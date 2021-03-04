<?php

namespace App\Policies;

use App\AccountLevel;
use App\Enums\AccountAccessibility;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountLevelPolicy
{
    use HandlesAuthorization;



    /**
     * Determine whether the user can create account levels.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can update the account level.
     *
     * @param  \App\User  $user
     * @param  \App\AccountLevel  $accountLevel
     * @return mixed
     */
    public function update(User $user, AccountLevel $accountLevel)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can delete the account level.
     *
     * @param  \App\User  $user
     * @param  \App\AccountLevel  $accountLevel
     * @return mixed
     */
    public function delete(User $user, AccountLevel $accountLevel)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }
}
