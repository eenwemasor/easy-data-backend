<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:02
 */

namespace App\Contracts;


use App\TransferFundTransaction;

interface TransferFundTransactionContract
{
    /**
     * @param array $transferFundTransaction
     * @return TransferFundTransaction
     */
    public function create(array $transferFundTransaction): TransferFundTransaction;
}