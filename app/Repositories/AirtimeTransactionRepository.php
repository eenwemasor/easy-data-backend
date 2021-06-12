<?php


namespace App\Repositories;


use App\AirtimeTransaction;
use App\Contracts\AirtimeTransactionContract;
use App\Enums\TransactionStatus;

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
        $transaction = AirtimeTransaction::findOrFail($transaction_id);

        if($transaction->status === TransactionStatus::COMPLETED){
            return $transaction;
        }

        $transaction->status = TransactionStatus::COMPLETED;
        $transaction->save();
        return $transaction;
    }

    /**
     * @param string $transaction_id
     * @return AirtimeTransaction
     */
    public function mark_transaction_failed(string $transaction_id): AirtimeTransaction
    {
        $transaction = AirtimeTransaction::findOrFail($transaction_id);

        if($transaction->status === TransactionStatus::FAILED){
            return $transaction;
        }


        $transaction->status = TransactionStatus::FAILED;
        $transaction->save();
        return $transaction;
    }
}
