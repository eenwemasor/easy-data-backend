<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:18
 */

namespace App\Repositories;


use App\Contracts\TransferFundTransactionContract;
use App\Enums\TransactionStatus;
use App\TransferFundTransaction;

class TransferFundTransactionRepository implements TransferFundTransactionContract
{

    /**
     * @param array $transferFundTransaction
     * @return TransferFundTransaction
     */
    public function create(array $transferFundTransaction): TransferFundTransaction
    {
        return TransferFundTransaction::create($transferFundTransaction);
    }

    /**
     * @param string $transaction_id
     * @return TransferFundTransaction
     */
    public function approve_transaction(string $transaction_id): TransferFundTransaction
    {
        $transaction = TransferFundTransaction::findOrFail($transaction_id);
        $transaction->status = TransactionStatus::COMPLETED;
        $transaction->save();
        return $transaction;
    }

    /**
     * @param string $transaction_id
     * @return TransferFundTransaction
     */
    public function decline_transaction(string $transaction_id): TransferFundTransaction
    {
        $transaction = TransferFundTransaction::findOrFail($transaction_id);
        $transaction->status = TransactionStatus::FAILED;
        $transaction->save();
        return $transaction;
    }
}