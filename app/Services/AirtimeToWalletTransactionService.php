<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:21
 */

namespace App\Services;


use App\Http\Controllers\UserController;
use App\Repositories\AirtimeToWalletTransactionRepository;

class AirtimeToWalletTransactionService
{
    /**
     * @var AirtimeToWalletTransactionRepository
     */
    private $airtime_to_wallet_transaction_repository;

    /**
     * AirtimeToWalletTransactionService constructor.
     * @param AirtimeToWalletTransactionRepository $airtime_to_wallet_transaction_repository
     */
    function __construct(AirtimeToWalletTransactionRepository $airtime_to_wallet_transaction_repository)
    {
        $this->airtime_to_wallet_transaction_repository = $airtime_to_wallet_transaction_repository;
    }


    /**
     * @param array $airtimeToWalletTransaction
     * @return \App\AirtimeToWalletTransaction
     */
    public function create(array  $airtimeToWalletTransaction )
    {
        $airtime_to_wallet_transaction = $this->airtime_to_wallet_transaction_repository->create($airtimeToWalletTransaction);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($airtimeToWalletTransaction["user_id"]);
        $admin = $user_cont->getAdmin();



        event(new AirtimeToWalletTransactionEvent($airtime_to_wallet_transaction,$user, $admin));

        return $airtime_to_wallet_transaction;
    }

}