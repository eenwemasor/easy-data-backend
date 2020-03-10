<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:17
 */

namespace App\Repositories;


use App\BitcoinTransaction;
use App\Contracts\BitcoinTransactionContract;

class BitcoinTransactionRepository implements BitcoinTransactionContract
{
    /**
     * @param array $bitcoinTransaction
     * @return BitcoinTransaction
     */
    public function create(array $bitcoinTransaction): BitcoinTransaction
    {
        // TODO: Implement create() method.
        return  BitcoinTransaction::create($bitcoinTransaction);
    }
}