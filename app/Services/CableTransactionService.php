<?php


namespace App\Services;


use App\CablePlanList;
use App\CableTransaction;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\WalletType;
use App\GraphQL\Errors\GraphqlError;
use App\Http\Controllers\UserController;
use App\Repositories\CableTransactionRepository;
use App\User;
use App\Vendors\Ringo\RingoCable;
use App\WalletTransaction;

class CableTransactionService
{
    /**
     * @var CableTransactionRepository
     */
    private $cableTransactionRepository;
    /**
     * @var WalletTransactionService
     */
    private $walletTransactionService;
    /**
     * @var RingoCable
     */
    private $ringoCable;

    /**
     * CableTransactionService constructor.
     * @param CableTransactionRepository $cableTransactionRepository
     * @param WalletTransactionService $walletTransactionService
     * @param RingoCable $ringoCable
     */
    public function __construct(
        CableTransactionRepository $cableTransactionRepository,
        WalletTransactionService $walletTransactionService,
        RingoCable $ringoCable
    )
    {
        $this->cableTransactionRepository = $cableTransactionRepository;
        $this->walletTransactionService = $walletTransactionService;
        $this->ringoCable = $ringoCable;
    }


    /**
     * @param array $cableTransaction
     * @return \App\CableTransaction
     * @throws
     */
    public function create(array $cableTransaction)
    {
        $user_cont = new UserController();
        $data = collect($cableTransaction);
        $cable_plan = CablePlanList::find($data['plan']);


//        $api_wallet = $this->validateTransactions->get_api_account_info();
//        if($api_wallet->balance < $cable_plan->amount){
//            throw new GraphqlError("Service is not available currently, please try again later");
//        }


//        $available_services = $this->validateTransactions->get_available_services("Tv");
//
//        $this->checkAvailableService($available_services,$cable_plan->cable);

        $user = User::find($cableTransaction["user_id"]);

        $walletTransactionData = $data->only(['transaction_type', 'description', 'user_id',])->toArray();
        $walletTransactionData['beneficiary'] = $cableTransaction['beneficiary_name'];
        $walletTransactionData['description'] = $cable_plan->amount . " " . $cable_plan->plan . " " . $cable_plan->cable . "cable tv subscription";
        $walletTransactionData['amount'] = $cable_plan->amount;


        $walletTransactionResult = $this->walletTransactionService->create($walletTransactionData);
        $cableData = $data->only(['decoder_number', 'beneficiary_name',])->toArray();
        $cableData['decoder'] = $cable_plan->cable;
        $cableData['plan'] = $cable_plan->plan;

        $wallet_result = collect($walletTransactionResult);
        $cableData['method'] = $walletTransactionResult['wallet'];
        $wallet_result['status'] = TransactionStatus::SENT;
        $cableTransactionData = array_merge($wallet_result->except(['transaction_type', 'description', 'beneficiary', 'status'])->toArray(), $cableData);
        $cableTransactionData['plan'] = $cable_plan->id;

        $initiateCableTransaction = $this->ringoCable->initiate_cable_transaction(['type' => $cable_plan->cable, 'smartCardNo' => $cableTransaction['decoder_number'], 'name' => $cable_plan->plan, 'code' => $cable_plan->product_code, 'request_id' => $walletTransactionResult['reference'],

        ], $cable_plan->amount);

        if (str_lower($initiateCableTransaction->smessage) == "successful" && $initiateCableTransaction->status == "200") {
            $cableTransactionData['status'] = TransactionStatus::COMPLETED;
            $cableTransactionResult = $this->cableTransactionRepository->create($cableTransactionData);

//            $user = $user_cont->getUserById($cableTransaction["user_id"]);
//            $admin = $user_cont->getAdmin();
//            event(new CableTransactionEvent($cableTransactionResult, $user, $admin));
            return $cableTransactionResult;
        } else {
            $cableTransactionData['status'] = TransactionStatus::FAILED;
            $this->cableTransactionRepository->create($cableTransactionData);

            $user = $user_cont->getUserById($cableTransaction["user_id"]);
            if ($walletTransactionResult['wallet'] == WalletType::WALLET) {
                $user->wallet = $user->wallet + $cable_plan->amount;
            } else {
                $user->bonus_wallet = $user->bonus_wallet + $cable_plan->amount;
            }
            $user->save();

            $wallet_transaction = WalletTransaction::find($walletTransactionResult['id']);
            $wallet_transaction->status = TransactionStatus::FAILED;
            $wallet_transaction->save();

            throw new GraphqlError($initiateCableTransaction->message);


        }


    }


    /**
     * @param string $transaction_id
     * @return  CableTransaction
     */
    public function mark_transaction_successful(string $transaction_id)
    {
        return $this->cableTransactionRepository->mark_transaction_successful($transaction_id);
    }

    /**
     * @param string $transaction_id
     * @return CableTransaction|\Illuminate\Support\Collection
     * @throws GraphqlError
     */
    public function mark_transaction_failed(string $transaction_id)
    {
        $cableTransaction = collect(CableTransaction::find($transaction_id));


        if ($cableTransaction->status === TransactionStatus::FAILED) {
            return $cableTransaction;
        }

        $walletTransactionData = $cableTransaction->only(['amount', 'user_id',])->toArray();
        $walletTransactionData['beneficiary'] = $cableTransaction['beneficiary_name'];
        $walletTransactionData['transaction_type'] = TransactionType::CREDIT;
        $walletTransactionData['description'] = "Unable to process your mobileNGCable Transaction request";
        $walletTransactionData['reference'] = $cableTransaction['reference'];
        $this->walletTransactionService->create($walletTransactionData);
        return $this->cableTransactionRepository->mark_transaction_failed($transaction_id);
    }


    static public function total_transaction_statistics($from, $to)
    {
        $total_cable = CableTransaction::whereBetween('created_at', [$from, $to]);
        $completed_order = CableTransaction::whereBetween('created_at', [$from, $to])->where('status', TransactionStatus::COMPLETED);
        $processing_order = CableTransaction::whereBetween('created_at', [$from, $to])->where('status', TransactionStatus::PROCESSING);
        $failed_order = CableTransaction::whereBetween('created_at', [$from, $to])->where('status', TransactionStatus::FAILED);


        return [
            'total_cable_order' => $total_cable->count(),
            'total_cable_completed_order' => $completed_order->count(),
            'total_cable_processing_order' => $processing_order->count(),
            'total_cable_failed_order' => $failed_order->count(),

            'total_cable_order_sum' => StatisticsService::sum_transaction($total_cable->get()),
            'total_cable_completed_order_sum' => StatisticsService::sum_transaction($completed_order->get()),
            'total_cable_processing_order_sum' => StatisticsService::sum_transaction($processing_order->get()),
            'total_cable_failed_order_sum' => StatisticsService::sum_transaction($failed_order->get())

        ];
    }


}
