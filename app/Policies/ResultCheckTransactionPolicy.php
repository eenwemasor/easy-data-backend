<?php

namespace App\Policies;

use App\Enums\AccountAccessibility;
use App\ResultCheckTransaction;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResultCheckTransactionPolicy
{
    use HandlesAuthorization;



    /**
     * Determine whether the user can delete the result check transaction.
     *
     * @param  \App\User  $user
     * @param  \App\ResultCheckTransaction  $resultCheckTransaction
     * @return mixed
     */
    public function delete(User $user, ResultCheckTransaction $resultCheckTransaction)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }


}
