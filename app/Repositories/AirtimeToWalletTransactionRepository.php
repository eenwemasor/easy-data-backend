<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:16
 */

namespace App\Repositories;


use App\AirtimeToWalletTransaction;
use App\Contracts\AirtimeToWalletTransactionContract;
use App\Enums\TransactionStatus;

class AirtimeToWalletTransactionRepository implements AirtimeToWalletTransactionContract
{

    /**
     * @param array $airtimeToWalletTransaction
     * @return AirtimeToWalletTransaction
     */
    public function create(array $airtimeToWalletTransaction): AirtimeToWalletTransaction
    {
        return AirtimeToWalletTransaction::create($airtimeToWalletTransaction);
    }

    /**
     * @param string $transaction_id
     * @return AirtimeToWalletTransaction
     */
    public function approve_transaction(string $transaction_id): AirtimeToWalletTransaction
    {

        $transaction = AirtimeToWalletTransaction::findOrFail($transaction_id);
        $transaction->status = TransactionStatus::COMPLETED;
        $transaction->save();
        return $transaction;
    }

    /**
     * @param string $transaction_id
     * @return AirtimeToWalletTransaction
     */
    public function decline_transaction(string $transaction_id): AirtimeToWalletTransaction
    {
        $transaction = AirtimeToWalletTransaction::findOrFail($transaction_id);
        $transaction->status = TransactionStatus::FAILED;
        $transaction->save();
        return $transaction;

    }
}