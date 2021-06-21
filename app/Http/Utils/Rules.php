<?php


namespace App\Http\Utils;


use Illuminate\Validation\Rules\Password;

class Rules
{

    public static function createMerchantRules()
    {
        return
            [
                'password' => ['required', Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(3)],
                'password_confirmation' => 'required|same:password',
                'pin' => 'required|size:4',
            ];
    }
}
