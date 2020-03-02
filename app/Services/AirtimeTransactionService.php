<?php


namespace App\Services;


use App\Events\AirtimeTransactionEvent;
use App\Http\Controllers\UserController;
use App\Repositories\AirtimeTransactionRepository;

class AirtimeTransactionService
{
    /**
     * @var AirtimeTransactionRepository
     */
    private $airtime_transaction_repository;

    /**
     * AirtimeTransactionService constructor.
     * @param AirtimeTransactionRepository $airtime_transaction_repository
     */
    public function __construct(AirtimeTransactionRepository $airtime_transaction_repository)
    {
        $this->airtime_transaction_repository = $airtime_transaction_repository;
    }


    /**
     * @param array $airtimeTransaction
     * @return \App\AirtimeTransaction
     */
    public function create(array  $airtimeTransaction )
    {
        $airtime_transaction = $this->airtime_transaction_repository->create($airtimeTransaction);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($airtimeTransaction["user_id"]);
        $admin = $user_cont->getAdmin();



        event(new AirtimeTransactionEvent($airtime_transaction,$user, $admin));

        return $airtime_transaction;
    }
}