<?php


namespace App\Http\Utils;


use App\Exports\TransactionReceipt;
use App\Models\ActivityLog;
use App\Models\Deposit;
use App\Models\MerchantPayment;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

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
        $transaction->available_amount = $response['balance'] ?? 0;
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


    public static function sortTransactionCollection($transactions)
    {

        $_transactions = [];
        foreach ($transactions as $record) {
            $data = [
                'Reference' => (string)$record->reference,
                'Date' => date('d F Y h:i a', strtotime($record->created_at)),
                'Transaction Type' => $record->service,
                'Transaction Service' => switchProducts($record->product),
                'Wallet' => $record->base_currency,
                'Amount' => $record->amount,
                'Charge' => $record->charge,
                'Total' => $record->total_amount,
                'Commission' => $record->commission,
                'Balance Before' => $record->balance_before,
                'Balance After' => $record->balance_after,
                'Account' => $record->account,
                'Account Name' => $record->account_name,
                'Institution' => $record->institution,
                'Status' => $record->status,
                'Exchange Currency' => $record->exchange_currency,
                'Exchange Rate' => $record->exchange_rate,
                'Exchange Amount' => $record->exchange_amount,
            ];

            if (user()->isMerchant()) {
                 array_merge($data, ['Initiator' => $record->user == user()->first_name ? 'You' : $record->user]);
            }

            array_push($_transactions, $data);
        }



        return $_transactions;
    }


    public static function downloadReceipt(MerchantPayment $transaction)
    {
        $invoice = \PDF::loadView('exports.pdf.transaction', $transaction);
        return $invoice->download($transaction->reference . ".pdf");
    }

    public static function logActivity($activity)
    {
        ActivityLog::create([
            "user_id" => user()->id,
            "type" => "User",
            "ip_address" => request()->ip(),
            "browser_agent" => request()->userAgent(),
            "activity" => $activity,
        ]);
    }

}
