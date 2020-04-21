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
}