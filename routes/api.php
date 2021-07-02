<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::get('/user', function (Request $request) {
//    return ["hello"];
//});

Route::prefix('v1')->group(function () {
    Route::post('create/merchant', [\App\Http\Controllers\Auth\RegisterController::class, 'createMerchant']);
    Route::get('currency/update', [\App\Http\Controllers\ExchangeController::class, 'currencyRateUpdate']);
});
