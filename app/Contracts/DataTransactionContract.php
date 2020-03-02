<?php


namespace App\Contracts;


use App\DataTransaction;
use App\User;

interface DataTransactionContract
{
    /**
     * @param array $DataTransaction
     * @return DataTransaction
     */
    public function create(array $DataTransaction):DataTransaction;
}