<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:17
 */

namespace App\Repositories;


use App\Contracts\GiftcardTransactionContract;
use App\Enums\TransactionStatus;
use App\GiftcardTransaction;

class GiftcardTransactionRepository implements GiftcardTransactionContract
{

    /**
     * @param array $giftcardTransaction
     * @return GiftcardTransaction
     */
    public function create(array $giftcardTransaction): GiftcardTransaction
    {

        return GiftcardTransaction::create($giftcardTransaction);
    }

    /**
     * @param string $transaction_id
     * @return GiftcardTransaction
     */
    public function mark_transaction_successful(string $transaction_id): GiftcardTransaction
    {
        $transaction = GiftcardTransaction::findOrFail($transaction_id);
        $transaction->status = TransactionStatus::COMPLETED;
        $transaction->save();
        return $transaction;
    }

    /**
     * @param string $transaction_id
     * @return GiftcardTransaction
     */
    public function mark_transaction_failed(string $transaction_id): GiftcardTransaction
    {
        $transaction = GiftcardTransaction::findOrFail($transaction_id);
        $transaction->status = TransactionStatus::FAILED;
        $transaction->save();
        return $transaction;
    }
}