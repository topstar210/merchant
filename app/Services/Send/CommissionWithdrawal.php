<?php


namespace App\Services\Send;


use App\Http\Controllers\WalletController;
use App\Http\Utils\Resource;
use App\Mail\TransactionReceipt;
use App\Models\MerchantPayment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CommissionWithdrawal
{
    public static function handleSend(MerchantPayment $trans)
    {

        try {
            if ($trans->wallet->commission > $trans->amount) {
                $balance = (new WalletController())->walletCommissionWithdrawal($trans->wallet, $trans->amount);

                $final_send = [
                    'status' => 1,
                    'message' => 'Commission to Wallet Withdrawal Successful | ' . $trans->base_currency . '' . $trans->amount,
                    'balance' => $balance,
                ];
            } else {
                $balance = $trans->wallet->balance;

                $final_send = [
                    'status' => 2,
                    'message' => 'Insufficient commission balance. Commission to wallet withdrawal failed',
                    'balance' => $balance,
                ];
            }

            $transaction = Resource::saveDepositTrans($trans, $final_send);

            $trans->transaction()->associate($transaction);
            $trans->status = $transaction->status;
            $trans->message = $final_send['message'];
            $trans->balance_after = $balance;

            $trans->save();

            try {
                Mail::to($trans->user->email)->queue(new TransactionReceipt($trans));
            } catch (\Exception $e) {
                Log::error('Exception Error sending Send Receipt Email', format_exception($e));
            }


        } catch (\Exception $e) {
            Log::error('Exception Error Handling Commission Withdrawal Transaction', format_exception($e));

        }
    }
}
