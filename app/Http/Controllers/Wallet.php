<?php

namespace App\Http\Controllers;

use App\User;

class Wallet extends Controller
{

    /**
     * @param $user
     * @param $amount
     */
    public function deductFromWallet(User $user, $amount)
    {
        $user_wallet = $user->wallet;
        if ($user_wallet < $amount) {
            abort(400, 'Insufficient Wallet balance.');
        } else {
            $new_balance = $user_wallet - $amount;
            $user->wallet = $new_balance;
            $user->save();
        }
    }

    /**
     * @param $user
     * @param $amount
     */
    public function deductFromBonusWallet(User $user, $amount)
    {
        $user_wallet = $user->bonus_wallet;
        if ($user_wallet < $amount) {
            abort(400, 'Insufficient Wallet balance.');
        } else {
            $new_balance = $user_wallet - $amount;
            $user->bonus_wallet = $new_balance;
            $user->save();
        }
    }

    /**
     * @param $user
     * @param $amount
     */
    public function fundWallet(User $user, $amount)
    {
        $user_wallet = $user->wallet;

        $new_balance = $user_wallet + $amount;
        $user->wallet = $new_balance;
        $user->save();
    }


}
