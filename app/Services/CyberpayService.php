<?php


namespace App\Services;


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

        Log::info($bank);
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
}
