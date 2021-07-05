<?php

namespace App\Http\Controllers;

use App\Models\TempTransactions;
use App\Models\Wallet;
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

    public function debitWallet(Wallet $wallet, $amount)
    {
        $wallet->refresh();
        $wallet->balance = (double)($wallet->balance - $amount);
        $wallet->save();

        return $wallet->balance;
    }

}
