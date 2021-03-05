<?php

namespace App\Policies;

use App\Enums\AccountAccessibility;
use App\SpectranetPriceList;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpectranetPriceListPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can create spectranet price lists.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can update the spectranet price list.
     *
     * @param  \App\User  $user
     * @param  \App\SpectranetPriceList  $spectranetPriceList
     * @return mixed
     */
    public function update(User $user, SpectranetPriceList $spectranetPriceList)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can delete the spectranet price list.
     *
     * @param  \App\User  $user
     * @param  \App\SpectranetPriceList  $spectranetPriceList
     * @return mixed
     */
    public function delete(User $user, SpectranetPriceList $spectranetPriceList)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

}
