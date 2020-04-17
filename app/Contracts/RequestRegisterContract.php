<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27/03/2020
 * Time: 14:03
 */

namespace App\Contracts;


use App\RequestRegister;

interface RequestRegisterContract
{

    /**
     * @param array $transferFundRequestRegister
     * @return RequestRegister
     */
    public function create(array $transferFundRequestRegister): RequestRegister;

}