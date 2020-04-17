<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:20
 */

namespace App\Services;


use App\AirtimeToCashTransaction;
use App\Enums\TransactionStatus;
use App\Events\AirtimeToCashTransactionEvent;
use App\GraphQL\Errors\GraphqlError;
use App\Http\Controllers\UserController;
use App\Repositories\AirtimeToCashTransactionRepository;
use App\User;

class AirtimeToCashTransactionService
{
    /**
     * @var AirtimeToCashTransactionRepository
     */
    private $airtime_to_cash_transaction_repository;
    /**
     * @var WalletTransactionService
     */
    private $walletTransactionService;

    /**
     * AirtimeToCashTransactionService constructor.
     * @param AirtimeToCashTransactionRepository $airtime_to_cash_transaction_repository
     */
    function __construct(AirtimeToCashTransactionRepository $airtime_to_cash_transaction_repository, WalletTransactionService $walletTransactionService)
    {
        $this->airtime_to_cash_transaction_repository = $airtime_to_cash_transaction_repository;


        $this->walletTransactionService = $walletTransactionService;
    }



    /**
     * @param array $airtimeToCashTransaction
     * @return AirtimeToCashTransaction
     * @throws
     */
    public function create(array  $airtimeToCashTransaction )
    {
        $user = User::find($airtimeToCashTransaction["user_id"]);
        if(!$user->active){
            throw new GraphqlError("Account not activated, please fund your wallet or pay our one time activation fee to continue.");
        }

        $data = collect($airtimeToCashTransaction);

        $walletTransactionData = $data->only([
            'transaction_type',
            'description',
            'amount',
            'beneficiary',
            'user_id',
        ])->toArray();


        $walletTransactionResult =  $this->walletTransactionService->create($walletTransactionData);
        $airtimeToCashData = $data->only([
            'phone',
            'network',
            'sender_phone',
            'recipient_phone'
        ])->toArray();

        $wallet_result = collect($walletTransactionResult);
        $wallet_result['status'] =TransactionStatus::SENT;
        $airtimeToCashTransactionData = array_merge(
            $wallet_result->except([
                'transaction_type',
                'description'
            ])->toArray(), $airtimeToCashData);
        
        
        $airtime_to_cash_transaction = $this->airtime_to_cash_transaction_repository->create($airtimeToCashTransactionData);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($airtimeToCashTransaction["user_id"]);
        $admin = $user_cont->getAdmin();



        event(new AirtimeToCashTransactionEvent($airtime_to_cash_transaction,$user, $admin));

        return $airtime_to_cash_transaction;
    }



}