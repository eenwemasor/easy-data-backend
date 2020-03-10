<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:17
 */

namespace App\Repositories;


use App\Contracts\GiftcardTransactionContract;
use App\GiftcardTransaction;

class GiftcardTransactionRepository implements GiftcardTransactionContract
{

    /**
     * @param array $giftcardTransaction
     * @return GiftcardTransaction
     */
    public function create(array $giftcardTransaction): GiftcardTransaction
    {
        // TODO: Implement create() method.

        return GiftcardTransaction::create($giftcardTransaction);
    }
}