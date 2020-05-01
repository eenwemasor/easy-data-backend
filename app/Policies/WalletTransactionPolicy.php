<?php

namespace App\Policies;

use App\Enums\AccountAccessibility;
use App\User;
use App\WalletTransaction;
use Illuminate\Auth\Access\HandlesAuthorization;

class WalletTransactionPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can update the wallet transaction.
     *
     * @param  \App\User  $user
     * @param  \App\WalletTransaction  $walletTransaction
     * @return mixed
     */
    public function update(User $user, WalletTransaction $walletTransaction)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can delete the wallet transaction.
     *
     * @param  \App\User  $user
     * @param  \App\WalletTransaction  $walletTransaction
     * @return mixed
     */
    public function delete(User $user, WalletTransaction $walletTransaction)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

}
