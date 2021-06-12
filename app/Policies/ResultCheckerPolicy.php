<?php

namespace App\Policies;

use App\Enums\AccountAccessibility;
use App\ResultChecker;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResultCheckerPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can create result checkers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can update the result checker.
     *
     * @param  \App\User  $user
     * @param  \App\ResultChecker  $resultChecker
     * @return mixed
     */
    public function update(User $user, ResultChecker $resultChecker)
    { return $user->accessibility === AccountAccessibility::ADMIN; //
    }

    /**
     * Determine whether the user can delete the result checker.
     *
     * @param  \App\User  $user
     * @param  \App\ResultChecker  $resultChecker
     * @return mixed
     */
    public function delete(User $user, ResultChecker $resultChecker)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }
}
