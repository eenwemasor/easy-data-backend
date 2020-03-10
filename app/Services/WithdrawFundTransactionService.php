<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:24
 */

namespace App\Services;


use App\Http\Controllers\UserController;
use App\Repositories\WithdrawFundTransactionRepository;
use App\WithdrawFundTransaction;

class WithdrawFundTransactionService
{
    /**
     * @var WithdrawFundTransactionRepository
     */
    private $withdraw_fund_transaction_repository;

    /**
     * WithdrawFundTransactionService constructor.
     * @param WithdrawFundTransactionRepository $withdraw_fund_transaction_repository
     */
    function __construct(WithdrawFundTransactionRepository $withdraw_fund_transaction_repository)
    {
        $this->withdraw_fund_transaction_repository = $withdraw_fund_transaction_repository;
    }

    public function create(array  $withdrawFundTransaction )
    {
        $withdraw_fund_transaction = $this->withdraw_fund_transaction_repository->create($withdrawFundTransaction);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($withdrawFundTransaction["user_id"]);
        $admin = $user_cont->getAdmin();



        event(new WithdrawFundTransactionEvent($withdraw_fund_transaction,$user, $admin));

        return $withdraw_fund_transaction;
    }
}