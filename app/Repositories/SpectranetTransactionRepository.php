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
use App\SpectranetPriceList;
use App\SpectranetTransaction;
use App\User;

class SpectranetTransactionRepository
{

    /**
     * @param array $spectranetTransaction
     * @return CableTransaction
     */
    public function create(array $spectranetTransaction): CableTransaction
    {
        $spectranetPlan = SpectranetPriceList::find($spectranetTransaction['plan']);
        $spectranetTransaction['plan'] = $spectranetPlan->description;
        return SpectranetTransaction::create($spectranetTransaction);
    }
  }
