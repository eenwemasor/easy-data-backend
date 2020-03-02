<?php


namespace App\Services;


use App\Events\ElectricityTransactionEvent;
use App\Http\Controllers\UserController;
use App\Repositories\ElectricityTransactionRepository;

class ElectricityTransactionService
{
    /**
     * @var ElectricityTransactionRepository
     */
    private $electricity_transaction_repository;

    /**
     * ElectricityTransactionService constructor.
     * @param ElectricityTransactionRepository $electricity_transaction_repository
     */
    public function __construct(ElectricityTransactionRepository $electricity_transaction_repository)
{
    $this->electricity_transaction_repository = $electricity_transaction_repository;
}


    /**
     * @param array $electricityTransaction
     * @return \App\ElectricityTransaction
     */
    public function create(array  $electricityTransaction)
    {
        $electricity_transaction = $this->electricity_transaction_repository->create($electricityTransaction);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($electricityTransaction["user_id"]);
        $admin = $user_cont->getAdmin();
        event(new ElectricityTransactionEvent($electricity_transaction,$user, $admin));

        return $electricity_transaction;
    }
}