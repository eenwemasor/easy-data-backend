<?php


namespace App\Repositories;


use App\Contracts\DataTransactionContract;
use App\DataPlanList;
use App\DataTransaction;
use App\Enums\TransactionStatus;
use App\Http\Controllers\Wallet;
use App\User;

class DataTransactionRepository implements DataTransactionContract
{

    /**
     * @param array $dataTransaction
     * @return DataTransaction
     */
    public function create(array $dataTransaction): DataTransaction
    {
        return DataTransaction::create($dataTransaction);
    }

    /**
     * @param string $transaction_id
     * @return DataTransaction
     */
    public function mark_transaction_successful(string $transaction_id): DataTransaction
    {

        $transaction = DataTransaction::findOrFail($transaction_id);
        $transaction->status = TransactionStatus::COMPLETED;
        $transaction->save();
        return $transaction;
    }

    /**
     * @param string $transaction_id
     * @return DataTransaction
     */
    public function mark_transaction_failed(string $transaction_id): DataTransaction
    {
        $transaction = DataTransaction::findOrFail($transaction_id);
        $transaction->status = TransactionStatus::FAILED;
        $transaction->save();
        return $transaction;
    }
}