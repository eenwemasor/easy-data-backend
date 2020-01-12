<?php

namespace App\Http\Controllers;

use App\AdminChannelUtil;
use App\ReferralReward;
use App\ReferralStack;
use App\User;
use Illuminate\Http\Request;


class ReferralRewardController extends Controller
{
    function save_referral(Request $request)
    {
        $referrer = $request->referrer;
        $referee = $request->referee;

        $referrer_user_exists = User::where('unique_id', $referrer)->first();

        if (!$referrer_user_exists) {
            return response()->json(['message' => 'Referral link does not exist']);
        } else {
            $ref_stack = new ReferralStack;
            $ref_stack->referrer = $referrer;
            $ref_stack->referee = $referee;
            $ref_stack->status = false;
            $ref_stack->save();
        }
    }

    function index(Request $request)
    {
        $referee = $request->referee;
        $stack_exists = ReferralStack::where('referee', $referee)->first();

        if (!$stack_exists || $stack_exists->status == true) {
            return response()->json(['message' => 'Referral link does not exist']);
        } else {
            $has_referrer = ReferralStack::where('referee', $stack_exists->referrer)->first();

            if (!$has_referrer) {
                self::save_referral_reward($stack_exists->referrer, $referee);
            } else {
                self::save_referral_with_indirect_ref($stack_exists->referrer, $referee, $has_referrer->referrer);
            }
        }
    }


    function save_referral_reward($referrer, $referee)
    {
        $referrer_user = User::where('unique_id', $referrer)->first();
        $referee = ReferralStack::where('referee', $referee)->first();
        $referee->status = true;
        $referee->save();

        $ref_rewards = ReferralReward::find(1);
        $referrer_user->bonus_wallet = (int)$referrer_user->bonus_wallet + (int)$ref_rewards->widget;
        $referrer_user->save();
    }

    function save_referral_with_indirect_ref($referrer, $referee, $indirect_referrer)
    {
        $ref_stack = new ReferralStack;
        $ref_stack->referrer = $referrer;
        $ref_stack->referee = $referee;
        $ref_stack->save();

        $referrer_user = User::where('unique_id', $referrer)->first();
        $ref_rewards = ReferralReward::find(1);
        $referrer_user->bonus_wallet = (int)$referrer_user->bonus_wallet + (int)$ref_rewards->widget;
        $referrer_user->save();

        $indirect_referrer_user_exists = User::where('unique_id', $indirect_referrer)->first();

        if (!$indirect_referrer_user_exists) {
            return response()->json(['message' => 'Done']);
        } else {
            $referee = ReferralStack::where('referee', $referee)->first();
            $referee->status = true;
            $referee->save();

            $indirect_referrer_user_exists->bonus_wallet = (int)$ref_rewards->indirect_referrer_percentage / 100 * (int)$ref_rewards->widget;
            $indirect_referrer_user_exists->save();
        }

    }

    function activateAccount(Request $request)
    {
        $referee = $request->referee;
        $stack_exists = ReferralStack::where('referee', $referee)->first();
        $act_amount = AdminChannelUtil::where('id', 1)->first()->account_activation_amount;

        if(!$stack_exists){
            return response()->json(['message' => 'No referrer']);
        }else{
            $referrer_user_exists = User::where('unique_id', $stack_exists->referrer)->first();

            if (!$referrer_user_exists) {
                return response()->json(['message' => 'No referrer']);
            } else {
                $referrer_user_exists->bonus_wallet = $act_amount/2;
                $referrer_user_exists->save();
            }
        }

    }
}
