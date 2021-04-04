<?php

namespace App\Policies;

use App\Enums\AccountAccessibility;
use App\User;
use App\WithdrawalTransaction;
use Illuminate\Auth\Access\HandlesAuthorization;

class WithdrawalTransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the withdrawal transaction.
     *
     * @param  \App\User  $user
     * @param  \App\WithdrawalTransaction  $withdrawalTransaction
     * @return mixed
     */
    public function delete(User $user, WithdrawalTransaction $withdrawalTransaction)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

}
