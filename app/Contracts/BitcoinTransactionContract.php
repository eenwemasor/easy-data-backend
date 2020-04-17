<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:00
 */

namespace App\Contracts;


use App\BitcoinTransaction;

interface BitcoinTransactionContract
{
    /**
     * @param array $bitcoinTransaction
     * @return BitcoinTransaction
     */
    public function create(array $bitcoinTransaction): BitcoinTransaction;

    /**
     * @param string $transaction_id
     * @return BitcoinTransaction
     */
    public function mark_transaction_successful(string $transaction_id): BitcoinTransaction;

    /**
     * @param string $transaction_id
     * @return BitcoinTransaction
     */
    public function mark_transaction_failed(string $transaction_id): BitcoinTransaction;
}