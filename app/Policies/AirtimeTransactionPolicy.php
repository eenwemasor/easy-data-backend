<?php

namespace App\Policies;

use App\AirtimeTransaction;
use App\Enums\AccountAccessibility;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AirtimeTransactionPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can update the airtime transaction.
     *
     * @param  \App\User  $user
     * @param  \App\AirtimeTransaction  $airtimeTransaction
     * @return mixed
     */
    public function update(User $user, AirtimeTransaction $airtimeTransaction)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can delete the airtime transaction.
     *
     * @param  \App\User  $user
     * @param  \App\AirtimeTransaction  $airtimeTransaction
     * @return mixed
     */
    public function delete(User $user, AirtimeTransaction $airtimeTransaction)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

}
