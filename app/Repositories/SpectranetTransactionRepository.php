<?php


namespace App\Repositories;


use App\SpectranetTransaction;

class SpectranetTransactionRepository
{

    /**
     * @param array $spectranetTransaction
     * @return SpectranetTransaction
     */
    public function create(array $spectranetTransaction): SpectranetTransaction
    {
        return SpectranetTransaction::create($spectranetTransaction);
    }
  }
