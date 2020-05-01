<?php

namespace App\Policies;

use App\ElectricityTransaction;
use App\ElectrictyTransaction;
use App\Enums\AccountAccessibility;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ElectricityTransactionPolicy
{
    use HandlesAuthorization;



    /**
     * Determine whether the user can update the electricity transaction.
     *
     * @param  \App\User  $user
     * @param  \App\ElectricityTransaction  $electricityTransaction
     * @return mixed
     */
    public function update(User $user, ElectricityTransaction $electricityTransaction)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can delete the electricity transaction.
     *
     * @param  \App\User  $user
     * @param  \App\ElectricityTransaction  $electricityTransaction
     * @return mixed
     */
    public function delete(User $user, ElectricityTransaction $electricityTransaction)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }
}
