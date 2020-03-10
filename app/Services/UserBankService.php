<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:23
 */

namespace App\Services;


use App\Repositories\UserBankRepository;
use App\UserBank;

class UserBankService
{
    /**
     * @var UserBankRepository
     */
    private $user_bank_repository;

    /**
     * UserBankService constructor.
     * @param UserBankRepository $user_bank_repository
     */
    function __construct(UserBankRepository $user_bank_repository)
    {
        $this->user_bank_repository = $user_bank_repository;
    }

    public function create(array  $userBank )
    {
        $user_bank = $this->user_bank_repository->create($userBank);

        return $user_bank;
    }
}