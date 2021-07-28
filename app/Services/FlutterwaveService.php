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
        $short_code = substr($code, 0, 2);
        $url = config('env.fw_bank_url') . "/$short_code?public_key=" . config('env.fw_pub_key');
        $response = Http::get($url);

        if ($response->status() != 200) {
            return [];
        }

        if ($response->json()['status'] == 'success') {
            if (in_array($code, ['XOF', 'XAF'])) {
                return [
                    ['Id' => 10000, 'code' => 'FMM', 'Name' => 'Francophone Mobile Money']
                ];
            }
            if (count($response->json()['data']['Banks'])) {
                return $response->json()['data']['Banks'];
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

    public static function sendHandler($account, $bank, $amount, $narration, $currency, $reference, $account_name, $data_extra)
    {
        $data = [

            "amount" => $amount,
            "seckey" => config('env.fw_sec_key'),
            "narration" => $narration,
            "currency" => $currency,
            "reference" => $reference,
            "callback_url" => url('api/webhook/send/' . rawurlencode('Flutterwave Payout') . '/' . $reference),
            "debit_currency" => $currency
        ];

        if (in_array($currency, ['GHS', 'KES', 'RWF', 'TZS', 'UGX', 'XAF', 'XOF', 'ZMW', 'ZAR'])) {
            $data = array_merge($data, ['beneficiary_name' => $account_name]);
        }

        if (in_array($currency, ['USD', 'EUR', 'GBP'])) {
            $data = array_merge($data,
                [
                    'beneficiary_name' => $account_name,
                    "meta" => [
                        [
                            "AccountNumber" => $account,
                            "RoutingNumber" => $data_extra['extra']['routing_number'],
                            "SwiftCode" => $data_extra['extra']['swift_code'],
                            "BankName" => $data_extra['extra']['recipient_bank'],
                            "BeneficiaryName" => $account_name,
                            "BeneficiaryCountry" => $data_extra['extra']['recipient_country']
                        ]
                    ]
                ]
            );

            if (in_array($currency, ['USD'])) {
                $data['meta'][0] = array_merge($data['meta'][0], [
                    "BeneficiaryAddress" => $data_extra['extra']['beneficiary_address']
                ]);
            }

            if (in_array($currency, ['EUR', 'GBP'])) {
                $data['meta'][0] = array_merge($data['meta'][0], [
                    "PostalCode" => $data_extra['extra']['postal_code'], // Beneficiary postal code
                    "StreetNumber" => $data_extra['extra']['street_number'],
                    "StreetName" => $data_extra['extra']['street_name'],
                    "City" => $data_extra['extra']['city']
                ]);
            }
        } else {
            $data = array_merge($data, [
                "account_bank" => $bank['Code'],
                "account_number" => $account,
            ]);
            if (in_array($currency, ['ZAR'])) {
                $data = array_merge($data, [
                    "meta" => [
                        [
                            "FirstName" => explode(' ', $account_name)[0],
                            "LastName" => explode(' ', $account_name)[1] ?? '',
                            "EmailAddress" => "info@imorapidtransfer.com",
                            "MobileNumber" => "+233303979715",
                            "Address" => $data_extra['extra']['beneficiary_address']
                        ]
                    ]
                ]);
            }
        }

        if (isset($data['meta'])) {
            $data['meta'][0] = (object)$data['meta'][0];
        }

        Log::info($data);

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
