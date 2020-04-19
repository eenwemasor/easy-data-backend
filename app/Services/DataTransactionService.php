<?php


namespace App\Services;


use App\AdminChannelUtil;
use App\DataPlanList;
use App\DataTransaction;
use App\Enums\NetworkType;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\WalletType;
use App\Events\DataTransactionEvent;
use App\GraphQL\Errors\GraphqlError;
use App\Http\Controllers\SendSMSController;
use App\Http\Controllers\UserController;
use App\Repositories\APIRequests\DataAPIRequests;
use App\Repositories\APIRequests\ValidateTransactions;
use App\Repositories\DataTransactionRepository;
use App\User;
use App\WalletTransaction;

class DataTransactionService
{
    /**
     * @var DataTransactionRepository
     */
    private $data_transaction_repository;
    /**
     * @var WalletTransactionService
     */
    private $walletTransactionService;
    /**
     * @var ValidateTransactions
     */
    private $validateTransactions;
    /**
     * @var DataAPIRequests
     */
    private $dataAPIRequests;

    /**
     * DataTransactionService constructor.
     * @param DataTransactionRepository $data_transaction_repository
     * @param WalletTransactionService $walletTransactionService
     * @param ValidateTransactions $validateTransactions
     * @param DataAPIRequests $dataAPIRequests
     */
    public function __construct(
        DataTransactionRepository $data_transaction_repository,
        WalletTransactionService $walletTransactionService,
        ValidateTransactions $validateTransactions,
        DataAPIRequests $dataAPIRequests
    )
    {
        $this->data_transaction_repository = $data_transaction_repository;
        $this->walletTransactionService = $walletTransactionService;
        $this->validateTransactions = $validateTransactions;
        $this->dataAPIRequests = $dataAPIRequests;
    }

    /**
     * @param array $dataTransaction
     * @return \App\DataTransaction
     * @throws
     */
    public function create(array $dataTransaction)
    {
        $data_plan = DataPlanList::find($dataTransaction['data']);
        $api_wallet = $this->validateTransactions->get_api_account_info();

        $user = User::find($dataTransaction["user_id"]);
        if (!$user->active) {
            throw new GraphqlError("Account not activated, please fund your wallet or pay our one time activation fee to continue.");
        }

        if($api_wallet < $data_plan->amount){
            throw new GraphqlError("Service is not available currently, please try again later");
        }



        $data = collect($dataTransaction);


        $walletTransactionData = $data->only(['transaction_type', 'description', 'amount', 'beneficiary', 'user_id',])->toArray();
        $walletTransactionData['description'] = $data_plan->amount ." ". $data_plan->plan ." ".$data_plan->network." data purchase";
        $walletTransactionData['amount'] = $data_plan->amount;

        $walletTransactionResult = $this->walletTransactionService->create($walletTransactionData);
        $dataData['network'] = $data_plan->network;
        $dataData['method'] = $walletTransactionResult['wallet'];
        $dataData['data'] = $data_plan->plan;
        $wallet_result = collect($walletTransactionResult);

        $initiate_data_transaction = $this->dataAPIRequests->InitiateDataTransaction([
            'network' =>$data_plan->network,
            'phone' => $dataTransaction['beneficiary'],
            'plan' => $data_plan->id,
        ]);


        var_dump($initiate_data_transaction);

        if($initiate_data_transaction === "successful"){
            $dataData['status'] = TransactionStatus::PROCESSING;
            $dataTransactionData = array_merge($wallet_result->except(['transaction_type', 'description', 'status'])->toArray(), $dataData);

            $data_transaction = $this->data_transaction_repository->create($dataTransactionData);

            $user_cont = New UserController();
            $user = $user_cont->getUserById($dataTransaction["user_id"]);
            $admin = $user_cont->getAdmin();

            event(new DataTransactionEvent($data_transaction, $user, $admin));

            return $data_transaction;
        }else{
            $user = User::find($dataTransaction["user_id"]);
            if ($walletTransactionResult['wallet'] == WalletType::WALLET) {
                $user->wallet = $user->wallet + $data_plan->amount;
            } else {
                $user->bonus_wallet = $user->bonus_wallet + $data_plan->amount;
            }
            $user->save();

            $wallet_transaction = WalletTransaction::find($walletTransactionResult['id']);
            $wallet_transaction->status = TransactionStatus::FAILED;
            $wallet_transaction->save();

            throw new GraphqlError("Transaction failed, please try again");
        }








    }

    /**
     * @param string $transaction_id
     * @return DataTransaction
     */
    public function mark_transaction_successful(string $transaction_id)
    {
        return $this->data_transaction_repository->mark_transaction_successful($transaction_id);
    }

    /**
     * @param string $transaction_id
     * @return DataTransaction
     */
    public function mark_transaction_failed(string $transaction_id)
    {
        $dataTransaction = collect(DataTransaction::find($transaction_id));


        $walletTransactionData = $dataTransaction->only(['amount', 'user_id',])->toArray();
        $walletTransactionData['beneficiary'] = $dataTransaction['beneficiary'];
        $walletTransactionData['transaction_type'] = TransactionType::CREDIT;
        $walletTransactionData['description'] = "Unable to Process your Data Transaction Request";
        $walletTransactionData['reference'] = $dataTransaction['reference'];
        $this->walletTransactionService->create($walletTransactionData);
        return $this->data_transaction_repository->mark_transaction_failed($transaction_id);
    }



    static public function total_transaction_statistics($from, $to){
        $total_glo_data = DataTransaction::where('network',NetworkType::GLO)->whereBetween('created_at', [$from, $to]);
        $total_etisalat_data = DataTransaction::where('network',NetworkType::NINE_MOBILE)->whereBetween('created_at', [$from, $to]);
        $total_airtel_data = DataTransaction::where('network',NetworkType::AIRTEL)->whereBetween('created_at', [$from, $to]);
        $total_mtn_data = DataTransaction::where('network',NetworkType::MTN)->whereBetween('created_at', [$from, $to]);
        $total_data_order = DataTransaction::whereBetween('created_at', [$from, $to]);


        $data_failed_order = DataTransaction::whereBetween('created_at', [$from, $to])->where('status',TransactionStatus::FAILED);
        $data_completed_order = DataTransaction::whereBetween('created_at', [$from, $to])->where('status',TransactionStatus::COMPLETED);
        $data_processing_order = DataTransaction::whereBetween('created_at', [$from, $to])->where('status',TransactionStatus::PROCESSING);
        
        
        return [
            'total_mtn_data_order'=>$total_mtn_data->count(),
            'total_etisalat_data_order'=>$total_etisalat_data->count(),
            'total_airtel_data_order'=>$total_airtel_data->count(),
            'total_glo_data_order'=>$total_glo_data->count(),
            'total_data_order'=>$total_data_order->count(),
            'data_failed_order'=>$data_failed_order->count(),
            'data_completed_order'=>$data_completed_order->count(),
            'data_processing_order'=>$data_processing_order->count(),

            'total_mtn_data_order_sum'=>StatisticsService::sum_transaction($total_mtn_data->get()),
            'total_etisalat_data_order_sum'=>StatisticsService::sum_transaction($total_etisalat_data->get()),
            'total_airtel_data_order_sum'=>StatisticsService::sum_transaction($total_airtel_data->get()),
            'total_glo_data_order_sum'=>StatisticsService::sum_transaction($total_glo_data->get()),
            'total_data_order_sum'=>StatisticsService::sum_transaction($total_data_order->get()),
            'data_failed_order_sum'=>StatisticsService::sum_transaction($data_failed_order->get()),
            'data_completed_order_sum'=>StatisticsService::sum_transaction($data_completed_order->get()),
            'data_processing_order_sum'=>StatisticsService::sum_transaction($data_processing_order->get())
            ];
    }
    
    
}