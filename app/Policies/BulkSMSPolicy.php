<?php

namespace App\Policies;

use App\BulkSMS;
use App\Enums\AccountAccessibility;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BulkSMSPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can update the bulk s m s.
     *
     * @param  \App\User  $user
     * @param  \App\BulkSMS  $bulkSMS
     * @return mixed
     */
    public function update(User $user, BulkSMS $bulkSMS)
    {
        return $user->accessibility = AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can delete the bulk s m s.
     *
     * @param  \App\User  $user
     * @param  \App\BulkSMS  $bulkSMS
     * @return mixed
     */
    public function delete(User $user, BulkSMS $bulkSMS)
    {
        return $user->accessibility = AccountAccessibility::ADMIN;
    }


}
