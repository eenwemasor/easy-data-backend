<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:24
 */

namespace App\Services;


use App\Enums\TransactionStatus;
use App\Events\WithdrawFundTransactionEvent;
use App\Http\Controllers\UserController;
use App\Repositories\WithdrawFundTransactionRepository;
use App\UserBank;
use App\WithdrawFundTransaction;

class WithdrawFundTransactionService
{
    /**
     * @var WithdrawFundTransactionRepository
     */
    private $withdraw_fund_transaction_repository;
    /**
     * @var WalletTransactionService
     */
    private $walletTransactionService;

    /**
     * WithdrawFundTransactionService constructor.
     * @param WithdrawFundTransactionRepository $withdraw_fund_transaction_repository
     * @param WalletTransactionService $walletTransactionService
     */
    function __construct(WithdrawFundTransactionRepository $withdraw_fund_transaction_repository, WalletTransactionService $walletTransactionService)
    {
        $this->withdraw_fund_transaction_repository = $withdraw_fund_transaction_repository;
        $this->walletTransactionService = $walletTransactionService;
    }

    /**
     * @param array $withdrawFundTransaction
     * @return WithdrawFundTransaction
     */
    public function create(array  $withdrawFundTransaction )
    {
        $data = collect($withdrawFundTransaction);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($withdrawFundTransaction["user_id"]);
        $admin = $user_cont->getAdmin();

        $receiving_bank = UserBank::find($withdrawFundTransaction["bank_id"]);
        $walletTransactionData = $data->only([
            'transaction_type',
            'description',
            'amount',
            'user_id',
        ])->toArray();
        $walletTransactionData['beneficiary'] = $receiving_bank->name;

        $walletTransactionResult =  $this->walletTransactionService->create($walletTransactionData);
        $withdrawFundData = $data->only([
            'bank_id',
        ])->toArray();

        $wallet_result = collect($walletTransactionResult);
        $wallet_result['status'] =TransactionStatus::SENT;

        $withdrawFundTransactionData = array_merge(
            $wallet_result->except([
                'transaction_type',
                'description',
                'beneficiary'
            ])->toArray(), $withdrawFundData);

        $withdraw_fund_transaction = $this->withdraw_fund_transaction_repository->create($withdrawFundTransactionData);
        event(new WithdrawFundTransactionEvent($withdraw_fund_transaction,$user, $admin,$receiving_bank));

        return $withdraw_fund_transaction;
    }
}