<?php

namespace App\Policies;

use App\Enums\AccountAccessibility;
use App\NewsUpdate;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsUpdatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create news updates.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can update the news update.
     *
     * @param  \App\User  $user
     * @param  \App\NewsUpdate  $newsUpdate
     * @return mixed
     */
    public function update(User $user, NewsUpdate $newsUpdate)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can delete the news update.
     *
     * @param  \App\User  $user
     * @param  \App\NewsUpdate  $newsUpdate
     * @return mixed
     */
    public function delete(User $user, NewsUpdate $newsUpdate)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }


}
