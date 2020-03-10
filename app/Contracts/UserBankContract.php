<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:02
 */

namespace App\Contracts;


use App\UserBank;

interface UserBankContract
{

    /**
     * @param array $userBank
     * @return UserBank
     */
    public function create(array $userBank): UserBank;
}