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

    /**
     * @param string $transaction_id
     * @return WithdrawFundTransaction
     */
    public function approve_transaction(string $transaction_id): WithdrawFundTransaction;

    /**
     * @param string $transaction_id
     * @return WithdrawFundTransaction
     */
    public function decline_transaction(string $transaction_id): WithdrawFundTransaction;
}