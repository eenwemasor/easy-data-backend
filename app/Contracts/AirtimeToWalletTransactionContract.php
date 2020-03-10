<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:00
 */

namespace App\Contracts;


use App\AirtimeToWalletTransaction;

interface AirtimeToWalletTransactionContract
{
    /**
     * @param array $airtimeToWalletTransaction
     * @return AirtimeToWalletTransaction
     */
    public function create(array $airtimeToWalletTransaction): AirtimeToWalletTransaction;
}