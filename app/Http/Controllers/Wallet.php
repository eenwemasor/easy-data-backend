<?php

namespace App\Http\Controllers;

use App\AdminChannelUtil;
use App\Enums\NetworkType;
use App\Enums\WalletTransactionStatus;
use App\Enums\WalletType;
use App\GraphQL\Errors\GraphqlError;
use App\User;

class Wallet extends Controller
{

    /**
     * @param User $user
     * @param $amount
     * @param $wallet
     * @return array
     */
    public function deduct_from_wallet(User $user, $amount, $wallet)
    {
        $user_wallet = $user->wallet;
        $new_balance = $user_wallet - $amount;
        $user->wallet = $new_balance;
        $user->save();

        return [
            'reference'=>uniqid(),
            'initial_balance' => $user_wallet,
            'new_balance' =>$new_balance,
            'wallet' =>$wallet,
            'status' =>WalletTransactionStatus::SUCCESSFUL
        ];
    }

    /**
     * @param User $user
     * @param $amount
     * @param $wallet
     * @return array
     */
    public function deduct_from_bonus_wallet(User $user, $amount, $wallet)
    {
        $user__bonus_wallet = $user->bonus_wallet;
        $new_balance = $user__bonus_wallet - $amount;
        $user->bonus_wallet = $new_balance;
        $user->save();

        return [
            'reference'=>uniqid(),
            'initial_balance' => $user__bonus_wallet,
            'new_balance' =>$new_balance,
            'wallet' =>$wallet,
            'status' =>WalletTransactionStatus::SUCCESSFUL
        ];
    }

    /**
     * @param User $user
     * @param $amount
     * @param null $from
     * @param null $reference
     * @return array
     */
    public function fund_wallet(User $user, $amount, $from = null, $reference = null)
    {

        if(isset($from)){
            $user_bonus_wallet = $user->bonus_wallet;
            $new_balance = $user_bonus_wallet + $amount;
            $user->bonus_wallet = $new_balance;
            $user->save();

            return [
                'reference'=>isset($reference) ?: uniqid(),
                'initial_balance' => $user_bonus_wallet,
                'new_balance' =>$new_balance,
                'wallet' =>WalletType::WALLET,
                'status' =>WalletTransactionStatus::SUCCESSFUL
            ];
        }else{
            $user_wallet = $user->wallet;
            $new_balance = $user_wallet + $amount;
            $user->wallet = $new_balance;
            $user->save();

            return [
                'reference'=>uniqid(),
                'initial_balance' => $user_wallet,
                'new_balance' =>$new_balance,
                'wallet' =>WalletType::WALLET,
                'status' =>WalletTransactionStatus::SUCCESSFUL
            ];
        }
    }


//    public function apply_discount( float $amount, string $network)
//    {
//        $discounts = AdminChannelUtil::first();
//        switch ($network){
//            case NetworkType::MTN:{
//                return $amount - $discounts->mtn_discount/100*$amount;
//            }
//            case NetworkType::NINE_MOBILE:{
//                return $amount - $discounts->etisalat_discount/100*$amount;
//            }
//            case NetworkType::AIRTEL:{
//                return $amount - $discounts->airtel_discount/100*$amount;
//            }
//            case NetworkType::GLO:{
//                return $amount - $discounts->glo_discount/100*$amount;
//            }
//            default:{
//                return $amount;
//            }
//        }
//
//    }


}
