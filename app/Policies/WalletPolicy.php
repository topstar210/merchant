<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class WalletPolicy
{
    use HandlesAuthorization;


    public function viewWallet(User $user, Wallet $wallet)
    {
        return $user->id === $wallet->user_id || $user->merchant_id === $wallet->user->merchant_id
            ? Response::allow()
            : Response::deny('You do not own this wallet.');
    }

    public function walletDeposit(User $user, Wallet $wallet)
    {
        return ($user->id === $wallet->user_id || $user->merchant_id === $wallet->user->merchant_id) && !$wallet->lock
            ? Response::allow()
            : Response::deny('You do not own this wallet or wallet is locked.');
    }

}
