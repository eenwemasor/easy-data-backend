<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:16
 */

namespace App\Repositories;


use App\AirtimeToCashTransaction;
use App\Contracts\AirtimeToCashTransactionContract;

class AirtimeToCashTransactionRepository implements AirtimeToCashTransactionContract
{
    /**
     * @param array $airtimeToCashTransaction
     * @return AirtimeToCashTransaction
     */
    public function create(array $airtimeToCashTransaction): AirtimeToCashTransaction
    {

        return AirtimeToCashTransaction::create($airtimeToCashTransaction);
    }
}