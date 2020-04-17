<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:01
 */

namespace App\Contracts;


use App\GiftcardTransaction;

interface GiftcardTransactionContract
{
    /**
     * @param array $giftcardTransaction
     * @return GiftcardTransaction
     */
    public function create(array $giftcardTransaction): GiftcardTransaction;

    /**
     * @param string $transaction_id
     * @return GiftcardTransaction
     */
    public function mark_transaction_successful(string $transaction_id): GiftcardTransaction;

    /**
     * @param string $transaction_id
     * @return GiftcardTransaction
     */
    public function mark_transaction_failed(string $transaction_id): GiftcardTransaction;


}