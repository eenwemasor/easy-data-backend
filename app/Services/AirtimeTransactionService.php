<?php


namespace App\Services;


use App\Enums\TransactionStatus;
use App\Events\AirtimeTransactionEvent;
use App\Http\Controllers\UserController;
use App\Repositories\AirtimeTransactionRepository;

class AirtimeTransactionService
{
    /**
     * @var AirtimeTransactionRepository
     */
    private $airtime_transaction_repository;
    /**
     * @var WalletTransactionService
     */
    private $walletTransactionService;

    /**
     * AirtimeTransactionService constructor.
     * @param AirtimeTransactionRepository $airtime_transaction_repository
     * @param WalletTransactionService $walletTransactionService
     */
    public function __construct(AirtimeTransactionRepository $airtime_transaction_repository, WalletTransactionService $walletTransactionService)
    {
        $this->airtime_transaction_repository = $airtime_transaction_repository;
        $this->walletTransactionService = $walletTransactionService;
    }


    /**
     * @param array $airtimeTransaction
     * @return \App\AirtimeTransaction
     */
    public function create(array  $airtimeTransaction )
    {

        $data = collect($airtimeTransaction);

        $walletTransactionData = $data->only([
            'transaction_type',
            'description',
            'amount',
            'beneficiary',
            'user_id',
        ])->toArray();


       $walletTransactionResult =  $this->walletTransactionService->create($walletTransactionData);
        $airtimeData = $data->only([
            'phone',
        ])->toArray();


        $wallet_result = collect($walletTransactionResult);
        $airtimeData['method'] =$walletTransactionResult['wallet'];
        $wallet_result['status'] =TransactionStatus::SENT;
        $airtimeTransactionData = array_merge(
            $wallet_result->except([
            'transaction_type',
            'description',
                'status'
        ])->toArray(), $airtimeData);


        $airtime_transaction = $this->airtime_transaction_repository->create($airtimeTransactionData);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($airtimeTransaction["user_id"]);
        $admin = $user_cont->getAdmin();


        event(new AirtimeTransactionEvent($airtime_transaction,$user, $admin));

        return $airtime_transaction;
    }
}