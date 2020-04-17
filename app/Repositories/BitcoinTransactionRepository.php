<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:17
 */

namespace App\Repositories;


use App\BitcoinTransaction;
use App\Contracts\BitcoinTransactionContract;
use App\Enums\TransactionStatus;

class BitcoinTransactionRepository implements BitcoinTransactionContract
{
    /**
     * @param array $bitcoinTransaction
     * @return BitcoinTransaction
     */
    public function create(array $bitcoinTransaction): BitcoinTransaction
    {
        return  BitcoinTransaction::create($bitcoinTransaction);
    }


    /**
     * @param string $transaction_id
     * @return BitcoinTransaction
     */
    public function mark_transaction_successful(string $transaction_id): BitcoinTransaction
    {
        $transaction = BitcoinTransaction::findOrFail($transaction_id);
        $transaction->status = TransactionStatus::COMPLETED;
        $transaction->save();
        return $transaction;

    }

    /**
     * @param string $transaction_id
     * @return BitcoinTransaction
     */
    public function mark_transaction_failed(string $transaction_id): BitcoinTransaction
    {
        $transaction = BitcoinTransaction::findOrFail($transaction_id);
        $transaction->status = TransactionStatus::FAILED;
        $transaction->save();
        return $transaction;
    }
}