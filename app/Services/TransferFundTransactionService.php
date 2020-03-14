<?php

namespace App\Services;


use App\Enums\TransactionStatus;
use App\Events\TransferFundTransactionEvent;
use App\Http\Controllers\UserController;
use App\Repositories\TransferFundTransactionRepository;

class TransferFundTransactionService
{
    /**
     * @var TransferFundTransactionRepository
     */
    private $transfer_fund_transaction_repository;
    /**
     * @var WalletTransactionService
     */
    private $walletTransactionService;

    /**
     * TransferFundTransactionService constructor.
     * @param TransferFundTransactionRepository $transfer_fund_transaction_repository
     * @param WalletTransactionService $walletTransactionService
     */
    function __construct(TransferFundTransactionRepository $transfer_fund_transaction_repository, WalletTransactionService $walletTransactionService)
    {
        $this->transfer_fund_transaction_repository = $transfer_fund_transaction_repository;
        $this->walletTransactionService = $walletTransactionService;
    }


    /**
     * @param array $transferFundTransaction
     * @return \App\TransferFundTransaction
     */
    public function create(array  $transferFundTransaction )
    {

        $data = collect($transferFundTransaction);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($transferFundTransaction["user_id"]);
        $recipient = $user_cont->getUserById($transferFundTransaction["recipient_id"]);
        $admin = $user_cont->getAdmin();

        $walletTransactionData = $data->only([
            'transaction_type',
            'description',
            'amount',
            'user_id',
        ])->toArray();
        $walletTransactionData['beneficiary'] = $recipient->full_name;


        $walletTransactionResult =  $this->walletTransactionService->create($walletTransactionData);
        $transferFundData = $data->only([
            'recipient_id',
        ])->toArray();

        $wallet_result = collect($walletTransactionResult);
        $wallet_result['status'] =TransactionStatus::SENT;
        $transferFundTransactionData = array_merge(
            $wallet_result->except([
                'transaction_type',
                'description',
                'beneficiary'
            ])->toArray(), $transferFundData);

        $transfer_fund_transaction = $this->transfer_fund_transaction_repository->create($transferFundTransactionData);




        event(new TransferFundTransactionEvent($transfer_fund_transaction,$user, $admin, $recipient));

        return $transfer_fund_transaction;
    }
}