<?php

namespace App\Services;


use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Events\TransferFundTransactionEvent;
use App\GraphQL\Errors\GraphqlError;
use App\Http\Controllers\UserController;
use App\Repositories\TransferFundTransactionRepository;
use App\TransferFundTransaction;
use App\User;

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
     * @throws
     */
    public function create(array  $transferFundTransaction )
    {


        $user_cont = New UserController();
        $user = $user_cont->getUserById($transferFundTransaction["user_id"]);

        if (!$user->active) {
            throw new GraphqlError("Account not activated, please fund your wallet or pay our one time activation fee to continue.");
        }

        $initial_balance = $user->wallet;
        $new_balance = $initial_balance - (float)$transferFundTransaction["amount"];

        $user = $user_cont->getUserById($transferFundTransaction["user_id"]);
        $recipient = $user_cont->getUserById($transferFundTransaction["recipient_id"]);
        $admin = $user_cont->getAdmin();

        $value = [
            'reference'=>uniqid(),
            'initial_balance'=>$initial_balance,
            'new_balance' =>$new_balance,
            'status'=> TransactionStatus::PROCESSING
        ];

        $transferFundTransactionData = array_merge($value, $transferFundTransaction);

        $transfer_fund_transaction = $this->transfer_fund_transaction_repository->create($transferFundTransactionData);

        event(new TransferFundTransactionEvent($transfer_fund_transaction,$user, $admin, $recipient));

        return $transfer_fund_transaction;
    }

    public function approve_transaction($transaction_id)
    {
        $transferFundTransaction = collect(TransferFundTransaction::find($transaction_id));
        $recipient = User::find($transferFundTransaction['recipient_id']);


        $transferFundTransactionData = $transferFundTransaction->only([
            'amount',
            'user_id',
        ])->toArray();
        $transferFundTransactionData['beneficiary'] =$recipient->full_name;
        $transferFundTransactionData['transaction_type'] = TransactionType::DEBIT;
        $transferFundTransactionData['description'] = "Transfer of Fund";
        $transferFundTransactionData['reference'] = $transferFundTransaction['reference'];
        $this->walletTransactionService->create($transferFundTransactionData);


        $transfer_fund_transaction = $this->transfer_fund_transaction_repository->approve_transaction($transaction_id);
        return $transfer_fund_transaction;
    }

    public function decline_transaction($transaction_id)
    {
        $transfer_fund_transaction = $this->transfer_fund_transaction_repository->decline_transaction($transaction_id);
        return $transfer_fund_transaction;
    }


}