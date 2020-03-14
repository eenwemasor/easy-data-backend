<?php


namespace App\Services;


use App\Enums\TransactionStatus;
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
     * @var WalletTransactionService
     */
    private $walletTransactionService;

    /**
     * DataTransactionService constructor.
     * @param DataTransactionRepository $data_transaction_repository
     * @param WalletTransactionService $walletTransactionService
     */
    public function __construct(DataTransactionRepository $data_transaction_repository, WalletTransactionService $walletTransactionService)
    {
        $this->data_transaction_repository = $data_transaction_repository;
        $this->walletTransactionService = $walletTransactionService;
    }

    /**
     * @param array $dataTransaction
     * @return \App\DataTransaction
     */
    public function create(array  $dataTransaction)
    {

        $data = collect($dataTransaction);

        $walletTransactionData = $data->only([
            'transaction_type',
            'description',
            'amount',
            'beneficiary',
            'user_id',
        ])->toArray();


        $walletTransactionResult =  $this->walletTransactionService->create($walletTransactionData);
        $dataData = $data->only([
            'network',
            'data',
        ])->toArray();
        $dataData['method'] =$walletTransactionResult['wallet'];
        $wallet_result = collect($walletTransactionResult);
        $wallet_result['status'] =TransactionStatus::SENT;
        $dataTransactionData = array_merge(
            $wallet_result->except([
                'transaction_type',
                'description',
                'status'
            ])->toArray(), $dataData);


        $data_transaction = $this->data_transaction_repository->create($dataTransactionData);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($dataTransaction["user_id"]);
        $admin = $user_cont->getAdmin();

        event(new DataTransactionEvent($data_transaction,$user, $admin));

        return $data_transaction;
    }
}