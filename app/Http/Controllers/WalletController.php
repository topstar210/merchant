<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

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
}
