<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
})->middleware('guest');

Auth::routes(['register' => false, 'verify' => false]);

Route::middleware('guest')->group(function () {
    Route::prefix('setup/complete')->group(function () {
        Route::get('/{signature}', [\App\Http\Controllers\Auth\RegisterController::class, 'completeSetupView']);
        Route::post('/{signature}', [\App\Http\Controllers\Auth\RegisterController::class, 'completeSetup']);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/login/authorization', [\App\Http\Controllers\Auth\TwoFactorController::class, 'index']);
    Route::post('/login/authorization', [\App\Http\Controllers\Auth\TwoFactorController::class, 'store']);
    Route::get('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logoutAlt']);
});

Route::middleware(['auth', 'twoFA'])->prefix('app')->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])
        ->name('app.dashboard')
        ->middleware('can:viewDashboard,App\Models\Permission');

    Route::prefix('agents')->middleware('can:isMerchant,App\Models\Permission')->group(function () {
        Route::get('/', [\App\Http\Controllers\AgentsController::class, 'all'])
            ->name('app.agents');
        Route::get('/add', [\App\Http\Controllers\AgentsController::class, 'add']);
        Route::get('/{user}', [\App\Http\Controllers\AgentsController::class, 'view'])
            ->middleware('can:viewAgent,user')
            ->missing(function (Request $request) {
                return Redirect::route('app.agents')->with(['error' => true, 'error_message' => 'No Agent found']);
            });
    });

    Route::prefix('account')->group(function () {
        Route::get('/', [\App\Http\Controllers\AccountController::class, 'index']);
        Route::get('security/{change}', [\App\Http\Controllers\AccountController::class, 'changeSecurity']);
    });

    Route::prefix('wallet')->group(function () {
        Route::get('/{wallet}', [\App\Http\Controllers\WalletController::class, 'view'])
            ->middleware('can:viewWallet,wallet')
            ->missing(function (Request $request) {
                return Redirect::route('app.dashboard')->with(['error' => true, 'error_message' => 'No Wallet Found']);
            });

        Route::get('/{wallet}/deposit', [\App\Http\Controllers\WalletController::class, 'initDeposit'])
            ->middleware('can:walletDeposit,wallet')
            ->missing(function (Request $request) {
                return Redirect::route('app.dashboard')->with(['error' => true, 'error_message' => 'No Wallet Found']);
            });

        Route::get('/{wallet}/deposit/{temp}', [\App\Http\Controllers\WalletController::class, 'confirmDeposit'])
            ->middleware('can:walletDeposit,wallet')
            ->missing(function (Request $request) {
                return Redirect::route('app.dashboard')->with(['error' => true, 'error_message' => 'No Initialized Transaction Found']);
            });
    });

    Route::prefix('send')->group(function () {
        Route::get('/', [\App\Http\Controllers\SendController::class, 'index'])
            ->middleware('can:shouldSend,App\Models\Permission');
        Route::get('/{wallet}', [\App\Http\Controllers\SendController::class, 'initSend'])
            ->middleware(['can:owner,wallet', 'can:shouldSend,App\Models\Permission', 'can:notLocked,wallet'])
            ->missing(function (Request $request) {
                return Redirect::route('app.dashboard')->with(['error' => true, 'error_message' => 'No Wallet Found']);
            });
        Route::get('/bank/{wallet}', [\App\Http\Controllers\SendController::class, 'initSendBank'])
            ->middleware(['can:owner,wallet', 'can:shouldSend,App\Models\Permission', 'can:notLocked,wallet', 'can:walletLimit,wallet'])
            ->missing(function (Request $request) {
                return Redirect::route('app.dashboard')->with(['error' => true, 'error_message' => 'No Wallet Found']);
            });
        Route::get('/{wallet}/{temp}', [\App\Http\Controllers\SendController::class, 'confirmSend'])
            ->middleware(['can:owner,wallet', 'can:shouldSend,App\Models\Permission', 'can:notLocked,wallet'])
            ->missing(function (Request $request) {
                return Redirect::route('app.dashboard')->with(['error' => true, 'error_message' => 'No Initialized Transaction Found']);
            });
    });

    Route::prefix('report')->group(function () {
        Route::prefix('transactions')->group(function () {
            Route::get('/', [\App\Http\Controllers\ReportController::class, 'allTransactions'])
                ->middleware('can:viewTransactions,App\Models\Permission');
            Route::get('/view/{reference}', [\App\Http\Controllers\ReportController::class, 'viewTransaction'])
                ->middleware('can:owner,reference')
                ->missing(function (Request $request) {
                    return Redirect::route('app.dashboard')->with(['error' => true, 'error_message' => 'No Transaction Found']);
                });
            Route::get('/receipt/{reference}', [\App\Http\Controllers\ReportController::class, 'downloadReceipt'])
                ->middleware('can:owner,reference')
                ->missing(function (Request $request) {
                    return Redirect::route('app.dashboard')->with(['error' => true, 'error_message' => 'No Transaction Found']);
                });
        });

    });


    Route::get('/transaction/process/{reference}', [\App\Http\Controllers\ProcessController::class, 'processTransaction'])
        ->middleware('can:processTransaction,reference')
        ->missing(function (Request $request) {
            return Redirect::route('app.dashboard')->with(['error' => true, 'error_message' => 'No Transaction Found or Transaction already processed']);
        });

    Route::match(['get', 'post'], 'deposit/webhook/{payment_method}/{temp}', [\App\Http\Controllers\WebHookController::class, 'initDeposit'])
        ->missing(function (Request $request) {
            return Redirect::route('app.dashboard')->with(['error' => true, 'error_message' => 'No Initialized Transaction Found']);
        });
});
