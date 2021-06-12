<?php

namespace App\Policies;

use App\AdminChannelUtil;
use App\Enums\AccountAccessibility;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminChannelUtilPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the admin channel util.
     *
     * @param  \App\User  $user
     * @param  \App\AdminChannelUtil  $adminChannelUtil
     * @return mixed
     */
    public function update(User $user, AdminChannelUtil $adminChannelUtil)
    {

        return $user->accessibility === AccountAccessibility::ADMIN;
    }


}
