<?php


namespace App\Services\Send;


use App\Http\Controllers\WalletController;
use App\Http\Utils\Resource;
use App\Mail\TransactionReceipt;
use App\Models\MerchantPayment;
use App\Services\CyberpayService;
use App\Services\FlutterwaveService;
use App\Services\OrchardServices;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBankService
{
    public static function handleSend(MerchantPayment $trans)
    {
        try {
            $narration = "Fund transfer from " . $trans->merchant->merchant_name . " to " . $trans->account_name;

            if ($trans->payment_method->name === 'CyberPay Payout') {
                $send = CyberpayService::sendHandler($trans->account, $trans->response['bank'], $trans->exchange_amount, $narration, $trans->reference, $trans->account_name, $trans->user);
            } elseif ($trans->payment_method->name === 'Flutterwave Payout') {
                $send = FlutterwaveService::sendHandler($trans->account, $trans->response['bank'], $trans->exchange_amount, $narration, $trans->exchange_currency, $trans->reference, $trans->account_name);
            } elseif ($trans->payment_method->name === 'Orchard') {
                $send = OrchardServices::sendHandler($trans->account, $trans->response['bank'], $trans->exchange_amount, $narration, $trans->reference, $trans->merchant->merchant_name);
            } else {
                $send = [
                    "status" => 'failed',
                    "message" => "Unable to process Send to Bank. No Service found"
                ];
            }

            self::completeSend($trans, $send);
        } catch (\Exception $e) {
            Log::error('Exception Error Handling Send to Bank Transaction Initiation', format_exception($e));
        }
    }

    public static function completeSend(MerchantPayment $trans, $response, $webhook = false)
    {
        try {
            if ($response['status'] == 'success') {
                $balance = (new WalletController())->removeLockDebit($trans);
                $status = 1;
            } elseif ($response['status'] == 'failed') {
                $balance = (new WalletController())->reverseLockDebit($trans);
                $status = 2;
            } else {
                $balance = $trans->wallet->balance;
                $status = 3;
            }

            if ($webhook) {
                $trans->status = switchTransStatus($status);
                $trans->message = $response['message'] ?? null;
                $trans->balance_after = $balance;

                $data = $trans->response;
                unset($data['response']);
                $data['response'] = $response['response'] ?? [];
                $trans->response = $data;

                $trans->save();

                $trans->transaction()->update(['status' => $trans->status, 'available_amount' => $balance]);

                $trans->transaction->withdrawal()->update(['status' => switchSubTransStatus($status)]);

            } else {
                $final_send = [
                    'status' => $status,
                    'message' => $response['status'] == 'success' ? ('Send to Bank Successful | ' . $trans->base_currency . '' . $trans->amount . ' | Recipient Account: ' . $trans->account_name . ' (' . $trans->account . ') ' . $trans->institution . ' | Sent Amount: ' . $trans->exchange_currency . '' . number_format($trans->exchange_amount, 2)) : $response['message'],
                    'balance' => $balance,
                ];

                $transaction = Resource::saveSendTrans($trans, $final_send);

                $trans->transaction()->associate($transaction);
                $trans->status = $transaction->status;
                $trans->message = $response['message'] ?? null;
                $trans->balance_after = $balance;

                if (isset($response['response'])) {
                    $data = $trans->response;
                    unset($data['response']);
                    $data['response'] = $response['response'];
                    $trans->response = $data;
                }

                $trans->save();
            }

            if ($status == 1) {
                $trans->commission = $trans->response['commission'];
                $trans->save();

                $total_commission = ($trans->wallet->commission + $trans->response['commission']);
                $trans->wallet()->update(['commission' => $total_commission]);
            }

            if (in_array($status, [1, 2])) {
                try {
                    Mail::to($trans->user->email)->queue(new TransactionReceipt($trans));
                } catch (\Exception $e) {
                    Log::error('Exception Error sending Send Receipt Email', format_exception($e));
                }
            }
        } catch (\Exception $e) {
            Log::error('Exception Error Handling Send to Bank Transaction Complete', format_exception($e));
        }

    }
}
