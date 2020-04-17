<?php


namespace App\Contracts;


use App\CableTransaction;

interface CableTransactionContract
{
    /**
     * @param array $CableTransaction
     * @return CableTransaction
     */
    public function create(array $CableTransaction):CableTransaction;

    /**
     * @param string $transaction_id
     * @return CableTransaction
     */
    public function mark_transaction_successful(string $transaction_id):CableTransaction;

    /**
     * @param string $transaction_id
     * @return CableTransaction
     */
    public function mark_transaction_failed(string $transaction_id):CableTransaction;
}