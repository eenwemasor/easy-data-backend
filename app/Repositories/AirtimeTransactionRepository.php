<?php


namespace App\Repositories;


use App\AirtimeTransaction;
use App\Contracts\AirtimeTransactionContract;
use App\Http\Controllers\Wallet;
use App\User;

class AirtimeTransactionRepository implements AirtimeTransactionContract
{

    /**
     * @param array $airtimeTransaction
     * @param User $user
     * @return AirtimeTransaction
     */
    public function create(array $airtimeTransaction): AirtimeTransaction
    {

        // TODO: Implement create() method.
        return AirtimeTransaction::create($airtimeTransaction);
    }

}