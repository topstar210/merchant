<?php


namespace App\Http\Utils;


use App\Models\Deposit;
use App\Models\MerchantPayment;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Support\Arr;

class Resource
{
    public static function getCountryState($country)
    {
        $path = storage_path('app/data/states.json');
        $states = readJson($path);

        return Arr::where($states, function ($val, $key) use ($country) {
            return $val['country_code'] == $country;
        });
    }

    public static function saveDepositTrans(MerchantPayment $trans, $response)
    {
        $deposits = new Deposit();
        $deposits->uuid = $trans->reference;
        $deposits->charge_percentage = $trans->response['charge_percentage'];
        $deposits->charge_fixed = $trans->response['charge_fixed'];
        $deposits->amount = $trans->amount;
        $deposits->status = switchSubTransStatus($response['status']);
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
        $transaction->status = switchTransStatus($response['status']);
        $transaction->note = $response['message'];
        $transaction->available_amount = 0;
        $transaction->save();

        return $transaction;
    }

    public static function saveSendTrans(MerchantPayment $trans, $response)
    {
        $send = new Withdrawal();
        $send->uuid = $trans->reference;
        $send->charge_percentage = $trans->response['charge_percentage'];
        $send->charge_fixed = $trans->response['charge_fixed'];
        $send->amount = $trans->amount;
        $send->status = switchSubTransStatus($response['status']);
        $send->user_id = $trans->user_id;
        $send->currency_id = $trans->wallet->currency_id;
        $send->payment_method_id = $trans->payment_method_id;
        $send->payment_method_info = $trans->payment_method->name;
        $send->save();

        $transaction = new Transaction();
        $transaction->user_id = $trans->user_id;
        $transaction->currency_id = $trans->wallet->currency_id;
        $transaction->payment_method_id = $trans->payment_method_id;
        $transaction->transaction_reference_id = $send->id;
        $transaction->transaction_type_id = WITHDRAWALS;
        $transaction->uuid = $trans->reference;
        $transaction->subtotal = $trans->amount;
        $transaction->email = $trans->user->email;
        $transaction->phone = $trans->user->phone;
        $transaction->percentage = $trans->response['charge_percentage'];
        $transaction->charge_percentage = $trans->response['charge_percentage'];
        $transaction->charge_fixed = $trans->response['charge_fixed'];
        $transaction->total = $trans->response['total'];
        $transaction->status = switchTransStatus($response['status']);
        $transaction->note = $response['message'];
        $transaction->available_amount = $response['balance'] ?? 0;
        $transaction->save();

        return $transaction;
    }


}
