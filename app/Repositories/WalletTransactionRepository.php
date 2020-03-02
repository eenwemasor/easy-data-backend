<?php


namespace App\Repositories;


use App\Contracts\WalletTransactionContract;
use App\Http\Controllers\Wallet;
use App\User;
use App\WalletTransaction;

class WalletTransactionRepository implements WalletTransactionContract
{

    /**
     * @param array $walletTransaction
     * @param User $user
     * @return WalletTransaction
     */
    public function create(array $walletTransaction,User $user): WalletTransaction
    {
        // TODO: Implement create() method.
        $wallet = $walletTransaction["wallet"];
        $transaction_type = $walletTransaction["transaction_type"];
        $amount = $walletTransaction["amount"];

        $wallet_controller = new Wallet();

        if($transaction_type == "CREDIT"){
            $wallet_controller->fundWallet($user, $amount);
        }else if($transaction_type == "DEBIT"){
            if($wallet == "WALLET"){
                $wallet_controller->deductFromWallet($user, $amount);
            }else if($wallet == "BONUS_WALLET"){
                $wallet_controller->deductFromBonusWallet($user, $amount);
            }
        }

        return WalletTransaction::create($walletTransaction);
    }
}