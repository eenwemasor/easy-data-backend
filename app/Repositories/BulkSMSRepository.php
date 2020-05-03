<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21/04/2020
 * Time: 09:06
 */

namespace App\Repositories;


use App\BulkSMS;
use App\Contracts\BulkSMSContract;
use App\Enums\BulkSMSStatus;

class BulkSMSRepository implements BulkSMSContract
{

    /**
     * @param array $sms
     * @return BulkSMS
     */
    public function create(array $sms): BulkSMS
    {
        return  BulkSMS::create($sms);
    }


    public function mark_transaction_successful(string $transaction_id): BulkSMS
    {
        $transaction = BulkSMS::findOrFail($transaction_id);

        if($transaction->status === BulkSMSStatus::DELIVERED){
            return $transaction;
        }

        $transaction->status = BulkSMSStatus::DELIVERED;
        $transaction->save();
        return $transaction;
    }


    public function mark_transaction_failed(string $transaction_id): BulkSMS
    {
        $transaction = BulkSMS::findOrFail($transaction_id);

        if($transaction->status === BulkSMSStatus::FAILED){
            return $transaction;
        }


        $transaction->status = BulkSMSStatus::FAILED;
        $transaction->save();
        return $transaction;
    }
    
}