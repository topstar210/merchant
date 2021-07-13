<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAgent(User $user, User $model)
    {
        return $user->merchant_id === $model->merchant_id && $user->isMerchant()
            ? Response::allow()
            : Response::deny('You do not own this agent.');
    }

}
