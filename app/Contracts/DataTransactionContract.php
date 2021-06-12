<?php


namespace App\Contracts;


use App\DataTransaction;
use App\User;

interface DataTransactionContract
{
    /**
     * @param array $DataTransaction
     * @return DataTransaction
     */
    public function create(array $DataTransaction):DataTransaction;

    /**
     * @param string $transaction_id
     * @return DataTransaction
     */
    public function mark_transaction_successful(string $transaction_id):DataTransaction;

    /**
     * @param string $transaction_id
     * @return DataTransaction
     */
    public function mark_transaction_failed(string $transaction_id):DataTransaction;
}