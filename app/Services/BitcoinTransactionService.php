<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:21
 */

namespace App\Services;


use App\BitcoinTransaction;
use App\Enums\TransactionStatus;
use App\Events\BitcoinTransactionEvent;
use App\GraphQL\Errors\GraphqlError;
use App\Http\Controllers\UserController;
use App\Repositories\BitcoinTransactionRepository;
use App\User;

class BitcoinTransactionService
{
    /**
     * @var BitcoinTransactionRepository
     */
    private $bitcoin_transaction_repository;
    /**
     * @var WalletTransactionService
     */
    private $walletTransactionService;

    /**
     * BitcoinTransactionService constructor.
     * @param BitcoinTransactionRepository $bitcoin_transaction_repository
     * @param WalletTransactionService $walletTransactionService
     */
    function __construct(BitcoinTransactionRepository $bitcoin_transaction_repository, WalletTransactionService $walletTransactionService)
    {
        $this->bitcoin_transaction_repository = $bitcoin_transaction_repository;
        $this->walletTransactionService = $walletTransactionService;
    }

    /**
     * @param array $bitcoinTransaction
     * @return \App\BitcoinTransaction
     * @throws
     */
    public function create(array $bitcoinTransaction)
    {


        $user = User::find($bitcoinTransaction["user_id"]);
        if (!$user->active) {
            throw new GraphqlError("Account not activated, please fund your wallet or pay our one time activation fee to continue.");
        }


        $bitcoinTransaction['status'] = TransactionStatus::PROCESSING;
        $bitcoinTransaction['reference'] = uniqid();

        $bitcoin_transaction = $this->bitcoin_transaction_repository->create($bitcoinTransaction);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($bitcoinTransaction["user_id"]);
        $admin = $user_cont->getAdmin();


        event(new BitcoinTransactionEvent($bitcoin_transaction, $user, $admin));

        return $bitcoin_transaction;
    }


    /**
     * @param string $transaction_id
     * @return BitcoinTransaction
     */
    public function mark_transaction_successful(string $transaction_id)
    {
        return $this->bitcoin_transaction_repository->mark_transaction_successful($transaction_id);
    }

    /**
     * @param string $transaction_id
     * @return BitcoinTransaction
     */
    public function mark_transaction_failed(string $transaction_id)
    {

        return $this->bitcoin_transaction_repository->mark_transaction_failed($transaction_id);
    }
}