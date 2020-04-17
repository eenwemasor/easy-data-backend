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
     * @return AirtimeTransaction
     */
    public function create(array $airtimeTransaction): AirtimeTransaction
    {
        return AirtimeTransaction::create($airtimeTransaction);
    }

    /**
     * @param string $transaction_id
     * @return AirtimeTransaction
     */
    public function mark_transaction_successful(string $transaction_id): AirtimeTransaction
    {
    }

    /**
     * @param string $transaction_id
     * @return AirtimeTransaction
     */
    public function mark_transaction_failed(string $transaction_id): AirtimeTransaction
    {
    }
}