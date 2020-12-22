<?php


namespace App\Services;


use App\ElectricityTransaction;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\WalletType;
use App\Events\ElectricityTransactionEvent;
use App\GraphQL\Errors\GraphqlError;
use App\Http\Controllers\SendSMSController;
use App\Http\Controllers\UserController;
use App\PowerPlanList;
use App\Repositories\APIRequests\ElectricityAPIRequests;
use App\Repositories\APIRequests\ValidateTransactions;
use App\Repositories\ElectricityTransactionRepository;
use App\User;
use App\WalletTransaction;

class ElectricityTransactionService
{
    /**
     * @var ElectricityTransactionRepository
     */
    private $electricity_transaction_repository;
    /**
     * @var WalletTransactionService
     */
    private $walletTransactionService;
    /**
     * @var ValidateTransactions
     */
    private $validateTransactions;
    /**
     * @var ElectricityAPIRequests
     */
    private $electricityAPIRequests;

    /**
     * ElectricityTransactionService constructor.
     * @param ElectricityTransactionRepository $electricity_transaction_repository
     * @param WalletTransactionService $walletTransactionService
     * @param ValidateTransactions $validateTransactions
     * @param ElectricityAPIRequests $electricityAPIRequests
     */
    public function __construct(ElectricityTransactionRepository $electricity_transaction_repository, WalletTransactionService $walletTransactionService, ValidateTransactions $validateTransactions, ElectricityAPIRequests $electricityAPIRequests)
    {
        $this->electricity_transaction_repository = $electricity_transaction_repository;
        $this->walletTransactionService = $walletTransactionService;
        $this->validateTransactions = $validateTransactions;
        $this->electricityAPIRequests = $electricityAPIRequests;
    }


    /**
     * @param array $electricityTransaction
     * @return \App\ElectricityTransaction
     * @throws
     */
    public function create(array $electricityTransaction)
    {

        $plan = PowerPlanList::find($electricityTransaction['plan']);

        $api_wallet = $this->validateTransactions->get_api_account_info();
        if($api_wallet->balance < $electricityTransaction['amount']){
            throw new GraphqlError("Service is not available currently, please try again later");
        }

        $user = User::find($electricityTransaction["user_id"]);

        if (!$user->active) {
            throw new GraphqlError("Account not activated, please fund your wallet or pay our one time activation fee to continue.");
        }

        $available_services = $this->validateTransactions->get_available_services('ELECT');
        $this->checkAvailableService($available_services, $plan->disco."_".$electricityTransaction['type']);

        $data = collect($electricityTransaction);

        $walletTransactionData = $data->only(['transaction_type', 'description', 'amount', 'user_id',])->toArray();
        $walletTransactionData['description'] = $plan->description." electricity bill payment";
        $walletTransactionData['beneficiary'] = $electricityTransaction['beneficiary_name'];


        $walletTransactionResult = $this->walletTransactionService->create($walletTransactionData);
        $electricityData = $data->only(['meter_number', 'beneficiary_name',])->toArray();
        $electricityData['plan'] = $plan->description;

        $wallet_result = collect($walletTransactionResult);
        $electricityData['method'] = $walletTransactionResult['wallet'];
        $electricityTransactionData = array_merge($wallet_result->except(['transaction_type', 'description', 'beneficiary', 'status',

        ])->toArray(), $electricityData);
        $electricityTransactionData['plan'] = $plan->id;

        $initiate_electricity_transaction = $this->electricityAPIRequests->initiate_electricity_transaction(["disco" => $plan->disco, "meterNo" => $electricityTransaction['meter_number'], "type" => $electricityTransaction['type'], "amount" => $electricityTransaction['amount'], "phonenumber" => $user->phone, "request_id" => $walletTransactionResult['reference']

        ], $electricityTransaction['beneficiary_name']);

        $initiate_electricity_transaction = json_decode($initiate_electricity_transaction);
        if (str_lower($initiate_electricity_transaction->message) === "successful" && $initiate_electricity_transaction->status === "200") {
            $electricityTransactionData['status'] = TransactionStatus::COMPLETED;
            $electricityTransactionData['token'] = $initiate_electricity_transaction->token;
            $electricity_transaction = $this->electricity_transaction_repository->create($electricityTransactionData);
//            $user_cont = New UserController();
//            $user = $user_cont->getUserById($electricityTransaction["user_id"]);
//            $admin = $user_cont->getAdmin();
//            event(new ElectricityTransactionEvent($electricity_transaction, $user, $admin));

            $smsController = new SendSMSController();
            $message = "Thank you for patronizing Gtserviz: Here is your Electricity token ". $initiate_electricity_transaction->token;
            $smsController->sendSMS($message, $user->phone);
            return $electricity_transaction;
        } else {
            $electricityTransactionData['status'] = TransactionStatus::FAILED;
            $this->electricity_transaction_repository->create($electricityTransactionData);

            $user = User::find($electricityTransaction["user_id"]);
            if ($walletTransactionResult['wallet'] === WalletType::WALLET) {
                $user->wallet = $user->wallet + $electricityTransaction['amount'];
            } else {
                $user->bonus_wallet = $user->bonus_wallet + $electricityTransaction['amount'];
            }
            $user->save();

            $wallet_transaction = WalletTransaction::find($walletTransactionResult['id']);
            $wallet_transaction->status = TransactionStatus::FAILED;
            $wallet_transaction->save();

            throw new GraphqlError($initiate_electricity_transaction->message);


        }

    }


    public function checkAvailableService($services, $service)
    {
        if ((!isset($services->Abuja) || $services->Abuja != "Available") && $service === "AEDC") {
            throw new GraphqlError("Abuja electricity service is currently not available");
        }
        if ((!isset($services->Eko) || $services->Eko != "Available") && $service === "EKEDC") {
            throw new GraphqlError("Eko electricity service is currently not available");
        }
        if ((!isset($services->Kaduna) || $services->Kaduna != "Available") && $service ==="KAEDC") {
            throw new GraphqlError("Kaduna electricity service is currently not available");
        }
        if ((!isset($services->Ibadan) || $services->Ibadan != "Available") && $service === "IBEDC") {
            throw new GraphqlError("Ibadan electricity service is currently not available");
        }
        if ((!isset($services->Kano) || $services->Kano != "Available") && $service === "KEDC") {
            throw new GraphqlError("Kano electricity service is currently not available");
        }
        if ((!isset($services->Ikeja) || $services->Ikeja != "Available") && $service === "IKEDC") {
            throw new GraphqlError("Ikeja electricity service is currently not available");
        }
        if ((!isset($services->portharcourt) || $services->portharcourt != "Available") && $service === "PHEDC") {
            throw new GraphqlError("Portharcourt electricity service is currently not available");
        }
        if ((!isset($services->Enugu) || $services->Enugu != "Available") && $service === "EEDC") {
            throw new GraphqlError("Enugu electricity service is currently not available");
        }
        if ((!isset($services->Jos) || $services->Jos != "Available") && $service === "JEDC") {
            throw new GraphqlError("Jos electricity service is currently not available");
        }
        if ((!isset($services->Benin) || $services->Benin != "Available") && $service === "BEDC") {
            throw new GraphqlError("Benin electricity service is currently not available");
        }
        if ((!isset($services->Yola) || $services->Yola != "Available") && $service === "YEDC") {
            throw new GraphqlError("Yola electricity service is currently not available");
        }
    }

    /**
     * @param string $transaction_id
     * @return ElectricityTransaction
     */
    public function mark_transaction_successful(string $transaction_id)
    {

        return $this->electricity_transaction_repository->mark_transaction_successful($transaction_id);
    }

    /**
     * @param string $transaction_id
     * @return ElectricityTransaction
     */
    public function mark_transaction_failed(string $transaction_id)
    {
        $electricityTransaction = collect(ElectricityTransaction::find($transaction_id));

        if($electricityTransaction->status === TransactionStatus::FAILED){
            return $electricityTransaction;
        }

        $walletTransactionData = $electricityTransaction->only(['amount', 'user_id',])->toArray();
        $walletTransactionData['beneficiary'] = $electricityTransaction['beneficiary_name'];
        $walletTransactionData['transaction_type'] = TransactionType::CREDIT;
        $walletTransactionData['description'] = "Unable to Process your Electricity Transaction Request";
        $walletTransactionData['reference'] = $electricityTransaction['reference'];
        $this->walletTransactionService->create($walletTransactionData);

        return $this->electricity_transaction_repository->mark_transaction_failed($transaction_id);

    }


    static public function total_transaction_statistics($from, $to)
    {
        $total_power = ElectricityTransaction::whereBetween('created_at', [$from, $to]);
        $completed_order = ElectricityTransaction::whereBetween('created_at', [$from, $to])->where('status', TransactionStatus::COMPLETED);
        $processing_order = ElectricityTransaction::whereBetween('created_at', [$from, $to])->where('status', TransactionStatus::PROCESSING);
        $failed_order = ElectricityTransaction::whereBetween('created_at', [$from, $to])->where('status', TransactionStatus::FAILED);

        return [
            'total_power_order' => $total_power->count(),
            'total_power_completed_order' => $completed_order->count(),
            'total_power_processing_order' => $processing_order->count(),
            'total_power_failed_order' => $failed_order->count(),


            'total_power_order_sum' => StatisticsService::sum_transaction($total_power->get()),
            'total_power_completed_order_sum' => StatisticsService::sum_transaction($completed_order->get()),
            'total_power_processing_order_sum' => StatisticsService::sum_transaction($processing_order->get()),
            'total_power_failed_order_sum' => StatisticsService::sum_transaction($failed_order->get()),
        ];
    }



}
