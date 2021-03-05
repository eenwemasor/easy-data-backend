<?php


namespace App\Repositories;


use App\ResultCheckTransaction;

class ResultCheckerRepository
{
    public function create(array $resultCheckTransactionData)
    {
        return ResultCheckTransaction::create($resultCheckTransactionData);
    }

}
