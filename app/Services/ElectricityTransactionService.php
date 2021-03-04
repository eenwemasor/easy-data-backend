<?php


namespace App\Services;


use App\ElectricityTransaction;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\WalletType;
use App\GraphQL\Errors\GraphqlError;
use App\Http\Controllers\SendSMSController;
use App\PowerPlanList;
use App\Repositories\ElectricityTransactionRepository;
use App\User;
use App\Vendors\Ringo\RingoElectricity;
use App\WalletTransaction;

class ElectricityTransactionService
{
    /**
     * @var ElectricityTransactionRepository
     */
    private $electricityTransactionRepository;
    /**
     * @var WalletTransactionService
     */
    private $walletTransactionService;
    /**
     * @var ringoElectricity
     */
    private $ringoElectricity;

    /**
     * ElectricityTransactionService constructor.
     * @param ElectricityTransactionRepository $electricityTransactionRepository
     * @param WalletTransactionService $walletTransactionService
     * @param RingoElectricity $ringoElectricity
     */
    public function __construct(ElectricityTransactionRepository $electricityTransactionRepository, WalletTransactionService $walletTransactionService, RingoElectricity $ringoElectricity)
    {
        $this->electricityTransactionRepository = $electricityTransactionRepository;
        $this->walletTransactionService = $walletTransactionService;
        $this->ringoElectricity = $ringoElectricity;
    }


    /**
     * @param array $electricityTransaction
     * @return \App\ElectricityTransaction
     * @throws
     */
    public function create(array $electricityTransaction)
    {

        $plan = PowerPlanList::find($electricityTransaction['plan']);

//        $api_wallet = $this->validateTransactions->get_api_account_info();
//        if($api_wallet->balance < $electricityTransaction['amount']){
//            throw new GraphqlError("Service is not available currently, please try again later");
//        }

        $user = User::find($electricityTransaction["user_id"]);

//        $availableServices = $this->validateTransactions->get_available_services('ELECT');
//        $this->check_available_service($availableServices, $plan->disco."_".$electricityTransaction['type']);

        $data = collect($electricityTransaction);

        $walletTransactionData = $data->only(['transaction_type', 'description', 'amount', 'user_id',])->toArray();
        $walletTransactionData['description'] = $plan->description." ringoElectricity bill payment";
        $walletTransactionData['beneficiary'] = $electricityTransaction['beneficiary_name'];


        $walletTransactionResult = $this->walletTransactionService->create($walletTransactionData);
        $electricityData = $data->only(['meter_number', 'beneficiary_name',])->toArray();
        $electricityData['plan'] = $plan->description;

        $wallet_result = collect($walletTransactionResult);
        $electricityData['method'] = $walletTransactionResult['wallet'];
        $electricityTransactionData = array_merge($wallet_result->except(['transaction_type', 'description', 'beneficiary', 'status',

        ])->toArray(), $electricityData);
        $electricityTransactionData['plan'] = $plan->id;

        $initiateElectricityTransaction = $this->ringoElectricity->initiate_electricity_transaction(["disco" => $plan->disco, "meterNo" => $electricityTransaction['meter_number'], "type" => $electricityTransaction['type'], "amount" => $electricityTransaction['amount'], "phonenumber" => $user->phone, "request_id" => $walletTransactionResult['reference']

        ], $electricityTransaction['beneficiary_name']);

        $initiateElectricityTransaction = json_decode($initiateElectricityTransaction);
        if (str_lower($initiateElectricityTransaction->message) === "successful" && $initiateElectricityTransaction->status === "200") {
            $electricityTransactionData['status'] = TransactionStatus::COMPLETED;
            $electricityTransactionData['token'] = $initiateElectricityTransaction->token;
            $electricityTransactionResult = $this->electricityTransactionRepository->create($electricityTransactionData);
//            $user_cont = New UserController();
//            $user = $user_cont->getUserById($electricityTransaction["user_id"]);
//            $admin = $user_cont->getAdmin();
//            event(new ElectricityTransactionEvent($electricityTransactionResult, $user, $admin));

            $smsController = new SendSMSController();
            $message = "Thank you for patronizing Gtserviz: Here is your Electricity token ". $initiateElectricityTransaction->token;
            $smsController->sendSMS($message, $user->phone);
            return $electricityTransactionResult;
        } else {
            $electricityTransactionData['status'] = TransactionStatus::FAILED;
            $this->electricityTransactionRepository->create($electricityTransactionData);

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

            throw new GraphqlError($initiateElectricityTransaction->message);
        }

    }


    public function check_available_service($services, $service)
    {
        if ((!isset($services->Abuja) || $services->Abuja != "Available") && $service === "AEDC") {
            throw new GraphqlError("Abuja ringoElectricity service is currently not available");
        }
        if ((!isset($services->Eko) || $services->Eko != "Available") && $service === "EKEDC") {
            throw new GraphqlError("Eko ringoElectricity service is currently not available");
        }
        if ((!isset($services->Kaduna) || $services->Kaduna != "Available") && $service ==="KAEDC") {
            throw new GraphqlError("Kaduna ringoElectricity service is currently not available");
        }
        if ((!isset($services->Ibadan) || $services->Ibadan != "Available") && $service === "IBEDC") {
            throw new GraphqlError("Ibadan ringoElectricity service is currently not available");
        }
        if ((!isset($services->Kano) || $services->Kano != "Available") && $service === "KEDC") {
            throw new GraphqlError("Kano ringoElectricity service is currently not available");
        }
        if ((!isset($services->Ikeja) || $services->Ikeja != "Available") && $service === "IKEDC") {
            throw new GraphqlError("Ikeja ringoElectricity service is currently not available");
        }
        if ((!isset($services->portharcourt) || $services->portharcourt != "Available") && $service === "PHEDC") {
            throw new GraphqlError("Portharcourt ringoElectricity service is currently not available");
        }
        if ((!isset($services->Enugu) || $services->Enugu != "Available") && $service === "EEDC") {
            throw new GraphqlError("Enugu ringoElectricity service is currently not available");
        }
        if ((!isset($services->Jos) || $services->Jos != "Available") && $service === "JEDC") {
            throw new GraphqlError("Jos ringoElectricity service is currently not available");
        }
        if ((!isset($services->Benin) || $services->Benin != "Available") && $service === "BEDC") {
            throw new GraphqlError("Benin ringoElectricity service is currently not available");
        }
        if ((!isset($services->Yola) || $services->Yola != "Available") && $service === "YEDC") {
            throw new GraphqlError("Yola ringoElectricity service is currently not available");
        }
    }

    /**
     * @param string $transaction_id
     * @return ElectricityTransaction
     */
    public function mark_transaction_successful(string $transaction_id)
    {
        return $this->electricityTransactionRepository->mark_transaction_successful($transaction_id);
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

        return $this->electricityTransactionRepository->mark_transaction_failed($transaction_id);

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
