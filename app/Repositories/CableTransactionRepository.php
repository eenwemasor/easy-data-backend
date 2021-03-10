<?php


namespace App\Repositories;


use App\CableTransaction;
use App\Contracts\CableTransactionContract;
use App\Enums\TransactionStatus;

class CableTransactionRepository implements CableTransactionContract
{

    /**
     * @param array $cableTransaction
     * @return CableTransaction
     */
    public function create(array $cableTransaction): CableTransaction
    {
        return CableTransaction::create($cableTransaction);
    }

    /**
     * @param string $transaction_id
     * @return CableTransaction
     */
    public function mark_transaction_successful(string $transaction_id): CableTransaction
    {
        $transaction = CableTransaction::findOrFail($transaction_id);
        if ($transaction->status === TransactionStatus::COMPLETED) {
            return $transaction;
        }
        $transaction->status = TransactionStatus::COMPLETED;
        $transaction->save();
        return $transaction;
    }

    /**
     * @param string $transaction_id
     * @return CableTransaction
     */
    public function mark_transaction_failed(string $transaction_id): CableTransaction
    {
        $transaction = CableTransaction::findOrFail($transaction_id);
        $transaction->status = TransactionStatus::FAILED;
        $transaction->save();
        return $transaction;
    }
}
