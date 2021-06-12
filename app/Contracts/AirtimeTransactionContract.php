<?php


namespace App\Contracts;


use App\AirtimeTransaction;

interface AirtimeTransactionContract
{
    /**
     * @param array $airtimeTransaction
     * @return AirtimeTransaction
     */
    public function create(array $airtimeTransaction): AirtimeTransaction;

    /**
     * @param string $transaction_id
     * @return AirtimeTransaction
     */
    public function mark_transaction_successful(string $transaction_id): AirtimeTransaction;

    /**
     * @param string $transaction_id
     * @return AirtimeTransaction
     */
    public function mark_transaction_failed(string $transaction_id): AirtimeTransaction;
}