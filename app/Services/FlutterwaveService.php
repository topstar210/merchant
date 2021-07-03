<?php


namespace App\Services;


use App\Http\Controllers\WalletController;
use App\Http\Utils\Resource;
use App\Models\Deposit;
use App\Models\MerchantPayment;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveService
{
    public static function handlePayment(MerchantPayment $trans)
    {
        try {
            $result = Http::post(config('env.fw_verify_url'), [
                "txref" => $trans->reference,
                "SECKEY" => config('env.fw_sec_key')
            ]);

            $response = $result->json();

            if ($result->status() != 200) {
                $response['status'] = "failed";
            }

            $final = [
                'status' => $response['status'] == 'success',
                'message' =>  ($response['data']['paymenttype'] == 'card' ? "Deposit Transaction with (" . $response['data']['card']['brand'] . " - " . $response['data']['card']['cardBIN'] . "***" . $response['data']['card']['last4digits'] . " )" : "Deposit Transaction with (" . $response['data']['paymenttype'] . " )")
            ];

            $transaction = Resource::saveDepositTrans($trans, $final);

            $trans->transaction()->associate($transaction);
            $trans->status = $transaction->status;

            $data = $trans->response;
            unset($data['response']);
            $data['response'] = $response;

            $trans->response = $data;

            if ($trans->status == "Success") {
                $balance = (new WalletController())->creditWallet($trans->wallet, $trans->amount);
                $trans->balance_after = $balance;
                $transaction->available_amount = $balance;
            }

            $trans->save();
            $transaction->save();

        } catch (\Exception $e) {
            Log::error('Exception Error processing Flutterwave Payment', format_exception($e));
        }
    }

}
