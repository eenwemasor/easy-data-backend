<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27/03/2020
 * Time: 14:11
 */

namespace App\Repositories;


use App\Contracts\RequestRegisterContract;
use App\RequestRegister;

class TransferFundRequestRegisterRepository implements RequestRegisterContract
{

    /**
     * @param array $transferFundRequestRegister
     * @return RequestRegister
     */
    public function create(array $transferFundRequestRegister): RequestRegister
    {
        return RequestRegister::create($transferFundRequestRegister);
    }

}