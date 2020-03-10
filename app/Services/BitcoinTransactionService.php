<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:21
 */

namespace App\Services;


use App\Http\Controllers\UserController;
use App\Repositories\BitcoinTransactionRepository;

class BitcoinTransactionService
{
    /**
     * @var BitcoinTransactionRepository
     */
    private $bitcoin_transaction_repository;

    /**
     * BitcoinTransactionService constructor.
     * @param BitcoinTransactionRepository $bitcoin_transaction_repository
     */
    function __construct(BitcoinTransactionRepository $bitcoin_transaction_repository)
{
    $this->bitcoin_transaction_repository = $bitcoin_transaction_repository;
}

    /**
     * @param array $bitcoinTransaction
     * @return \App\BitcoinTransaction
     */
    public function create(array  $bitcoinTransaction )
    {
        $bitcoin_transaction = $this->bitcoin_transaction_repository->create($bitcoinTransaction);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($bitcoinTransaction["user_id"]);
        $admin = $user_cont->getAdmin();



        event(new BitcoinTransactionEvent($bitcoin_transaction,$user, $admin));

        return $bitcoin_transaction;
    }
}