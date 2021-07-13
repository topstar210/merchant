<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PermissionPolicy
{
    use HandlesAuthorization;

    public function isMerchant(User $user)
    {
        return $user->isMerchant();
    }

    public function viewDashboard(User $user)
    {
        return true;
    }

    public function viewTransactions(User $user)
    {
        return true;
    }

    public function shouldSend(User $user)
    {
        return is_null($user->suspend_payout_date) ? Response::allow()
            : Response::deny('You are not allowed to send');
    }


}
