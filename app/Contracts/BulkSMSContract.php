<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21/04/2020
 * Time: 09:07
 */

namespace App\Contracts;


use App\BulkSMS;

interface BulkSMSContract
{
    /**
     * @param array $sms
     * @return BulkSMS
     */
    public function create(array $sms): BulkSMS;
}