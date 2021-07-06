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
            $result = Http::withHeaders([
                'Merchant-Id' => config('env.ps_mid')
            ])->get(config('env.ps_verify_url') . $trans->reference . '/status');

            $response = $result->json();

            if ($result->status() != 200) {
                $response['code'] = "100";
            }

            $msg = $response['code'] == "000" ? ("Actual Debit amount of " . $trans->response['to_currency'] . " " . $trans->response['exchange_amount'] . ", at the rate of " . $trans->response['from_currency'] . $trans->response['exchange_rate'] . " => " . $trans->response['to_currency'] . "1") : "";
            $msg1 = $response['code'] == "000" ? ("Deposit Transaction with " . $response['r_switch'] . " " . $response['subscriber_number'] . ". " . $msg) : ("Deposit Transaction Failed. " . $response['reason'] ?? "");

            return [
                'status' => $response['code'] == '000' ? 1 : 2,
                'message' => $msg1,
                'response' => $response
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
