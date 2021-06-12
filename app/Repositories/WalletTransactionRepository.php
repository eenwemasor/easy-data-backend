<?php


namespace App\Repositories;


use App\Contracts\WalletTransactionContract;
use App\User;
use App\WalletTransaction;

class WalletTransactionRepository implements WalletTransactionContract
{

    /**
     * @param array $walletTransaction
     * @param User $user
     * @return WalletTransaction
     */
    public function create(array $walletTransaction): WalletTransaction
    {
        return WalletTransaction::create($walletTransaction);
    }
}
