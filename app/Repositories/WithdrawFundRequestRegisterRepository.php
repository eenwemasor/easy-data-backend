<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27/03/2020
 * Time: 20:18
 */

namespace App\Repositories;


use App\Contracts\RequestRegisterContract;
use App\RequestRegister;

class WithdrawFundRequestRegisterRepository implements RequestRegisterContract
{

    /**
     * @param array $withdrawFund
     * @return RequestRegister
     */
    public function create(array $withdrawFund): RequestRegister
    {
        return RequestRegister::create($withdrawFund);
    }


}