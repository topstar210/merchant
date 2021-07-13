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
            $response = Http::withToken(config('env.fw_sec_key'))->get(config('env.fw_verify_url') . '/' . $trans->response['response']['id'] . '/verify');

            Log::info('Flutterwave CheckStatus for:' . $trans->reference, $response->json());

            if ($response->status() != 200) {
                return [
                    'status' => 2,
                    'message' => "Unable to verify deposit transaction",
                    'response' => $response->json()
                ];
            }

            if ($response->json()['status'] == 'success' && $response->json()['data']['status'] == 'successful') {
                return [
                    'status' => 1,
                    'message' => ($response->json()['data']['payment_type'] == 'card' ? "Deposit Transaction with (" . $response->json()['data']['card']['type'] . " - " . $response->json()['data']['card']['first_6digits'] . "***" . $response->json()['data']['card']['last_4digits'] . ") | Card Country:" . $response->json()['data']['card']['country'] . " | Amount: " . $response->json()['data']['currency'] . $response->json()['data']['amount'] : "Deposit Transaction with (" . $response->json()['data']['payment_type'] . " )"),
                    'response' => $response->json()
                ];
            }

            return [
                'status' => 2,
                'message' => "Unable to verify deposit transaction",
                'response' => $response->json()
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
        }

        if ($response->json()['status'] == 'success') {
            if (count($response->json()['data']['Banks'])) {
                return $response->json()['data']['Banks'];
            }
            if (in_array($code, ['XOF', 'XAF'])) {
                return [
                    ['Id' => 10000, 'code' => 'FMM', 'Name' => 'Francophone Mobile Money']
                ];
            }
        }

        return [];

    }

    public static function nameEnquiry($account, $bank)
    {
        $response = Http::withToken(config('env.fw_sec_key'))
            ->post(config('env.fw_validate_url'), [
                    "account_number" => $account,
                    "account_bank" => $bank['Code'],
                ]
            );

        Log::info('Flutterwave Name Enquiry for:' . $account, $response->json());

        if ($response->status() != 200) {
            return null;
        }

        if ($response->json()['status'] == 'success') {
            return $response->json()['data']['account_name'];
        }

        return null;


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

        Log::info('Flutterwave initial response for:' . $reference, $response->json());

        if ($response->status() != 200) {
            return [
                "status" => 'failed',
                "message" => "Unable to process Send to Bank",
            ];
        }

        if ($response->json()['status'] !== 'success') {
            return [
                "status" => 'failed',
                "message" => $response->json()['data']['complete_message'],
                "response" => $response->json()['data']
            ];
        }

        return [
            "status" => 'pending',
            "message" => $response->json()['message'],
            "response" => $response->json()['data']
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

    public static function requery($ref, $reference)
    {
        $response = Http::withToken(config('env.fw_sec_key'))
            ->get(config('env.fw_requery_url') . $ref);

        Log::info('Flutterwave Requery for:' . $reference, $response->json());

        if ($response->status() != 200) {
            return [
                "status" => 'pending',
                "message" => "Unable to process transaction requery",
                "response" => ['id' => $ref]
            ];
        }

        if ($response->json()['status'] == 'success' && $response->json()['data']['status'] == "SUCCESSFUL") {
            return [
                "status" => 'success',
                "message" => $response->json()['data']['complete_message'],
                "response" => $response->json()['data']
            ];
        } elseif ($response->json()['status'] == 'success' && $response->json()['data']['status'] == "FAILED") {
            return [
                "status" => 'failed',
                "message" => $response->json()['data']['complete_message'],
                "response" => $response->json()['data']
            ];
        } else {
            return [
                "status" => 'pending',
                "message" => $response->json()['data']['complete_message'],
                "response" => ['id' => $ref]
            ];
        }
    }
}
