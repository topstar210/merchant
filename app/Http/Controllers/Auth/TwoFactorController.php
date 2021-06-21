<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TwoFactorController extends Controller
{
    //

    public function index(Request $request)
    {

        if (session()->exists('twoFA')) {
            if (!session('twoFA.validated')) {
                return view('auth.two_factor');
            }
        }
        return redirect('logout');
    }

    public function store(Request $request)
    {
        if (session()->exists('twoFA')) {
            if (session('twoFA.token') == $request->pin) {
                session(['twoFA.validated' => true]);

                return redirect(RouteServiceProvider::HOME);
            } else {
                throw ValidationException::withMessages(['pin' => 'Invalid Authorization Token']);
            }

        }
        return redirect('logout');
    }
}
