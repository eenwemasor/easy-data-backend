<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:19
 */

namespace App\Repositories;


use App\Contracts\UserBankContract;
use App\UserBank;

class UserBankRepository implements UserBankContract
{

    /**
     * @param array $userBank
     * @return UserBank
     */
    public function create(array $userBank): UserBank
    {
       return  UserBank::create($userBank);
    }
}