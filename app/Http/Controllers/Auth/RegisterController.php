<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMerchantRequest;
use App\Http\Utils\Rules;
use App\Mail\AgentInvitedMail;
use App\Mail\MerchantCreatedMail;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Merchant;
use App\Models\Permission;
use App\Models\User;
use App\Services\AuthorizationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function createMerchant(CreateMerchantRequest $request)
    {
        try {
            $validated = $request->validated();

            $country = Country::query()->find((int)$validated['country_id']);
            $currency = $this->getCurrency($country->short_name);

            $default_currency_id = DB::table('settings')->where('name', 'default_currency')->first()->value ?? 24;
            $temp_password = Str::random(8);


            if (!$currency instanceof Currency) {
                $currency = (object)[
                    "code" => "USD",
                    "id" => $default_currency_id
                ];
            }

            $merchant = Merchant::query()->create([
                'mid' => (string)rand(10000000, 99999999),
                'merchant_name' => $validated['merchant_name'],
                'merchant_email' => $validated['email'],
                'merchant_address' => $validated['merchant_address'],
                'merchant_phone' => $validated['phone'],
                'country' => $country->name,
                'currency' => $currency->code,
                'logo' => $validated['logo'] ?? null,
                'site_url' => $validated['site_url'] ?? null,
                'commission' => (double)$validated['commission'],
            ]);

            $user = User::query()->create([
                'type' => 'merchant',
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'],
                'formattedPhone' => "+" . $country->phone_code . $validated['phone'],
                'defaultCountry' => strtolower($country->short_name),
                'carrierCode' => $country->phone_code,
                'email' => $validated['email'],
                'password' => Hash::make($temp_password),
                'status' => 'Inactive',
                'reg_com' => false,
                'account_number' => 'irt' . rand(10000000000, 99999999999),
                'address_verified' => true,
                'identity_verified' => true,
            ]);


            $user->userDetail()->create([
                'country_id' => (int)$validated['country_id'],
                'city' => $validated['city'],
                'address_1' => $validated['merchant_address'],
                'default_currency' => $currency->id,
                'timezone' => DB::table('settings')->where('name', 'default_timezone')->first()->value ?? 'Africa/Accra',
                'gender' => $validated['gender']
            ]);

            $wallets = [
                [
                    "currency_id" => $default_currency_id,
                    "balance" => 0.00,
                    "limit_amount" => 0.00,
                    "is_default" => 'No'
                ],
            ];

            if ($currency instanceof Currency) {
                $wallets[] = [
                    "currency_id" => $currency->id,
                    "balance" => 0.00,
                    "limit_amount" => 0.00,
                    "is_default" => 'Yes'
                ];
            }

            $user->wallets()->createMany($wallets);

            $user->merchant()->associate($merchant);
            $user->save();

//        $user->assignRole('merchant');
//        $user->givePermissionTo(Permission::systemDefaultPermissions());

            $hash = Hash::make((string)($user->id . ":" . $user->email));

            $signature = base64_encode("$hash:$user->id:$user->email");
            $url = url("/setup/complete/$signature");

            Mail::to($user)->send(new MerchantCreatedMail($user, $merchant, $url));

            return response()->json([
                "status" => "success",
                "message" => "Merchant Created Successfully",
                "mid" => $merchant->mid,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Exception Error for Create Merchant', format_exception($e));

            return response()->json([
                "status" => "failed",
                "message" => "Exception:" . $e->getMessage(),
                "mid" => null,
            ], 200);
        }
    }


    public function createAgent($validated)
    {
        try {
            $country = Country::query()->where('short_name', $validated['country'])->first();
            $currency = $this->getCurrency($country->short_name);
            $default_currency_id = DB::table('settings')->where('name', 'default_currency')->first()->value ?? 24;
            $temp_password = Str::random(8);


            if (!$currency instanceof Currency) {
                $currency = (object)[
                    "code" => "USD",
                    "id" => $default_currency_id
                ];
            }

            $user = User::query()->create([
                'type' => 'agent',
                'merchant_id' => user()->merchant_id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'],
                'formattedPhone' => "+" . $country->phone_code . $validated['phone'],
                'defaultCountry' => strtolower($country->short_name),
                'carrierCode' => $country->phone_code,
                'email' => $validated['email'],
                'password' => Hash::make($temp_password),
                'status' => 'Inactive',
                'reg_com' => false,
                'account_number' => 'irt' . rand(10000000000, 99999999999),
                'address_verified' => true,
                'identity_verified' => true,
            ]);


            $user->userDetail()->create([
                'country_id' => $country->id,
                'city' => $validated['city'],
                'state' => $validated['state'],
                'address_1' => $validated['address'],
                'default_currency' => $currency->id,
                'timezone' => DB::table('settings')->where('name', 'default_timezone')->first()->value ?? 'Africa/Accra',
                'gender' => $validated['gender']
            ]);

            $wallets = [
                [
                    "currency_id" => $default_currency_id,
                    "balance" => 0.00,
                    "limit_amount" => 0.00,
                    "is_default" => 'No'
                ],
            ];

            if ($currency instanceof Currency) {
                $wallets[] = [
                    "currency_id" => $currency->id,
                    "balance" => 0.00,
                    "limit_amount" => 0.00,
                    "is_default" => 'Yes'
                ];
            }

            $user->wallets()->createMany($wallets);

//        $user->assignRole('agent');
//        $user->givePermissionTo(Permission::agentDefaultPermissions());

            $hash = Hash::make((string)($user->id . ":" . $user->email));

            $signature = base64_encode("$hash:$user->id:$user->email");
            $url = url("/setup/complete/$signature");

            Mail::to($user)->send(new AgentInvitedMail($user, user()->merchant, $url));

            return [
                'error' => false,
                'error_message' => 'Agent added successfully, kindly inform agent to click the link sent to his email to accept invitation'
            ];

        } catch (\Exception $e) {
            Log::error('Exception Error for Create Merchant', format_exception($e));

            return [
                'error' => true,
                'error_message' => $e->getMessage() . '. Kindly try again'
            ];
        }
    }

    public function completeSetupView(Request $request, $signature)
    {
        $valid = $this->validateSignature($signature);

        if ($valid instanceof Redirector || $valid instanceof RedirectResponse) {
            return $valid;
        }

        return view('auth.complete_setup', ["user" => $valid]);
    }

    public function completeSetup(Request $request, $signature)
    {
        $valid = $this->validateSignature($signature);

        if ($valid instanceof Redirector || $valid instanceof RedirectResponse) {
            return $valid;
        }

        $validated = $this->validate($request, Rules::completeSetupRules());

        $valid->password = Hash::make($validated['password']);
        $valid->pin = $validated['pin'];
        $valid->reg_com = true;
        $valid->status = 'Active';
        $valid->save();

        $valid->userDetail()->update(['email_verification' => true]);

        Auth::login($valid);

        session()->put('twoFA', [
            "token" => "12345",
            "validated" => true
        ]);

        return redirect('/app');
    }


    private function getCurrency($country)
    {
        return Currency::query()->where('name', 'like', "%$country%")->first();

    }

    private function validateSignature($signature)
    {
        $decoded = base64_decode($signature, true);
        $split = explode(':', $decoded);

        if (count($split) != 3 || !Hash::check((string)($split[1] . ":" . $split[2]), $split[0])) {
            return redirect('login')->with([
                "error" => true,
                "error_message" => "Invalid Request. Kindly click on the original link sent to your email",
            ]);
        }

        $user = User::query()->with('merchant')->find((int)$split[1]);

        if (!$user instanceof User) {
            return redirect('login')->with([
                "error" => true,
                "error_message" => "Seems you clicked an invalid link. Contact support@imorapidtransfer.com for more information",
            ]);
        }

        if ($user->reg_com) {
            return redirect('login');
        }

        return $user;
    }

}
