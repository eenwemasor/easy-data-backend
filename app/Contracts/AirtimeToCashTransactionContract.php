<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 21:59
 */

namespace App\Contracts;


use App\AirtimeToCashTransaction;

interface AirtimeToCashTransactionContract
{
    /**
     * @param array $airtimeToCashTransaction
     * @return AirtimeToCashTransaction
     */
    public function create(array $airtimeToCashTransaction): AirtimeToCashTransaction;

}