<?php


namespace App\Repositories;


use App\WithdrawalTransaction;

class WithdrawalTransactionRepository
{

    /**
     * @param array $withdrawalTransaction
     * @return WithdrawalTransaction
     */
    public function create(array $withdrawalTransaction): WithdrawalTransaction
    {
        return  WithdrawalTransaction::create($withdrawalTransaction);
    }

}
