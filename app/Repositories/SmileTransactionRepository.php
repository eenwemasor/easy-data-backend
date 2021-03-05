<?php


namespace App\Repositories;


use App\CablePlanList;
use App\CableTransaction;
use App\Contracts\CableTransactionContract;
use App\Enums\TransactionStatus;
use App\Http\Controllers\SendSMSController;
use App\Http\Controllers\Wallet;
use App\SmilePriceList;
use App\SmileTransaction;
use App\User;

class SmileTransactionRepository
{

    /**
     * @param array $smileTransaction
     * @return CableTransaction
     */
    public function create(array $smileTransaction): CableTransaction
    {
        $smilePlan = SmilePriceList::find($smileTransaction['plan']);
        $smileTransaction['plan'] = $smilePlan->description;
        return SmileTransaction::create($smileTransaction);
    }
  }
