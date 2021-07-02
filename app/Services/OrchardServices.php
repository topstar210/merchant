<?php


namespace App\Services;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrchardServices
{
    public static function initiateCheckout($amount, $payment_option, $reference)
    {
        $data = [
            'amount' => $amount,
            'exttrid' => $reference,
            'reference' => 'IMO Rapid Transfer',
            'callback_url' => url('api/v1/webhook/deposit/Orchard/' . $reference),
            'service_id' => config('env.orc_service_id'),
            'ts' => gmdate("Y-m-d H:i:s", time() + 3600 * (0 + date("I"))),
            'landing_page' => url('app/deposit/webhook/Orchard/' . $reference),
            'payment_mode' => $payment_option,
            'currency_code' => "GHS",
            'currency_val' => $amount
        ];

        $headers = self::computeAuth($data);

        Log::info($headers);

        $result = Http::withHeaders([
            'Authorization: ' . $headers,
            'Content-Type: application/json',
            'timeout: 180',
            'open_timeout: 180'
        ])->post(config('env.orc_payment_url'), $data);

        $response = $result->json();

        Log::info($response);

        if ($result->status() !== 200) {
            $response['resp_code'] == '100';
        }

        return [
            "status" => $response['resp_code'] == '000',
            "link" => $response['redirect_url'] ?? null,
            "message" => $response['resp_code'] == '000' ? $response['resp_desc'] : 'Unable to initialize payment. Try another option',
        ];


    }

    private static function computeAuth($data)
    {
        $data_string = json_encode($data);
        $signature = hash_hmac('sha256', $data_string, config('env.orc_secret_key'));
        return config('env.orc_client_key') . ':' . $signature;
    }
}
