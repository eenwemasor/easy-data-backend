<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:20
 */

namespace App\Services;


use App\AirtimeToCashTransaction;
use App\Http\Controllers\UserController;
use App\Repositories\AirtimeToCashTransactionRepository;

class AirtimeToCashTransactionService
{
    /**
     * @var AirtimeToCashTransactionRepository
     */
    private $airtime_to_cash_transaction_repository;

    /**
     * AirtimeToCashTransactionService constructor.
     * @param AirtimeToCashTransactionRepository $airtime_to_cash_transaction_repository
     */
    function __construct(AirtimeToCashTransactionRepository $airtime_to_cash_transaction_repository)
    {
        $this->airtime_to_cash_transaction_repository = $airtime_to_cash_transaction_repository;


    }



    /**
     * @param array $airtimeToCashTransaction
     * @return AirtimeToCashTransaction
     */
    public function create(array  $airtimeToCashTransaction )
    {
        $airtime_to_cash_transaction = $this->airtime_to_cash_transaction_repository->create($airtimeToCashTransaction);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($airtimeToCashTransaction["user_id"]);
        $admin = $user_cont->getAdmin();



        event(new AirtimeToCashTransactionEvent($airtime_to_cash_transaction,$user, $admin));

        return $airtime_to_cash_transaction;
    }



}