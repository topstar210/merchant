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

class PayswitchService
{
    public static function checkStatus(MerchantPayment $trans)
    {
        try {
            $response = Http::withHeaders([
                'Merchant-Id' => config('env.ps_mid')
            ])->get(config('env.ps_verify_url') . $trans->reference . '/status');

            Log::info('PaySwitch Check Status for:' . $trans->reference, $response->json());

            if ($response->status() != 200) {
                return [
                    'status' => 2,
                    'message' => "Unable to verify Deposit Transaction",
                    'response' => []
                ];
            }

            $msg = $response->json()['code'] == "000" ? ("Actual Debit amount of " . $trans->response['to_currency'] . " " . $trans->response['exchange_amount'] . ", at the rate of " . $trans->response['from_currency'] . $trans->response['exchange_rate'] . " => " . $trans->response['to_currency'] . "1") : "";
            $msg1 = $response->json()['code'] == "000" ? ("Deposit Transaction with " . $response->json()['r_switch'] . " " . $response->json()['subscriber_number'] . ". " . $msg) : ("Deposit Transaction Failed. " . $response->json()['reason'] ?? "");

            return [
                'status' => $response->json()['code'] == '000' ? 1 : 2,
                'message' => $msg1,
                'response' => $response->json()
            ];


        } catch (\Exception $e) {
            Log::error('Exception Error processing Payswitch Payment', format_exception($e));

            return [
                'status' => 2,
                'message' => "Unable to verify deposit transaction",
                'response' => []
            ];
        }
    }
}
