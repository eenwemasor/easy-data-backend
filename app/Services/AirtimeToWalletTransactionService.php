<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:21
 */

namespace App\Services;


use App\Enums\TransactionStatus;
use App\Events\AirtimeToWalletTransactionEvent;
use App\Http\Controllers\UserController;
use App\Repositories\AirtimeToWalletTransactionRepository;

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
     */
    public function create(array  $airtimeToWalletTransaction )
    {

        $data = collect($airtimeToWalletTransaction);

        $walletTransactionData = $data->only([
            'transaction_type',
            'description',
            'amount',
            'beneficiary',
            'user_id',
        ])->toArray();


        $walletTransactionResult =  $this->walletTransactionService->create($walletTransactionData);
        $airtimeToWalletData = $data->only([
            'phone',
            'network',
            'sender_phone',
            'recipient_phone'
        ])->toArray();

        $wallet_result = collect($walletTransactionResult);
        $wallet_result['status'] =TransactionStatus::SENT;
        $airtimeToWalletTransactionData = array_merge(
            $wallet_result->except([
                'transaction_type',
                'description'
            ])->toArray(), $airtimeToWalletData);


        $airtime_to_wallet_transaction = $this->airtime_to_wallet_transaction_repository->create($airtimeToWalletTransactionData);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($airtimeToWalletTransaction["user_id"]);
        $admin = $user_cont->getAdmin();



        event(new AirtimeToWalletTransactionEvent($airtime_to_wallet_transaction,$user, $admin));

        return $airtime_to_wallet_transaction;
    }

}