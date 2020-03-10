<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:00
 */

namespace App\Contracts;


use App\BitcoinTransaction;

interface BitcoinTransactionContract
{
    /**
     * @param array $bitcoinTransaction
     * @return BitcoinTransaction
     */
    public function create(array $bitcoinTransaction): BitcoinTransaction;
}