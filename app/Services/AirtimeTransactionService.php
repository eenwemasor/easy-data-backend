<?php


namespace App\Services;


use App\AirtimeTransaction;
use App\Enums\NetworkType;
use App\Enums\TransactionStatus;
use App\Enums\WalletType;
use App\Events\AirtimeTransactionEvent;
use App\GraphQL\Errors\GraphqlError;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Wallet;
use App\Repositories\AirtimeTransactionRepository;
use App\Repositories\APIRequests\AirtimeAPIRequests;
use App\Repositories\APIRequests\ValidateTransactions;
use App\User;
use App\WalletTransaction;

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
     * @var ValidateTransactions
     */
    private $validateTransactions;
    /**
     * @var airtimeAPIRequests
     */
    private $airtimeAPIRequests;

    /**
     * AirtimeTransactionService constructor.
     * @param AirtimeTransactionRepository $airtime_transaction_repository
     * @param WalletTransactionService $walletTransactionService
     * @param ValidateTransactions $validateTransactions
     * @param airtimeAPIRequests $airtimeAPIRequests
     */
    public function __construct(AirtimeTransactionRepository $airtime_transaction_repository, WalletTransactionService $walletTransactionService, ValidateTransactions $validateTransactions, AirtimeAPIRequests $airtimeAPIRequests)
    {
        $this->airtime_transaction_repository = $airtime_transaction_repository;
        $this->walletTransactionService = $walletTransactionService;
        $this->validateTransactions = $validateTransactions;
        $this->airtimeAPIRequests = $airtimeAPIRequests;
    }

    /**
     * @param array $airtimeTransaction
     * @return \App\AirtimeTransaction
     * @throws
     */
    public function create(array $airtimeTransaction)
    {
        $api_wallet = $this->validateTransactions->get_api_account_info();
        if($api_wallet < $airtimeTransaction['amount']){
            throw new GraphqlError("Service is not available currently, please try again later");
        }


        $user = User::find($airtimeTransaction["user_id"]);
        if (!$user->active) {
            throw new GraphqlError("Account not activated, please fund your wallet or pay our one time activation fee to continue.");
        }

        $data = collect($airtimeTransaction);
        $wallet = new Wallet();
        $discounted_amounted = $wallet->apply_discount($airtimeTransaction['amount'], $airtimeTransaction['network']);

        $walletTransactionData = $data->only(['transaction_type', 'description', 'beneficiary', 'user_id'])->toArray();
        $walletTransactionData['description'] = $airtimeTransaction['amount']." ".$airtimeTransaction['network']." airtime purchase";
        $walletTransactionData['amount'] = $discounted_amounted;


        if (false) {
            throw new GraphqlError("Service unavailable, please try again later");
        } else {

            $walletTransactionResult = $this->walletTransactionService->create($walletTransactionData);
            $airtimeData = $data->only(['phone',])->toArray();

            $initiateTransaction = $this->airtimeAPIRequests->initiate_airtime_transaction(
                [
                    'amount' => $airtimeTransaction['amount'],
                    'network' =>$airtimeTransaction['network'],
                    'phone' => $airtimeTransaction['phone']
                ]
            );

            if ($initiateTransaction == "successful") {
                $wallet_result = collect($walletTransactionResult);
                $airtimeData['method'] = $walletTransactionResult['wallet'];
                $airtimeData['status'] = TransactionStatus::COMPLETED;
                $airtimeData['network'] = $airtimeTransaction['network'];
                $airtimeTransactionData = array_merge($wallet_result->except(['transaction_type', 'description', 'status'])->toArray(), $airtimeData);

                $airtime_transaction = $this->airtime_transaction_repository->create($airtimeTransactionData);
                $user_cont = New UserController();
                $user = $user_cont->getUserById($airtimeTransaction["user_id"]);
                $admin = $user_cont->getAdmin();

                event(new AirtimeTransactionEvent($airtime_transaction, $user, $admin));

                return $airtime_transaction;
            } else {
                $wallet_result = collect($walletTransactionResult);
                $airtimeData['method'] = $walletTransactionResult['wallet'];
                $airtimeData['status'] = TransactionStatus::FAILED;
                $airtimeData['network'] = $airtimeTransaction['network'];
                $airtimeTransactionData = array_merge($wallet_result->except(['transaction_type', 'description', 'status'])->toArray(), $airtimeData);
                $this->airtime_transaction_repository->create($airtimeTransactionData);

                $user = User::find($airtimeTransaction["user_id"]);
                if ($walletTransactionResult['wallet'] == WalletType::WALLET) {
                    $user->wallet = $user->wallet + $discounted_amounted;
                } else {
                    $user->bonus_wallet = $user->bonus_wallet + $discounted_amounted;
                }
                $user->save();

                $wallet_transaction = WalletTransaction::find($walletTransactionResult['id']);
                $wallet_transaction->status = TransactionStatus::FAILED;
                $wallet_transaction->save();

                throw new GraphqlError("Transaction failed, please try again");
            }
        }


    }



        static public function total_transaction_statistics($from, $to){
            $total_glo_airtime = AirtimeTransaction::where('network',NetworkType::GLO)->whereBetween('created_at', [$from, $to]);
            $total_etisalat_airtime = AirtimeTransaction::where('network',NetworkType::NINE_MOBILE)->whereBetween('created_at', [$from, $to]);
            $total_airtel_airtime = AirtimeTransaction::where('network',NetworkType::AIRTEL)->whereBetween('created_at', [$from, $to]);
            $total_mtn_airtime = AirtimeTransaction::where('network',NetworkType::MTN)->whereBetween('created_at', [$from, $to]);

            $total_airtime_order = AirtimeTransaction::whereBetween('created_at', [$from, $to]);
            $airtime_failed_order = AirtimeTransaction::whereBetween('created_at', [$from, $to])->where('status',TransactionStatus::FAILED);
            $airtime_completed_order = AirtimeTransaction::whereBetween('created_at', [$from, $to])->where('status',TransactionStatus::COMPLETED);
            $airtime_processing_order = AirtimeTransaction::whereBetween('created_at', [$from, $to])->where('status',TransactionStatus::PROCESSING);


        return [
            'total_mtn_airtime_order'=>$total_mtn_airtime->count(),
            'total_etisalat_airtime_order'=>$total_etisalat_airtime->count(),
            'total_airtel_airtime_order'=>$total_airtel_airtime->count(),
            'total_glo_airtime_order'=>$total_glo_airtime->count(),
            'total_airtime_order'=>$total_airtime_order->count(),
            'airtime_failed_order'=>$airtime_failed_order->count(),
            'airtime_completed_order'=>$airtime_completed_order->count(),
            'airtime_processing_order'=>$airtime_processing_order->count(),
            'total_mtn_airtime_order_sum'=>StatisticsService::sum_transaction($total_mtn_airtime->get()),
            'total_etisalat_airtime_order_sum'=> StatisticsService::sum_transaction($total_etisalat_airtime->get()),
            'total_glo_airtime_order_sum'=>StatisticsService::sum_transaction($total_glo_airtime->get()),
            'total_airtel_airtime_order_sum'=>StatisticsService::sum_transaction($total_airtel_airtime->get()),
            'total_airtime_order_sum'=>StatisticsService::sum_transaction($total_glo_airtime->get()),
            'airtime_failed_order_sum'=>StatisticsService::sum_transaction($total_glo_airtime->get()),
'airtime_completed_order_sum'=>StatisticsService::sum_transaction($total_glo_airtime->get()),
'airtime_processing_order_sum'=>StatisticsService::sum_transaction($total_glo_airtime->get()),
        ];
    }


}