<?php


namespace App\Services;


use App\Mail\AuthorizationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthorizationService
{
    /**
     * generate token and send
     * @return string
     */
    public static function handle() : String
    {
        $token = (string)rand(100000, 999999);
        Log::info($token);
        Mail::to(user()->email)->send(new AuthorizationMail($token));

        return $token;
    }

}
