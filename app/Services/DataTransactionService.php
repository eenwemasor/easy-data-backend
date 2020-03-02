<?php


namespace App\Services;


use App\Events\DataTransactionEvent;
use App\Http\Controllers\UserController;
use App\Repositories\DataTransactionRepository;

class DataTransactionService
{
    /**
     * @var DataTransactionRepository
     */
    private $data_transaction_repository;

    /**
     * DataTransactionService constructor.
     * @param DataTransactionRepository $data_transaction_repository
     */
    public function __construct(DataTransactionRepository $data_transaction_repository)
    {
        $this->data_transaction_repository = $data_transaction_repository;
    }

    /**
     * @param array $dataTransaction
     * @return \App\DataTransaction
     */
    public function create(array  $dataTransaction)
    {
        $data_transaction = $this->data_transaction_repository->create($dataTransaction);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($dataTransaction["user_id"]);
        $admin = $user_cont->getAdmin();

        event(new DataTransactionEvent($data_transaction,$user, $admin));

        return $data_transaction;
    }
}