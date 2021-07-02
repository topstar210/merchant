<?php

namespace App\Http\Controllers;

use App\Models\MerchantPayment;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    //

    public function processTransaction(Request $request, MerchantPayment $reference)
    {
        return view('app.common.process', ['transaction' => $reference]);
    }

}
