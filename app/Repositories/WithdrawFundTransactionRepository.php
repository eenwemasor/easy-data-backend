<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:19
 */

namespace App\Repositories;


use App\Contracts\WithdrawFundTransactionContract;
use App\Enums\TransactionStatus;
use App\WithdrawFundTransaction;

class WithdrawFundTransactionRepository implements WithdrawFundTransactionContract
{

    /**
     * @param array $withdrawFundTransaction
     * @return WithdrawFundTransaction
     */
    public function create(array $withdrawFundTransaction): WithdrawFundTransaction
    {
        return WithdrawFundTransaction::create($withdrawFundTransaction);
    }

    /**
     * @param string $transaction_id
     * @return WithdrawFundTransaction
     */
    public function approve_transaction(string $transaction_id): WithdrawFundTransaction
    {
        $transaction = WithdrawFundTransaction::findOrFail($transaction_id);
        $transaction->status = TransactionStatus::COMPLETED;
        $transaction->save();
        return $transaction;
    }
    /**
     * @param string $transaction_id
     * @return WithdrawFundTransaction
     */
    public function decline_transaction(string $transaction_id): WithdrawFundTransaction
    {
        $transaction = WithdrawFundTransaction::findOrFail($transaction_id);
        $transaction->status = TransactionStatus::FAILED;
        $transaction->save();
        return $transaction;
    }
}