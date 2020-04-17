<?php


namespace App\Contracts;


use App\ElectricityTransaction;

interface ElectricityTransactionContract
{

    /**
     * @param array $ElectricityTransaction
     * @return ElectricityTransaction
     */
    public function create(array $ElectricityTransaction):ElectricityTransaction;

    /**
     * @param string $transaction_id
     * @return ElectricityTransaction
     */
    public function mark_transaction_successful(string $transaction_id):ElectricityTransaction;

    /**
     * @param string $transaction_id
     * @return ElectricityTransaction
     */
    public function mark_transaction_failed(string $transaction_id):ElectricityTransaction;



}