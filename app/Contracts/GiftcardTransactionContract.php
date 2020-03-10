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
}