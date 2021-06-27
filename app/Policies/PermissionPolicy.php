<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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




}
