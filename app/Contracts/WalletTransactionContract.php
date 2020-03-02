<?php


namespace App\Contracts;


use App\User;
use App\WalletTransaction;

interface WalletTransactionContract
{
    /**
     * @param array $WalletTransaction
     * @param User $user
     * @return WalletTransaction
     */
    public function create(array $WalletTransaction,User $user):WalletTransaction;
}