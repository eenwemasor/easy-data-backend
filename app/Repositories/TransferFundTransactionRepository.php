<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:18
 */

namespace App\Repositories;


use App\Contracts\TransferFundTransactionContract;
use App\TransferFundTransaction;

class TransferFundTransactionRepository implements TransferFundTransactionContract
{

    /**
     * @param array $transferFundTransaction
     * @return TransferFundTransaction
     */
    public function create(array $transferFundTransaction): TransferFundTransaction
    {
        // TODO: Implement create() method.
        return TransferFundTransaction::create($transferFundTransaction);
    }
}