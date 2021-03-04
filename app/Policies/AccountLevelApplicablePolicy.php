<?php

namespace App\Policies;

use App\AccountLevelApplicable;
use App\Enums\AccountAccessibility;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountLevelApplicablePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create account level applicables.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can update the account level applicable.
     *
     * @param  \App\User  $user
     * @param  \App\AccountLevelApplicable  $accountLevelApplicable
     * @return mixed
     */
    public function update(User $user, AccountLevelApplicable $accountLevelApplicable)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can delete the account level applicable.
     *
     * @param  \App\User  $user
     * @param  \App\AccountLevelApplicable  $accountLevelApplicable
     * @return mixed
     */
    public function delete(User $user, AccountLevelApplicable $accountLevelApplicable)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

}
