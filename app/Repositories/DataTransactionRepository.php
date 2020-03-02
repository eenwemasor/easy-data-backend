<?php


namespace App\Repositories;


use App\Contracts\DataTransactionContract;
use App\DataTransaction;
use App\Http\Controllers\Wallet;
use App\User;

class DataTransactionRepository implements DataTransactionContract
{

    /**
     * @param array $dataTransaction
     * @param User $user
     * @return DataTransaction
     */
    public function create(array $dataTransaction): DataTransaction
    {
        // TODO: Implement create() method.

        return DataTransaction::create($dataTransaction);
    }
}