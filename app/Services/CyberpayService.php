<?php


namespace App\Services;


use App\Models\MerchantPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CyberpayService
{
    public static function getBanks()
    {
        $response = Http::get(config('env.cp_bank_url'));

        if ($response->status() != 200) {
            return [];
        } else {
            if (!$response->json()['succeeded']) {
                return [];
            }
            return $response->json()['data'];
        }
    }

    public static function nameEnquiry($account, $bank)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'ApiKey' => base64_encode(config('env.cp_integration_key'))
        ])->post(config('env.cp_validate_url'), [
            "bankCode" => $bank['bankCode'],
            "accountId" => $account
        ]);

        if ($response->status() != 200) {
            return null;
        }

        if (!$response->json()['succeeded']) {
            return null;
        }


        if (is_null($response->json()['data']['accountName'])) {
            return null;
        }

        return $response->json()['data']['accountName'];
    }

    public static function sendHandler($account, $bank, $amount, $narration, $reference, $account_name, $user)
    {
        $beneficiary = str_replace('  ', ' ', $account_name);
        $beneficiary = explode(' ', $beneficiary);

        $data = [
            "customerWalletCode" => config('env.cp_wallet_code'),
            "businessCode" => config('env.cp_business_code'),
            "beneficiaryLastName" => $beneficiary[0],
            "beneficiaryOtherName" => $beneficiary[1] ?? $beneficiary[0],
            "senderLastName" => $user->last_name,
            "senderOtherName" => $user->first_name,
            "amount" => (int)($amount * 100),
            "accountNumber" => $account,
            "bankCode" => $bank['bankCode'],
            "webHook" => url('api/webhook/send/' . rawurlencode('CyberPay Payout') . '/' . $reference),
            "merchantRef" => $reference,
            "narration" => $narration
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'ApiKey' => base64_encode(config('env.cp_integration_key'))
        ])->post(config('env.cp_send_url'), $data);

        Log::info($response->json());

        if ($response->status() != 200) {
            return [
                "status" => 'failed',
                "message" => "Unable to process Send to Bank"
            ];
        }

        if (!$response->json()['succeeded']) {
            return [
                "status" => 'failed',
                "message" => $response->json()['message']
            ];
        }

        if (($response->json()['succeeded'] && $response->json()['message'] == "Funds transfer completed") || ($response->json()['succeeded'] && $response->json()['message'] == "Transaction successful")) {
            return self::requery($response->json()['data']);
        }

        return [
            "status" => 'pending',
            "message" => $response->json()['message'],
            "response" => ['ref' => $response->json()['data']]
        ];

    }

    public static function webhookHandler(Request $request, MerchantPayment $trans)
    {
        $data = $request->all();

        return [
            "status" => $data['Data']['Status'] == "Successful" ? 'success' : 'failed',
            "message" => $data['Data']['Message'],
            "response" => $data
        ];
    }

    public static function requery($ref)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'ApiKey' => base64_encode(config('env.cp_integration_key'))
        ])->get(config('env.cp_send_url') . '/' . $ref . '/requery');

        Log::info($response);

        if ($response->json()['succeeded'] && $response->json()['data']['status'] == "Successful") {
            return [
                "status" => 'success',
                "message" => $response->json()['message'],
                "response" => $response->json()
            ];
        } else {
            return [
                "status" => 'pending',
                "message" => $response->json()['message'],
                "response" => ['ref' => $ref]
            ];
        }
    }
}
