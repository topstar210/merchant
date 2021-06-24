<?php

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
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index']);
    Route::prefix('agents')->group(function () {
        Route::get('/', \App\Http\Livewire\App\Agents\All::class);
        Route::get('/add', \App\Http\Livewire\App\Agents\Add::class);
        Route::get('/{user}', \App\Http\Livewire\App\Agents\View::class)->missing(function (Request $request) {
            return Redirect::back()->with(['error_message' => 'No agent found']);
        });
    });
});
