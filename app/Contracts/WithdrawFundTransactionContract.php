<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:03
 */

namespace App\Contracts;


use App\WithdrawFundTransaction;

interface WithdrawFundTransactionContract
{
    /**
     * @param array $withdrawFundTransaction
     * @return WithdrawFundTransaction
     */
    public function create(array $withdrawFundTransaction): WithdrawFundTransaction;
}