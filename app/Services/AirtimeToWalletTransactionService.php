<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:21
 */

namespace App\Services;


use App\AirtimeToWalletTransaction;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Events\AirtimeToWalletTransactionEvent;
use App\GraphQL\Errors\GraphqlError;
use App\Http\Controllers\UserController;
use App\Repositories\AirtimeToWalletTransactionRepository;
use App\User;

class AirtimeToWalletTransactionService
{
    /**
     * @var AirtimeToWalletTransactionRepository
     */
    private $airtime_to_wallet_transaction_repository;
    /**
     * @var WalletTransactionService
     */
    private $walletTransactionService;

    /**
     * AirtimeToWalletTransactionService constructor.
     * @param AirtimeToWalletTransactionRepository $airtime_to_wallet_transaction_repository
     * @param WalletTransactionService $walletTransactionService
     */
    function __construct(AirtimeToWalletTransactionRepository $airtime_to_wallet_transaction_repository, WalletTransactionService
     $walletTransactionService)
    {
        $this->airtime_to_wallet_transaction_repository = $airtime_to_wallet_transaction_repository;
        $this->walletTransactionService = $walletTransactionService;
    }


    /**
     * @param array $airtimeToWalletTransaction
     * @return \App\AirtimeToWalletTransaction
     * @throws
     */
    public function create(array  $airtimeToWalletTransaction )
    {

        $user_cont = New UserController();
        $user = $user_cont->getUserById($airtimeToWalletTransaction["user_id"]);
        $user_wallet = $user->wallet;
        $new_balance = $user_wallet + (float)$airtimeToWalletTransaction["amount"];
        $user->wallet = $new_balance;

        if(!$user->active){
            throw new GraphqlError("Account not activated, please fund your wallet or pay our one time activation fee to continue.");
        }

         $value = [
            'reference'=>uniqid(),
            'initial_balance' => $user_wallet,
            'new_balance' =>$new_balance,
            'wallet' =>"WALLET",
            'status' =>TransactionStatus::PROCESSING
        ];

        $data = collect($airtimeToWalletTransaction);

        $airtimeToWalletData = $data->only([
            'transaction_type',
            'phone',
            'network',
            'sender_phone',
            'recipient_phone',
            'amount',
            'user_id'
        ])->toArray();

        $airtimeToWalletTransactionData = array_merge($value, $airtimeToWalletData);


        $airtime_to_wallet_transaction = $this->airtime_to_wallet_transaction_repository->create($airtimeToWalletTransactionData);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($airtimeToWalletTransaction["user_id"]);
        $admin = $user_cont->getAdmin();



        event(new AirtimeToWalletTransactionEvent($airtime_to_wallet_transaction,$user, $admin));

        return $airtime_to_wallet_transaction;
    }


    /**
     * @param string $transaction_id
     * @return AirtimeToWalletTransaction
     */
    public function approve_transaction(string  $transaction_id ){

        $airtimeTransaction = collect(AirtimeToWalletTransaction::find($transaction_id));


        $walletTransactionData = $airtimeTransaction->only([
            'amount',
            'user_id',
        ])->toArray();
        $walletTransactionData['beneficiary'] = $airtimeTransaction['recipient_phone'];
        $walletTransactionData['transaction_type'] = TransactionType::CREDIT;
        $walletTransactionData['description'] = "Airtime to wallet transaction";
        $walletTransactionData['reference'] = $airtimeTransaction['reference'];
        $this->walletTransactionService->create($walletTransactionData);


        $airtime_to_wallet_transaction = $this->airtime_to_wallet_transaction_repository->approve_transaction($transaction_id);
        return $airtime_to_wallet_transaction;
    }

    /**
     * @param string $transaction_id
     *  @return AirtimeToWalletTransaction
     */
    public function decline_transaction(string  $transaction_id ){
        $airtime_to_wallet_transaction = $this->airtime_to_wallet_transaction_repository->decline_transaction($transaction_id);
        return $airtime_to_wallet_transaction;
    }

    static public function total_transaction_statistics($from, $to){
        $total_airtime_to_wallet = AirtimeToWalletTransaction::whereBetween('created_at', [$from, $to]);
        $completed_order = AirtimeToWalletTransaction::whereBetween('created_at', [$from, $to])->where('status',TransactionStatus::COMPLETED);
        $processing_order = AirtimeToWalletTransaction::whereBetween('created_at', [$from, $to])->where('status',TransactionStatus::PROCESSING);
        $failed_order = AirtimeToWalletTransaction::whereBetween('created_at', [$from, $to])->where('status',TransactionStatus::FAILED);
        return [
            'total_airtime_to_wallet'=>$total_airtime_to_wallet->count(),
            'total_airtime_to_wallet_completed'=>$completed_order->count(),
            'total_airtime_to_wallet_processing'=>$processing_order->count(),
            'total_airtime_to_wallet_failed'=>$failed_order->count(),

            'total_airtime_to_wallet_sum'=>StatisticsService::sum_transaction($total_airtime_to_wallet->get()),
            'total_airtime_to_wallet_completed_sum'=>StatisticsService::sum_transaction($completed_order->get()),
            'total_airtime_to_wallet_processing_sum'=>StatisticsService::sum_transaction($processing_order->get()),
            'total_airtime_to_wallet_failed_sum'=>StatisticsService::sum_transaction($failed_order->get())


        ];
    }
}