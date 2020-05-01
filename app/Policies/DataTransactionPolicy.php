<?php

namespace App\Policies;

use App\DataTransaction;
use App\Enums\AccountAccessibility;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DataTransactionPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can update the data transaction.
     *
     * @param  \App\User  $user
     * @param  \App\DataTransaction  $dataTransaction
     * @return mixed
     */
    public function update(User $user, DataTransaction $dataTransaction)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can delete the data transaction.
     *
     * @param  \App\User  $user
     * @param  \App\DataTransaction  $dataTransaction
     * @return mixed
     */
    public function delete(User $user, DataTransaction $dataTransaction)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }


}
