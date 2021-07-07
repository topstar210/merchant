<?php


namespace App\Services;


use App\Http\Controllers\WalletController;
use App\Http\Utils\Resource;
use App\Mail\TransactionReceipt;
use App\Models\Deposit;
use App\Models\MerchantPayment;
use App\Models\Transaction;
use App\Services\Deposit\DepositService;
use Illuminate\Http\Request;
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

        $result = Http::withHeaders([
            'Authorization' => self::computeAuth($data),
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
            "message" => $response['resp_code'] == '000' ? $response['resp_desc'] : 'Payment initialization failed. Try another option',
        ];
    }


    public static function handlePayment(MerchantPayment $trans, $response)
    {
        try {
            $response['code'] = substr($response['trans_status'], 0, 3);

            $final = [
                'status' => $response['code'] == '000' ? 1 : 2,
                'message' => $response['message'],
                'response' => $response
            ];

            DepositService::completeDeposit($trans, $final);

            return response()->json(["error" => false, "error_message" => "Transaction processed successfully"]);

        } catch (\Exception $e) {
            Log::error('Exception Error processing Orchard Payment', format_exception($e));

            return response()->json(["error" => true, "error_message" => "Unable to complete transaction update"]);

        }
    }

    public static function getBanks()
    {
        return [
            ["Id" => 1, "Name" => "MTN MOBILE MONEY", "Code" => "MTN"],
            ["Id" => 2, "Name" => "VODAFONE CASH", "Code" => "VOD"],
            ["Id" => 3, "Name" => "AIRTELTIGO CASH", "Code" => "AIR"],
            ["Id" => 4, "Name" => "STANDARD CHARTERED BANK", "Code" => "SCB"],
            ["Id" => 5, "Name" => "BARCLAYS BANK", "Code" => "BAR"],
            ["Id" => 6, "Name" => "GCB BANK LTD", "Code" => "GCB"],
            ["Id" => 7, "Name" => "NATIONAL INVESTMENT BANK", "Code" => "NIB"],
            ["Id" => 8, "Name" => "AGRICULTURAL DEVELOPMENT BANK", "Code" => "ADB"],
            ["Id" => 9, "Name" => "UNIVERSAL MERCHANT BANK", "Code" => "UMB"],
            ["Id" => 10, "Name" => "HFC BANK", "Code" => "HFC"],
            ["Id" => 11, "Name" => "ZENITH BANK", "Code" => "ZEB"],
            ["Id" => 12, "Name" => "ECOBANK", "Code" => "ECO"],
            ["Id" => 13, "Name" => "CAL BANK", "Code" => "CAL"],
            ["Id" => 14, "Name" => "PRUDENTIAL BANK LTD", "Code" => "PRD"],
            ["Id" => 15, "Name" => "STANBIC BANK", "Code" => "STA"],
            ["Id" => 16, "Name" => "FIRST BANK OF NIGERIA", "Code" => "FBN"],
            ["Id" => 17, "Name" => "BANK OF AFRICA", "Code" => "BOA"],
//            ["Id" => 18, "Name" => "UNIBANK", "Code" => "UNI"],
            ["Id" => 19, "Name" => "GUARANTY TRUST BANK", "Code" => "GTB"],
            ["Id" => 20, "Name" => "FIDELITY BANK", "Code" => "FID"],
//            ["Id" => 21, "Name" => "BSIC", "Code" => "BSI"],
            ["Id" => 22, "Name" => "BANK OF BARODA", "Code" => "BOB"],
            ["Id" => 23, "Name" => "ACCESS BANK", "Code" => "ACC"],
//            ["Id" => 24, "Name" => "ENERGY BANK", "Code" => "ENE"],
//            ["Id" => 25, "Name" => "ROYAL BANK", "Code" => "ROY"],
            ["Id" => 26, "Name" => "FIRST NATIONAL BANK", "Code" => "FNB"],
            ["Id" => 27, "Name" => "SOVEREIGN BANK", "bank_code" => "SOV "],
//            ["Id" => 28, "Name" => "PREMIUM BANK", "Code" => "PRM"],
//            ["Id" => 29, "Name" => "HERITAGE BANK", "Code" => "HRT"],
            ["Id" => 30, "Name" => "UNITED BANK OF AFRICA", "Code" => "UBA"],
        ];
    }

    public static function nameEnquiry($account, $bank)
    {
        $response = Http::post(config('env.orc_validate_url'), [
            "customer_number" => $account,
            "bank_code" => $bank['Code'],
            "trans_type" => "AII",
            "service_id" => config('env.orc_service_id')
        ]);

        if ($response->status() != 200) {
            return null;
        }

        if ($response->json()['resp_code'] == "027" && isset($response->json()['account_name'])) {
            return $response->json()['account_name'];
        }

        return null;
    }

    public static function sendHandler($account, $bank, $amount, $narration, $reference, $sender)
    {
        $momo = in_array($bank['Code'], ['MTN', 'VOD', 'AIR']);

        $data = [
            "customer_number" => $account,
            "amount" => $amount,
            "exttrid" => $reference,
            "reference" => $momo ? (substr($sender, 0, 25)) : $narration,
            "service_id" => config('env.orc_service_id'),
            "ts" => gmdate("Y-m-d H:i:s", time() + 3600 * (0 + date("I"))),
            "trans_type" => "MTC",
            "nw" => $momo ? $bank['Code'] : "BNK",
            "callback_url" => url('api/webhook/send/' . rawurlencode('CyberPay Payout') . '/' . $reference),
        ];

        if (!$momo) {
            $data = array_merge($data, ["bank_code" => $bank['Code']]);
        }

        $response = Http::withHeaders([
            'Authorization' => self::computeAuth($data),
            'Content-Type' => 'application/json',
            'timeout' => 180,
            'open_timeout' => 180
        ])->post(config('env.orc_payout_url'), $data);

        Log::info($response->json());

        if ($response->status() != 200) {
            return [
                "status" => 'failed',
                "message" => "Unable to process Send to Bank"
            ];
        }

        if ($response->json()['resp_code'] === '015') {
            return [
                "status" => 'pending',
                "message" => $response->json()['resp_desc']
            ];
        }

        return [
            "status" => 'failed',
            "message" => $response->json()['resp_desc'] ?? "Unable to complete transaction."
        ];
    }

    public static function webhookHandler(Request $request, MerchantPayment $trans)
    {
        if (in_array($request->ip(), ['159.8.210.195'])) {
            $data = $request->all();

            $code = substr($data['trans_status'], 0, 3);

            return [
                "status" => $code == "000" ? 'success' : 'failed',
                "message" => $data['message'],
                "response" => $data
            ];
        } else {
            return [
                "status" => 'failed',
                "message" => "invalid IP access",
            ];
        }
    }

    public static function requery($reference)
    {
        $data = [
            'trans_type' => 'TSC',
            'service_id' => config('env.orc_service_id'),
            'exttrid' => $reference
        ];

        $response = Http::withHeaders([
            'Authorization' => self::computeAuth($data),
            'Content-Type' => 'application/json',
            'timeout' => 180,
            'open_timeout' => 180
        ])->post(config('env.orc_requery_url'), $data);

        if ($response->status() != 200) {
            return [
                "status" => 'pending',
                "message" => "Unable to process transaction requery",
            ];
        }

        $code = substr($response->json()['trans_status'], 0, 3);

        if ($code == "000") {
            return [
                "status" => 'success',
                "message" => $response->json()['message'],
                "response" => $response->json()
            ];
        } elseif ($code == "015") {
            return [
                "status" => 'pending',
                "message" => $response->json()['message'],
            ];

        } else {
            return [
                "status" => 'failed',
                "message" => $response->json()['message'],
                "response" => $response->json()
            ];
        }
    }

    private static function computeAuth($data)
    {
        $data_string = json_encode($data);
        $signature = hash_hmac('sha256', $data_string, config('env.orc_secret_key'));
        return config('env.orc_client_key') . ':' . $signature;
    }

}
