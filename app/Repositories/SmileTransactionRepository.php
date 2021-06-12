<?php


namespace App\Repositories;


use App\SmileTransaction;

class SmileTransactionRepository
{

    /**
     * @param array $smileTransaction
     * @return SmileTransaction
     */
    public function create(array $smileTransaction): SmileTransaction
    {
        return SmileTransaction::create($smileTransaction);
    }
  }
