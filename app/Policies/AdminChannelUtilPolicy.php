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
     * Determine whether the user can view any admin channel utils.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the admin channel util.
     *
     * @param  \App\User  $user
     * @param  \App\AdminChannelUtil  $adminChannelUtil
     * @return mixed
     */
    public function view(User $user, AdminChannelUtil $adminChannelUtil)
    {
        //
    }

    /**
     * Determine whether the user can create admin channel utils.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

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
