<?php

namespace App\Policies;

use App\CablePlanList;
use App\Enums\AccountAccessibility;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CablePlanListPolicy
{
    use HandlesAuthorization;



    /**
     * Determine whether the user can create cable plan lists.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can update the cable plan list.
     *
     * @param  \App\User  $user
     * @param  \App\CablePlanList  $cablePlanList
     * @return mixed
     */
    public function update(User $user, CablePlanList $cablePlanList)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can delete the cable plan list.
     *
     * @param  \App\User  $user
     * @param  \App\CablePlanList  $cablePlanList
     * @return mixed
     */
    public function delete(User $user, CablePlanList $cablePlanList)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }
}
