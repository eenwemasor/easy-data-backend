<?php


namespace App\Contracts;


use App\ElectricityTransaction;

interface ElectricityTransactionContract
{

    /**
     * @param array $ElectricityTransaction
     * @return ElectricityTransaction
     */
    public function create(array $ElectricityTransaction):ElectricityTransaction;
}