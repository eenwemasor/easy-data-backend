<?php

namespace App\Policies;

use App\BankAccount;
use App\Enums\AccountAccessibility;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BankAccountPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create bank accounts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can update the bank account.
     *
     * @param  \App\User  $user
     * @param  \App\BankAccount  $bankAccount
     * @return mixed
     */
    public function update(User $user, BankAccount $bankAccount)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can delete the bank account.
     *
     * @param  \App\User  $user
     * @param  \App\BankAccount  $bankAccount
     * @return mixed
     */
    public function delete(User $user, BankAccount $bankAccount)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }
}
