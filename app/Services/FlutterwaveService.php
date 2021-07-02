<?php


namespace App\Services;


use App\Http\Controllers\WalletController;
use App\Models\Deposit;
use App\Models\MerchantPayment;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveService
{
    public static function handlePayment(MerchantPayment $trans)
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

            $transaction = self::saveDepositTrans($trans, $response);

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

        } catch (\Exception $e) {
            Log::error('Exception Error processing Flutterwave Payment', format_exception($e));
        }
    }

    private static function saveDepositTrans(MerchantPayment $trans, $response)
    {
        $deposits = new Deposit();
        $deposits->uuid = $trans->reference;
        $deposits->charge_percentage = $trans->response['charge_percentage'];
        $deposits->charge_fixed = $trans->response['charge_fixed'];
        $deposits->amount = $trans->amount;
        $deposits->status = $response['status'] == 'success' ? "Success" : 'Blocked';
        $deposits->user_id = $trans->user_id;
        $deposits->currency_id = $trans->wallet->currency_id;
        $deposits->payment_method_id = $trans->payment_method_id;
        $deposits->save();

        $transaction = new Transaction();
        $transaction->user_id = $trans->user_id;
        $transaction->currency_id = $trans->wallet->currency_id;
        $transaction->payment_method_id = $trans->payment_method_id;
        $transaction->transaction_reference_id = $deposits->id;
        $transaction->transaction_type_id = DEPOSITS;
        $transaction->uuid = $trans->reference;
        $transaction->subtotal = $trans->amount;
        $transaction->email = $trans->user->email;
        $transaction->phone = $trans->user->phone;
        $transaction->percentage = $trans->response['charge_percentage'];
        $transaction->charge_percentage = $trans->response['charge_percentage'];
        $transaction->charge_fixed = $trans->response['charge_fixed'];
        $transaction->total = $trans->response['total'];
        $transaction->status = $response['status'] == 'success' ? "Success" : 'Failed';
        $transaction->note = $response['data']['paymenttype'] == 'card' ? "Deposit Transaction with (" . $response['data']['card']['brand'] . " - " . $response['data']['card']['cardBIN'] . "***" . $response['data']['card']['last4digits'] . " )" : "Deposit Transaction with (" . $response['data']['paymenttype'] . " )";
        $transaction->available_amount = 0;
        $transaction->save();

        return $transaction;
    }

}
