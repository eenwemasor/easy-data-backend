<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:24
 */

namespace App\Services;


use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Events\WithdrawFundTransactionEvent;
use App\GraphQL\Errors\GraphqlError;
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
     * @throws
     */
    public function create(array  $withdrawFundTransaction )
    {
        $user_cont = New UserController();
        $user = $user_cont->getUserById($withdrawFundTransaction["user_id"]);

        if (!$user->active) {
            throw new GraphqlError("Account not activated, please fund your wallet or pay our one time activation fee to continue.");
        }

        $initial_balance = $user->wallet;
        $new_balance = $initial_balance - (float)$withdrawFundTransaction["amount"];


        $user = $user_cont->getUserById($withdrawFundTransaction["user_id"]);
        $admin = $user_cont->getAdmin();

        $receiving_bank = UserBank::find($withdrawFundTransaction["bank_id"]);

        $value = [
            'reference'=>uniqid(),
            'initial_balance'=>$initial_balance,
            'new_balance' =>$new_balance,
            'status'=> TransactionStatus::PROCESSING
        ];

        $withdrawFundTransactionData = array_merge($value,$withdrawFundTransaction);

        $withdraw_fund_transaction = $this->withdraw_fund_transaction_repository->create($withdrawFundTransactionData);
        event(new WithdrawFundTransactionEvent($withdraw_fund_transaction,$user, $admin,$receiving_bank));

        return $withdraw_fund_transaction;
    }

    public function approve_transaction($transaction_id)
    {
        $withdrawFundTransaction = collect(WithdrawFundTransaction::find($transaction_id));
        $bank = UserBank::find($withdrawFundTransaction['bank_id']);
        $withdrawFundTransactionData = $withdrawFundTransaction->only([
            'amount',
            'user_id',
        ])->toArray();
        $withdrawFundTransactionData['beneficiary'] =$bank->name . " " . $bank->bank_name;
        $withdrawFundTransactionData['transaction_type'] = TransactionType::DEBIT;
        $withdrawFundTransactionData['description'] = "Withdrawal of Fund";
        $withdrawFundTransactionData['reference'] = $withdrawFundTransaction['reference'];
        $this->walletTransactionService->create($withdrawFundTransactionData);

        $transfer_fund_transaction = $this->withdraw_fund_transaction_repository->approve_transaction($transaction_id);
        return $transfer_fund_transaction;
    }

    public function decline_transaction($transaction_id)
    {
        $withdraw_fund_transaction = $this->withdraw_fund_transaction_repository->decline_transaction($transaction_id);
        return $withdraw_fund_transaction;
    }

    static public function total_transaction_statistics($from, $to){
        $amount =0;
        $total_completed_transaction = WithdrawFundTransaction::where('status',TransactionStatus::COMPLETED)->whereBetween('created_at', [$from, $to])->get();
        foreach ( $total_completed_transaction as $data){
            $amount += $data->amount;
        }
        return $amount;
    }


}