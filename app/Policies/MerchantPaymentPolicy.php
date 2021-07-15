<?php

namespace App\Policies;

use App\Models\MerchantPayment;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MerchantPaymentPolicy
{
    use HandlesAuthorization;

    public Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function processTransaction(User $user, MerchantPayment $merchantPayment)
    {
        Log::info($user->id);
        Log::info();
        if ($user->merchant_id === $merchantPayment->user->merchant_id && $user->isMerchant()) {
            return Response::allow();
        } elseif ($user->id !== (int)$merchantPayment->user_id) {
            return Response::deny('You are not allowed to complete this transaction process');
        } elseif (Carbon::now()->diffInMinutes(Carbon::parse($merchantPayment->created_at)) > 10 && !$this->request->has('fingerprint')) {
            return Response::deny('Transaction already processed');
        }
        return Response::allow();
    }

    public function owner(User $user, MerchantPayment $merchantPayment)
    {
        return $user->id === $merchantPayment->user_id || ($user->merchant_id === $merchantPayment->user->merchant_id && $user->isMerchant())
            ? Response::allow()
            : Response::deny('You are not authorized to view this transaction.');
    }

}
