<?php

namespace App\Http\Controllers;

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
}
