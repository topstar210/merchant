<?php


namespace App\Services;


use App\Http\Controllers\WalletController;
use App\Http\Utils\Resource;
use App\Mail\DepositReceipt;
use App\Models\Deposit;
use App\Models\MerchantPayment;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
            'Authorization' => $headers,
            'Content-Type' => 'application/json',
            'timeout' => 180,
            'open_timeout' => 180
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


    public static function handlePayment(MerchantPayment $trans, $response)
    {
        try {
            $response['code'] = substr($response['trans_status'], 0, 3);

            $final = [
                'status' => $response['code'] == '000',
                'message' => $response['message']
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
                Mail::to($trans->user->email)->queue(new DepositReceipt($trans));
            } catch (\Exception $e) {
                Log::error('Exception Error sending Deposit Receipt Email', format_exception($e));
            }

            return response()->json(["error" => false, "error_message" => "Transaction processed successfully"]);

        } catch (\Exception $e) {
            Log::error('Exception Error processing Flutterwave Payment', format_exception($e));
        }
    }


    private static function computeAuth($data)
    {
        $data_string = json_encode($data);
        $signature = hash_hmac('sha256', $data_string, config('env.orc_secret_key'));
        return config('env.orc_client_key') . ':' . $signature;
    }
}
