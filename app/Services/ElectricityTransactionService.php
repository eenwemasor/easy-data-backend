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
    public function __construct(
        ElectricityTransactionRepository $electricityTransactionRepository,
        WalletTransactionService $walletTransactionService,
        RingoElectricity $ringoElectricity)
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
        $powerPlan = PowerPlanList::find($electricityTransaction['plan']);
        $data = collect($electricityTransaction);
        $amount = $this->ringoElectricity->apply_discount($electricityTransaction, $electricityTransaction['amount']);
        $this->ringoElectricity->check_api_wallet($amount);

        $walletTransactionResult = $this->chargeUser($data, $electricityTransaction['beneficiary_name'], $amount);

        $electricityData = $data->only(['meter_number', 'beneficiary_name',])->toArray();
        $electricityData['plan'] = $powerPlan->description;

        $walletResult = collect($walletTransactionResult);
        $electricityData['method'] = $walletTransactionResult['wallet'];
        $electricityData['phone'] = $electricityTransaction['phone'];
        $electricityTransactionData = array_merge($walletResult->except(['transaction_type', 'description', 'beneficiary', 'status',

        ])->toArray(), $electricityData);
        $electricityTransactionData['plan'] = $powerPlan->id;

        $electricityTransactionResult = $this->ringoElectricity->purchase_electricity($electricityTransaction, $powerPlan, $walletTransactionResult['reference'], $amount);

        if ($electricityTransactionResult['success']) {
            $electricityTransactionData['status'] = TransactionStatus::COMPLETED;
            $electricityTransactionData['token'] = $electricityTransactionResult['token'];
            $electricityTransactionResult = $this->electricityTransactionRepository->create($electricityTransactionData);

            $this->sendElectricityToken($electricityTransactionResult['token'], $electricityTransaction['phone']);

            return $electricityTransactionResult;
        } else {
            $electricityTransactionData['status'] = TransactionStatus::FAILED;
            $this->electricityTransactionRepository->create($electricityTransactionData);

            $this->refundUser($amount, $walletTransactionResult, $electricityTransaction['user_id']);

            $wallet_transaction = WalletTransaction::find($walletTransactionResult['id']);
            $wallet_transaction->status = TransactionStatus::FAILED;
            $wallet_transaction->save();
            throw new GraphqlError($electricityTransactionResult['message']);
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
     * @return \Illuminate\Support\Collection
     */
    public function mark_transaction_failed(string $transaction_id)
    {
        $electricityTransaction = collect(ElectricityTransaction::find($transaction_id));

        if ($electricityTransaction->status === TransactionStatus::FAILED) {
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

    /**
     * @param $data
     * @param $beneficiary_name
     * @param $amount
     * @return WalletTransaction
     * @throws GraphqlError
     */
    private function chargeUser($data, $beneficiary_name, $amount)
    {
        $walletTransactionData = $data->only(['description', 'user_id',])->toArray();
        $walletTransactionData['amount'] = $amount;
        $walletTransactionData['transaction_type'] = TransactionType::DEBIT;
        $walletTransactionData['beneficiary'] = $beneficiary_name;
        return $this->walletTransactionService->create($walletTransactionData);
    }

    /**
     * @param $token
     * @param $phone
     */
    private function sendElectricityToken( $token, $phone)
    {
        $smsController = new SendSMSController();
        $message = "Thank you for patronizing Subpay: Here is your Electricity token " . $token;
        $smsController->sendSMS($message, $phone);
    }

    /**
     * @param $amount1
     * @param $walletTransactionResult
     */
    private function refundUser($amount1, $walletTransactionResult, $user_id)
    {
        $user = User::find($user_id);
        if ($walletTransactionResult['wallet'] === WalletType::WALLET) {
            $user->wallet = $user->wallet + $amount1;
        } else {
            $user->bonus_wallet = $user->bonus_wallet + $amount1;
        }
        $user->save();
    }


}
