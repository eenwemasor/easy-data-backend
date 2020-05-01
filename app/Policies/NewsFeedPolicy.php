<?php

namespace App\Policies;

use App\Enums\AccountAccessibility;
use App\NewsFeed;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsFeedPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create news feeds.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can update the news feed.
     *
     * @param  \App\User  $user
     * @param  \App\NewsFeed  $newsFeed
     * @return mixed
     */
    public function update(User $user, NewsFeed $newsFeed)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can delete the news feed.
     *
     * @param  \App\User  $user
     * @param  \App\NewsFeed  $newsFeed
     * @return mixed
     */
    public function delete(User $user, NewsFeed $newsFeed)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

}
