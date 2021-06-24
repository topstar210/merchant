<?php


namespace App\Http\Utils;


use Illuminate\Validation\Rules\Password;

class Rules
{

    public static function completeSetupRules()
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

    public static function addAgentRules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'country' => 'required|exists:countries,short_name',
            'phone' => 'required|phone|unique:users,phone',
            'phone_country' => 'required_with:phone',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'gender' => 'required|in:male,female',
        ];
    }
}
