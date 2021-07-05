<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class WalletPolicy
{
    use HandlesAuthorization;

    public function owner(User $user, Wallet $wallet)
    {
        return $user->id === $wallet->user_id
            ? Response::allow()
            : Response::deny('You do not own this wallet.');
    }

    public function notLocked(User $user, Wallet $wallet)
    {
        return !$wallet->lock
            ? Response::allow()
            : Response::deny('You can\'t transact with a locked wallet.');
    }

    public function viewWallet(User $user, Wallet $wallet)
    {
        return $user->id === $wallet->user_id || $user->merchant_id === $wallet->user->merchant_id
            ? Response::allow()
            : Response::deny('You do not own this wallet . ');
    }

    public function walletDeposit(User $user, Wallet $wallet)
    {
        return ($user->id === $wallet->user_id || $user->merchant_id === $wallet->user->merchant_id) && !$wallet->lock
            ? Response::allow()
            : Response::deny('You do not own this wallet or wallet is locked . ');
    }

    public function walletLimit(User $user, Wallet $wallet)
    {
        return $wallet->limit_amount > $wallet->loadSum('today_out_sum', 'amount')->today_out_sum_sum_amount
            ? Response::allow()
            : Response::deny('You have exceeded your send daily limit.');
    }

}
