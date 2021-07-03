<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessDeposit;
use App\Models\MerchantPayment;
use App\Models\PaymentMethod;
use App\Models\TempTransactions;
use App\Services\OrchardServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebHookController extends Controller
{
    //
    public function initDeposit(Request $request, PaymentMethod $payment_method, TempTransactions $temp)
    {
        if ($payment_method->is($temp->route->payment_method)) {
            $tp = $temp->replicate();

            $temp->delete();

            $tp->data = array_merge($tp->data, ['response' => $request->except('_token')]);

            $transaction = MerchantPayment::query()->create([
                'merchant_id' => user()->merchant_id,
                'user_id' => $tp->wallet->user_id,
                'transaction_type' => $tp->transaction_type_id,
                'reference' => $tp->reference,
                'amount' => $tp->data['amount'],
                'charges' => $tp->data['charge'],
                'exchange_amount' => $tp->data['exchange_amount'],
                'exchange_rate' => $tp->data['exchange_rate'],
                'base_currency' => $tp->data['from_currency'],
                'exchange_currency' => $tp->data['to_currency'],
                'account' => $tp->data['account'] ?? null,
                'account_name' => $tp->data['account_name'] ?? null,
                'institution' => $tp->data['institution'] ?? null,
                'service' => "DEPOSIT",
                'balance_before' => $tp->wallet->balance,
                'product' => "Wallet Funding",
                'response' => $tp->data,
                'wallet_id' => $tp->wallet->id,
                'payment_method_id' => $payment_method->id
            ]);

            if (!in_array($payment_method, ['Orchard'])) {
                ProcessDeposit::dispatch($transaction);
            }

            return redirect('app/transaction/process/' . $tp->reference);
        }

        return redirect('app')->with(['error' => true, 'error_message' => 'Invalid Request Received. Try again']);
    }

    public function handleOrchardDeposit(Request $request, MerchantPayment $reference)
    {
        Log::info('Response from Orchard Payment', $request->all());
        if ($request->ip() == '159.8.210.195') {
            if ($reference->status !== 'Pending') {
                return response()->json(["error" => true, "error_message" => "Transaction already processed"]);
            }

            return OrchardServices::handlePayment($reference, $request->all());
        }

        return response()->json(["error" => true, "error_message" => "request from unknown IP"]);

    }
}
