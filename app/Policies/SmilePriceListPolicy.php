<?php

namespace App\Policies;

use App\Enums\AccountAccessibility;
use App\SmilePriceList;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SmilePriceListPolicy
{
    use HandlesAuthorization;



    /**
     * Determine whether the user can create smile price lists.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can update the smile price list.
     *
     * @param  \App\User  $user
     * @param  \App\SmilePriceList  $smilePriceList
     * @return mixed
     */
    public function update(User $user, SmilePriceList $smilePriceList)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can delete the smile price list.
     *
     * @param  \App\User  $user
     * @param  \App\SmilePriceList  $smilePriceList
     * @return mixed
     */
    public function delete(User $user, SmilePriceList $smilePriceList)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

}
