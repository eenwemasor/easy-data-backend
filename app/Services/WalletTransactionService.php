<?php


namespace App\Services;


use App\Events\WalletTransactionEvent;
use App\Http\Controllers\UserController;
use App\Repositories\WalletTransactionRepository;

class WalletTransactionService
{
    /**
     * @var WalletTransactionRepository
     */
    private $wallet_transaction_repository;

    /**
     * WalletTransactionService constructor.
     * @param WalletTransactionRepository $wallet_transaction_repository
     */
    public function __construct(WalletTransactionRepository $wallet_transaction_repository)
    {
        $this->wallet_transaction_repository = $wallet_transaction_repository;
    }


    /**
     * @param array $walletTransaction
     * @return mixed
     */
    public function create(array  $walletTransaction)
    {
        $user_cont = New UserController();
        $user = $user_cont->getUserById($walletTransaction["user_id"]);
        $wallet_transaction = $this->wallet_transaction_repository->create($walletTransaction,$user);

        event(new WalletTransactionEvent($wallet_transaction, $user));

        return $wallet_transaction;
    }

}