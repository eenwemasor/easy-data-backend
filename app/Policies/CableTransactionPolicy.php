<?php

namespace App\Policies;

use App\CableTransaction;
use App\Enums\AccountAccessibility;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CableTransactionPolicy
{
    use HandlesAuthorization;



    /**
     * Determine whether the user can update the cable transaction.
     *
     * @param  \App\User  $user
     * @param  \App\CableTransaction  $cableTransaction
     * @return mixed
     */
    public function update(User $user, CableTransaction $cableTransaction)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can delete the cable transaction.
     *
     * @param  \App\User  $user
     * @param  \App\CableTransaction  $cableTransaction
     * @return mixed
     */
    public function delete(User $user, CableTransaction $cableTransaction)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

}
