<?php


namespace App\Services\Send;


use App\Http\Controllers\WalletController;
use App\Http\Utils\Resource;
use App\Mail\TransactionReceipt;
use App\Models\MerchantPayment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendAccountService
{
    public static function handleSend(MerchantPayment $trans)
    {
        try {
            $balance = (new WalletController())->debitWallet($trans->wallet, $trans->response['total']);

            $recipient_wallet = $trans->recipient_wallet;

            $final_send = [
                'status' => true,
                'message' => 'Send to Account Successful | ' . $trans->base_currency . '' . $trans->amount . ' | Recipient Account:' . $trans->account_name . ' (' . $trans->account . ')',
                'balance' => $balance,
            ];

            $transaction = Resource::saveSendTrans($trans, $final_send);

            $trans->transaction()->associate($transaction);
            $trans->status = $transaction->status;
            $trans->balance_after = $balance;


            //Handle 2nd leg of credit
            $deposit = $trans->replicate();
            $deposit->user_id = $recipient_wallet->user->id;
            $deposit->merchant_id = $recipient_wallet->user->merchant_id;
            $deposit->transaction_type = DEPOSITS;
            $deposit->charges = 0;
            $deposit->exchange_amount = (float)($deposit->amount * $deposit->exchange_rate);
            $deposit->amount = $deposit->exchange_amount;
            $deposit->service = "DEPOSIT";
            $deposit->product = "WF";
            $deposit->balance_before = $recipient_wallet->balance;
            $deposit->wallet_id = $recipient_wallet->id;
            $deposit->reference = (string)rand(100000000000, 999999999999);

            $balance_deposit = (new WalletController())->creditWallet($recipient_wallet, $deposit->exchange_amount);

            $final_deposit = [
                'status' => true,
                'message' => 'Wallet transfer received from ' . $trans->merchant->merchant_name . ' | ' . $trans->exchange_currency . $deposit->exchange_amount,
            ];

            $transaction_deposit = Resource::saveDepositTrans($trans, $final_deposit);

            if ($recipient_wallet->user->isMerchant() || $recipient_wallet->user->isAgent()) {
                $deposit->transaction()->associate($transaction_deposit);
                $deposit->balance_after = $balance_deposit;
                $deposit->save();
            }

            $transaction_deposit->available_amount = $balance_deposit;

            $trans->save();
            $transaction_deposit->save();

            try {
                Mail::to($trans->user->email)->queue(new TransactionReceipt($trans));
                Mail::to($recipient_wallet->user->email)->queue(new TransactionReceipt($deposit));
            } catch (\Exception $e) {
                Log::error('Exception Error sending Deposit Receipt Email', format_exception($e));
            }

        } catch (\Exception $e) {
            Log::error('Exception Error Handling Send to Wallet Transaction', format_exception($e));
        }
    }

}