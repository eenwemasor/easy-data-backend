<?php

namespace App\Policies;

use App\DataPlanList;
use App\Enums\AccountAccessibility;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DataPlanListPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can create data plan lists.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can update the data plan list.
     *
     * @param  \App\User  $user
     * @param  \App\DataPlanList  $dataPlanList
     * @return mixed
     */
    public function update(User $user, DataPlanList $dataPlanList)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can delete the data plan list.
     *
     * @param  \App\User  $user
     * @param  \App\DataPlanList  $dataPlanList
     * @return mixed
     */
    public function delete(User $user, DataPlanList $dataPlanList)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }


}
