<?php


namespace App\Contracts;


use App\AirtimeTransaction;

interface AirtimeTransactionContract
{
    /**
     * @param array $airtimeTransaction
     * @return AirtimeTransaction
     */
public function create(array $airtimeTransaction): AirtimeTransaction;
}