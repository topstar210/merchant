<?php

namespace App\Http\Controllers;

use App\Services\ExchangeService;
use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    //

    public function currencyRateUpdate(Request $request)
    {
        return ExchangeService::updateCurrencies();
    }
}
