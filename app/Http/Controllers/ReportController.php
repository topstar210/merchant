<?php

namespace App\Http\Controllers;

use App\Http\Utils\Resource;
use App\Models\MerchantPayment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    public function allTransactions(Request $request)
    {
        return view('app.report.transactions.all');
    }

    public function viewTransaction(Request $request, MerchantPayment $reference)
    {
        return view('app.report.transactions.view', ['transaction' => $reference->load('transaction')]);
    }

    public function downloadReceipt(MerchantPayment $reference)
    {
        return Resource::downloadReceipt($reference);

    }
}
