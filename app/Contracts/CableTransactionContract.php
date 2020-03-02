<?php


namespace App\Contracts;


use App\CableTransaction;

interface CableTransactionContract
{
    /**
     * @param array $CableTransaction
     * @return CableTransaction
     */
    public function create(array $CableTransaction):CableTransaction;
}