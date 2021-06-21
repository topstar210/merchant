<?php

namespace App\Http\Middleware;

use App\Services\AuthorizationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TwoFactor
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if (session()->exists('twoFA')) {
                if (session('twoFA.validated')) {
                    return $next($request);
                } else {
                    return redirect('login/authorization');
                }
            } else {
                $token = AuthorizationService::handle();
                session(['twoFA' => [
                    "token" => $token,
                    "validated" => false
                ]]);

                return redirect('login/authorization');
            }
        }
        return redirect('login');
    }
}
