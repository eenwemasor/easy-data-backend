<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:19
 */

namespace App\Repositories;


use App\Contracts\WithdrawFundTransactionContract;
use App\WithdrawFundTransaction;

class WithdrawFundTransactionRepository implements WithdrawFundTransactionContract
{

    /**
     * @param array $withdrawFundTransaction
     * @return WithdrawFundTransaction
     */
    public function create(array $withdrawFundTransaction): WithdrawFundTransaction
    {
        // TODO: Implement create() method.
        return WithdrawFundTransaction::create($withdrawFundTransaction);
    }
}