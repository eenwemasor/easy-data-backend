<?php


namespace App\Services;


use App\Enums\TransactionStatus;
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
     * @var WalletTransactionService
     */
    private $walletTransactionService;

    /**
     * ElectricityTransactionService constructor.
     * @param ElectricityTransactionRepository $electricity_transaction_repository
     * @param WalletTransactionService $walletTransactionService
     */
    public function __construct(ElectricityTransactionRepository $electricity_transaction_repository,  WalletTransactionService $walletTransactionService)
{
    $this->electricity_transaction_repository = $electricity_transaction_repository;
    $this->walletTransactionService = $walletTransactionService;
}


    /**
     * @param array $electricityTransaction
     * @return \App\ElectricityTransaction
     */
    public function create(array  $electricityTransaction)
    {

        $data = collect($electricityTransaction);

        $walletTransactionData = $data->only([
            'transaction_type',
            'description',
            'amount',
            'user_id',
        ])->toArray();
        $walletTransactionData['beneficiary'] = $electricityTransaction['beneficiary_name'];


        $walletTransactionResult =  $this->walletTransactionService->create($walletTransactionData);
        $electricityData = $data->only([
            'decoder',
            'decoder_number',
            'beneficiary_name',
            'plan'
        ])->toArray();

        $wallet_result = collect($walletTransactionResult);
        $electricityData['method'] =$walletTransactionResult['wallet'];
        $wallet_result['status'] =TransactionStatus::SENT;
        $electricityTransactionData = array_merge(
            $wallet_result->except([
                'transaction_type',
                'description',
                'beneficiary',
                'status'
            ])->toArray(), $electricityData);


        $electricity_transaction = $this->electricity_transaction_repository->create($electricityTransactionData);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($electricityTransaction["user_id"]);
        $admin = $user_cont->getAdmin();
        event(new ElectricityTransactionEvent($electricity_transaction,$user, $admin));

        return $electricity_transaction;
    }
}