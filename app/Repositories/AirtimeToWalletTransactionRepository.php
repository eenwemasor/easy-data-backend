<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:16
 */

namespace App\Repositories;


use App\AirtimeToWalletTransaction;
use App\Contracts\AirtimeToWalletTransactionContract;

class AirtimeToWalletTransactionRepository implements AirtimeToWalletTransactionContract
{

    /**
     * @param array $airtimeToWalletTransaction
     * @return AirtimeToWalletTransaction
     */
    public function create(array $airtimeToWalletTransaction): AirtimeToWalletTransaction
    {
        // TODO: Implement create() method.

        return AirtimeToWalletTransaction::create($airtimeToWalletTransaction);
    }
}