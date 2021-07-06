<?php


namespace App\Services;


use App\Http\Controllers\WalletController;
use App\Http\Utils\Resource;
use App\Mail\TransactionReceipt;
use App\Models\Deposit;
use App\Models\MerchantPayment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FlutterwaveService
{
    public static function checkStatus(MerchantPayment $trans)
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

            return [
                'status' => $response['status'] == 'success' ? 1 : 2,
                'message' => $response['status'] == 'success' ? ($response['data']['paymenttype'] == 'card' ? "Deposit Transaction with (" . $response['data']['card']['brand'] . " - " . $response['data']['card']['cardBIN'] . "***" . $response['data']['card']['last4digits'] . " )" : "Deposit Transaction with (" . $response['data']['paymenttype'] . " )") : "Unable to verify deposit transaction",
                'response' => $response
            ];

        } catch (\Exception $e) {
            Log::error('Exception Error processing Flutterwave Payment', format_exception($e));

            return [
                'status' => 2,
                'message' => "Unable to verify deposit transaction",
                'response' => []
            ];
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

    public static function sendHandler($account, $bank, $amount, $narration, $currency, $reference, $account_name)
    {
        $data = [
            "account_bank" => $bank['Code'],
            "account_number" => $account,
            "amount" => $amount,
            "seckey" => config('env.fw_sec_key'),
            "narration" => $narration,
            "currency" => $currency,
            "reference" => $reference,
            "callback_url" => url('api/webhook/send/' . rawurlencode('Flutterwave Payout') . '/' . $reference),
            "debit_currency" => $currency
        ];

        if (in_array($currency, ['KES', 'RWF', 'TZS', 'UGX', 'XAF', 'XOF', 'ZMW', 'ZAR'])) {
            $data = array_merge($data, ['beneficiary_name' => $account_name]);
        }

        $response = Http::post(config('env.fw_send_url'), $data);

        Log::info($response->json());

        if ($response->status() != 200) {
            return [
                "status" => 'failed',
                "message" => "Unable to process Send to Bank"
            ];
        }

        if ($response->json()['status'] !== 'success') {
            return [
                "status" => 'failed',
                "message" => $response->json()['message']
            ];
        }

        return [
            "status" => 'pending',
            "message" => $response->json()['message']
        ];

    }

    public static function webhookHandler(Request $request, MerchantPayment $trans)
    {
        $data = $request->all();

        return [
            "status" => $data['transfer']['status'] == "SUCCESSFUL" ? 'success' : 'failed',
            "message" => $data['transfer']['complete_message'],
            "response" => $data
        ];
    }
}
