<?php

namespace App\Http\Controllers;

use App\Models\MerchantPayment;
use App\Models\TempTransactions;
use App\Models\Wallet;
use App\Models\WalletDebitLock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    //
    public function __construct()
    {

    }

    public function view(Request $request, Wallet $wallet)
    {
        return view('app.wallet.view', ['wallet' => $wallet]);
    }

    public function initDeposit(Request $request, Wallet $wallet)
    {
        return view('app.wallet.deposit', ['wallet' => $wallet]);
    }

    public function confirmDeposit(Request $request, Wallet $wallet, TempTransactions $temp)
    {
        return view('app.wallet.deposit-confirm', ['wallet' => $wallet, 'temp' => $temp]);
    }

    public function creditWallet(Wallet $wallet, $amount)
    {
        $wallet->refresh();
        $wallet->balance = (double)($wallet->balance + $amount);
        $wallet->save();

        return $wallet->balance;
    }

    public function walletCommissionWithdrawal(Wallet $wallet, $amount)
    {
        $wallet->refresh();
        $wallet->balance = (double)($wallet->balance + $amount);
        $wallet->commission = (double)($wallet->commission - $amount);
        $wallet->save();

        return $wallet->balance;
    }

    public function debitWallet(Wallet $wallet, $amount)
    {
        $wallet->refresh();
        $wallet->balance = (double)($wallet->balance - $amount);
        $wallet->save();

        return $wallet->balance;
    }

    public function lockDebit(Wallet $wallet, $amount, $reference)
    {
        $wallet->balance = (double)($wallet->balance - $amount);
        $wallet->save();

        return $wallet->debit_locks()->create([
            'amount' => $amount,
            'reference' => $reference
        ]);
    }

    public function removeLockDebit(MerchantPayment $transaction)
    {
        $transaction->debit_lock()->delete();

        return $transaction->wallet->balance;
    }

    public function reverseLockDebit(MerchantPayment $transaction)
    {
        $lock = $transaction->debit_lock;

        if (!is_null($lock)) {
            $balance = (double)($transaction->wallet->balance + $lock->amount);

            $transaction->wallet()->update(['balance' => $balance]);

            $transaction->debit_lock()->delete();
        }

        return $transaction->wallet->balance;
    }

}
