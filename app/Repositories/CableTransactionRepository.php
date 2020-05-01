<?php


namespace App\Repositories;


use App\CablePlanList;
use App\CableTransaction;
use App\Contracts\CableTransactionContract;
use App\Enums\TransactionStatus;
use App\Http\Controllers\SendSMSController;
use App\Http\Controllers\Wallet;
use App\User;

class CableTransactionRepository implements CableTransactionContract
{

    /**
     * @param array $cableTransaction
     * @param User $user
     * @return CableTransaction
     */
    public function create(array $cableTransaction): CableTransaction
    {
        $cable_plan = CablePlanList::find($cableTransaction['plan']);

        $cableTransaction['plan'] = $cable_plan->plan;
        return CableTransaction::create($cableTransaction);
    }

    /**
     * @param string $transaction_id
     * @return CableTransaction
     */
    public function mark_transaction_successful(string $transaction_id): CableTransaction
    {

        $transaction = CableTransaction::findOrFail($transaction_id);
        if($transaction->status === TransactionStatus::COMPLETED){
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