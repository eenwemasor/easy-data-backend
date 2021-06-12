<?php

namespace App\Policies;

use App\Enums\AccountAccessibility;
use App\PowerPlanList;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PowerPlanListPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can create power plan lists.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can update the power plan list.
     *
     * @param  \App\User  $user
     * @param  \App\PowerPlanList  $powerPlanList
     * @return mixed
     */
    public function update(User $user, PowerPlanList $powerPlanList)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can delete the power plan list.
     *
     * @param  \App\User  $user
     * @param  \App\PowerPlanList  $powerPlanList
     * @return mixed
     */
    public function delete(User $user, PowerPlanList $powerPlanList)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }


}
