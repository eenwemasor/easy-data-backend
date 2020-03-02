<?php


namespace App\Services;


use App\Events\CableTransactionEvent;
use App\Http\Controllers\UserController;
use App\Repositories\CableTransactionRepository;

class CableTransactionService
{
    /**
     * @var CableTransactionRepository
     */
    private $cable_transaction_repository;

    /**
     * CableTransactionService constructor.
     * @param CableTransactionRepository $cable_transaction_repository
     */
    public function __construct(CableTransactionRepository $cable_transaction_repository)
    {
        $this->cable_transaction_repository = $cable_transaction_repository;
    }


    /**
     * @param array $cableTransaction
     * @return \App\CableTransaction
     */
    public function create(array  $cableTransaction)
    {
        $cable_transaction = $this->cable_transaction_repository->create($cableTransaction);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($cableTransaction["user_id"]);
        $admin = $user_cont->getAdmin();
        event(new CableTransactionEvent($cable_transaction,$user, $admin));

        return $cable_transaction;
    }

}