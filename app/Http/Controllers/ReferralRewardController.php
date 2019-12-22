<?php

namespace App\Http\Controllers;

use App\ReferralReward;
use App\ReferralStack;
use App\User;
use Illuminate\Http\Request;


class ReferralRewardController extends Controller
{
    function index(Request $request)
    {
        $referrer = $request->referrer;
        $referee = $request->referee;
        $referrer_user_exists = User::where('unique_id', $referrer)->first();

        if (!$referrer_user_exists) {
                return true;
        }else{
            $has_referrer = ReferralStack::where('referee', $referrer)->first();

            if (!$has_referrer) {
                self::saveReferral($referrer,$referee);
            }else{
                self::saveReferralWithIndirectRef($referrer,$referee,$has_referrer->referrer);
            }
        }

    }

    function saveReferral($referrer, $referee){
        $ref_stack = new ReferralStack;
        $ref_stack->referrer = $referrer;
        $ref_stack->referee = $referee;
        $ref_stack->save();

        $referrer_user = User::where('unique_id', $referrer)->first();
        $ref_rewards = ReferralReward::find(1);
        $referrer_user->wallet  = (int)$referrer_user->wallet + (int)$ref_rewards->widget;
        $referrer_user->save();
    }

    function saveReferralWithIndirectRef($referrer, $referee,$indirect_referrer){
        $ref_stack = new ReferralStack;
        $ref_stack->referrer = $referrer;
        $ref_stack->referee = $referee;
        $ref_stack->save();

        $referrer_user = User::where('unique_id', $referrer)->first();
        $ref_rewards = ReferralReward::find(1);
        $referrer_user->wallet  = (int)$referrer_user->wallet + (int)$ref_rewards->widget;
        $referrer_user->save();

        $indirect_referrer_user_exists = User::where('unique_id', $indirect_referrer)->first();

        if(!$indirect_referrer_user_exists){
            return true;
        }else{
            $indirect_referrer_user_exists->wallet  =  (int)$ref_rewards->indirect_referrer_percentage/100 * (int)$ref_rewards->widget;
            $indirect_referrer_user_exists->save();
        }

    }


}
