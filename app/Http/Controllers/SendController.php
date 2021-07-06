<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessSend;
use App\Models\Currency;
use App\Models\MerchantPayment;
use App\Models\TempTransactions;
use App\Models\Wallet;
use Illuminate\Http\Request;

class SendController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('app.send.index');
    }

    public function initSend(Request $request, Wallet $wallet)
    {
        return view('app.send.initiate_send', ['wallet' => $wallet]);
    }

    public function initSendBank(Request $request, Wallet $wallet)
    {
        if ($request->session()->has('sendBank_' . $wallet->id)) {

            $data = $request->session()->get('sendBank_' . $wallet->id);
            $data['send_currency'] = Currency::query()->find($data['send_currency']);

            return view('app.send.initiate_send_bank', ['wallet' => $wallet, 'data' => $data]);
        } else {
            return redirect('app')->with(['error' => true, 'error_message' => 'No send session initiated']);
        }
    }

    public function confirmSend(Request $request, Wallet $wallet, TempTransactions $temp)
    {
        return view('app.send.confirm_send', ['wallet' => $wallet, 'temp' => $temp]);
    }

    public function initializeSWSATransaction(TempTransactions $temp)
    {
        $tp = $temp->replicate();

        $temp->delete();

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
            'service' => "WITHDRAWAL",
            'balance_before' => $tp->wallet->balance,
            'product' => $tp->data['service'],
            'response' => $tp->data,
            'wallet_id' => $tp->wallet_id,
            'payment_method_id' => $tp->payment_method_id
        ]);

        ProcessSend::dispatch($transaction);

        return redirect('app/transaction/process/' . $tp->reference);
    }

    public function initializeSBTransaction(TempTransactions $temp)
    {
        $tp = $temp->replicate();

        $temp->delete();

        if ($tp->data['total'] > $tp->wallet->balance) {
            return redirect('app/')->with(['error' => true, 'error_message' => 'Transaction can not be processed. Insufficient wallet balance']);
        }

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
            'institution' => $tp->data['bank']['Name'] ?? $tp->data['bank']['bankName'],
            'service' => "WITHDRAWAL",
            'balance_before' => $tp->wallet->balance,
            'product' => $tp->data['service'],
            'response' => $tp->data,
            'wallet_id' => $tp->wallet_id,
            'payment_method_id' => $tp->route->payment_method->id
        ]);

        (new WalletController())->lockDebit($tp->wallet, $tp->data['total'], $tp->reference);

        ProcessSend::dispatch($transaction);

        return redirect('app/transaction/process/' . $tp->reference);
    }
}
