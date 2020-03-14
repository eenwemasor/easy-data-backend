<?php

namespace App\Http\Controllers;

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
    public function deductFromWallet(User $user, $amount, $wallet)
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
    public function deductFromBonusWallet(User $user, $amount, $wallet)
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
     * @param $user
     * @param $amount
     * @return array
     */
    public function fundWallet(User $user, $amount)
    {
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
