<?php


namespace App\Contracts;


use App\User;
use App\WalletTransaction;

interface WalletTransactionContract
{
    /**
     * @param array $WalletTransaction
     * @return WalletTransaction
     */
    public function create(array $WalletTransaction):WalletTransaction;
}