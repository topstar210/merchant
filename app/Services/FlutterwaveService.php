<?php


namespace App\Services;


use App\Http\Controllers\WalletController;
use App\Http\Utils\Resource;
use App\Mail\TransactionReceipt;
use App\Models\Deposit;
use App\Models\MerchantPayment;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
                'message' => ($response['data']['paymenttype'] == 'card' ? "Deposit Transaction with (" . $response['data']['card']['brand'] . " - " . $response['data']['card']['cardBIN'] . "***" . $response['data']['card']['last4digits'] . " )" : "Deposit Transaction with (" . $response['data']['paymenttype'] . " )")
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

            try {
                Mail::to($trans->user->email)->queue(new TransactionReceipt($trans));
            } catch (\Exception $e) {
                Log::error('Exception Error sending Deposit Receipt Email', format_exception($e));
            }

        } catch (\Exception $e) {
            Log::error('Exception Error processing Flutterwave Payment', format_exception($e));
        }
    }

    public static function getBanks($code)
    {
        $code = substr($code, 0, 2);
        $url = config('env.fw_bank_url') . "/$code?public_key=" . config('env.fw_pub_key');
        $response = Http::get($url);

        if ($response->status() != 200) {
            return [];
        } else {
            if (!isset($response->json()['data']['Banks'])) {
                return [];
            }
            return $response->json()['data']['Banks'];
        }
    }

    public static function nameEnquiry($account, $bank)
    {
        $response = Http::post(config('env.fw_validate_url'), [
            "recipientaccount" => $account,
            "destbankcode" => $bank['Code'],
            "PBFPubKey" => config('env.fw_pub_key')
        ]);

        if ($response->status() != 200) {
            return null;
        }

        if ($response->json()['status'] !== 'success') {
            return null;
        }

        if (is_null($response->json()['data']['data']['accountname'])) {
            return null;
        }

        return $response->json()['data']['data']['accountname'];
    }
}
