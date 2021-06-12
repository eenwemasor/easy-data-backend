<?php

namespace App\Policies;

use App\Enums\AccountAccessibility;
use App\ReferralReward;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReferralRewardPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can update the referral reward.
     *
     * @param  \App\User  $user
     * @param  \App\ReferralReward  $referralReward
     * @return mixed
     */
    public function update(User $user, ReferralReward $referralReward)
    {
        return $user->accessibility === AccountAccessibility::ADMIN;
    }

    /**
     * Determine whether the user can delete the referral reward.
     *
     * @param  \App\User  $user
     * @param  \App\ReferralReward  $referralReward
     * @return mixed
     */
    public function delete(User $user, ReferralReward $referralReward)
    {
        return false;
    }


}
