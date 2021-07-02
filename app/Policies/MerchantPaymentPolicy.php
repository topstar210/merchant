<?php

namespace App\Policies;

use App\Models\MerchantPayment;
use App\Models\User;
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
        if ($user->id !== $merchantPayment->user_id) {
            return Response::deny('You are not allowed to complete this transaction process');
        } elseif ($merchantPayment->status !== 'Pending' && !$this->request->has('fingerprint')) {
            return Response::deny('Transaction already processed or do not exist');
        }
        return Response::allow();
    }

}
