<?php

namespace App\Services;


use App\Http\Controllers\UserController;
use App\Repositories\TransferFundTransactionRepository;

class TransferFundTransactionService
{
    /**
     * @var TransferFundTransactionRepository
     */
    private $transfer_fund_transaction_repository;

    /**
     * TransferFundTransactionService constructor.
     * @param TransferFundTransactionRepository $transfer_fund_transaction_repository
     */
    function __construct(TransferFundTransactionRepository $transfer_fund_transaction_repository)
    {
        $this->transfer_fund_transaction_repository = $transfer_fund_transaction_repository;
    }


    public function create(array  $transferFundTransaction )
    {
        $transfer_fund_transaction = $this->transfer_fund_transaction_repository->create($transferFundTransaction);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($transferFundTransaction["user_id"]);
        $admin = $user_cont->getAdmin();



        event(new TransferFundTransactionEvent($transfer_fund_transaction,$user, $admin));

        return $transfer_fund_transaction;
    }
}