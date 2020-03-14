<?php


namespace App\Services;


use App\Enums\TransactionStatus;
use App\Events\CableTransactionEvent;
use App\Http\Controllers\UserController;
use App\Repositories\CableTransactionRepository;

class CableTransactionService
{
    /**
     * @var CableTransactionRepository
     */
    private $cable_transaction_repository;
    /**
     * @var WalletTransactionService
     */
    private $walletTransactionService;

    /**
     * CableTransactionService constructor.
     * @param CableTransactionRepository $cable_transaction_repository
     * @param WalletTransactionService $walletTransactionService
     */
    public function __construct(CableTransactionRepository $cable_transaction_repository, WalletTransactionService $walletTransactionService)
    {
        $this->cable_transaction_repository = $cable_transaction_repository;
        $this->walletTransactionService = $walletTransactionService;
    }


    /**
     * @param array $cableTransaction
     * @return \App\CableTransaction
     */
    public function create(array  $cableTransaction)
    {

        $data = collect($cableTransaction);

        $walletTransactionData = $data->only([
            'transaction_type',
            'description',
            'amount',
            'user_id',
        ])->toArray();
        $walletTransactionData['beneficiary'] = $cableTransaction['beneficiary_name'];


        $walletTransactionResult =  $this->walletTransactionService->create($walletTransactionData);
        $cableData = $data->only([
            'decoder',
            'decoder_number',
            'beneficiary_name',
            'plan'
        ])->toArray();

        $wallet_result = collect($walletTransactionResult);
        $cableData['method'] =$walletTransactionResult['wallet'];
        $wallet_result['status'] =TransactionStatus::SENT;
        $cableTransactionData = array_merge(
            $wallet_result->except([
                'transaction_type',
                'description',
                'beneficiary',
                'status'
            ])->toArray(), $cableData);


        $cable_transaction = $this->cable_transaction_repository->create($cableTransactionData);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($cableTransaction["user_id"]);
        $admin = $user_cont->getAdmin();
        event(new CableTransactionEvent($cable_transaction,$user, $admin));

        return $cable_transaction;
    }

}