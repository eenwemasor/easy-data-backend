<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:21
 */

namespace App\Services;


use App\Enums\TransactionStatus;
use App\Events\BitcoinTransactionEvent;
use App\Http\Controllers\UserController;
use App\Repositories\BitcoinTransactionRepository;

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
     */
    public function create(array  $bitcoinTransaction )
    {

        $data = collect($bitcoinTransaction);

        $walletTransactionData = $data->only([
            'transaction_type',
            'description',
            'beneficiary',
            'user_id',
        ])->toArray();
        $walletTransactionData['amount'] = $bitcoinTransaction['amount_to_receive'];

        $walletTransactionResult =  $this->walletTransactionService->create($walletTransactionData);
        $bitcoinData = $data->only([
            'amount_to_sell',
            'amount_to_receive',
            'buying_rate',
        ])->toArray();

        $wallet_result = collect($walletTransactionResult);
        $wallet_result['status'] =TransactionStatus::SENT;
        $bitcoinTransactionData = array_merge(
            $wallet_result->except([
                'transaction_type',
                'description',
                'amount'
            ])->toArray(), $bitcoinData);

        $bitcoin_transaction = $this->bitcoin_transaction_repository->create($bitcoinTransactionData);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($bitcoinTransaction["user_id"]);
        $admin = $user_cont->getAdmin();



        event(new BitcoinTransactionEvent($bitcoin_transaction,$user, $admin));

        return $bitcoin_transaction;
    }
}