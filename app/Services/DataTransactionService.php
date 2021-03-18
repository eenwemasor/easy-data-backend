<?php


namespace App\Services;


use App\DataPlanList;
use App\DataTransaction;
use App\Enums\DataType;
use App\Enums\NetworkType;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\WalletType;
use App\GraphQL\Errors\GraphqlError;
use App\Repositories\DataTransactionRepository;
use App\User;
use App\Vendors\DailyEarnPro\DailyEarnProData;
use App\Vendors\Ringo\RingoData;
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
     * @var DailyEarnProData
     */
    private $dailyEarnProData;
    /**
     * @var RingoData
     */
    private $ringoData;

    /**
     * DataTransactionService constructor.
     * @param DataTransactionRepository $data_transaction_repository
     * @param DailyEarnProData $dailyEarnProData
     * @param RingoData $ringoData
     * @param WalletTransactionService $walletTransactionService
     */
    public function __construct(
        DataTransactionRepository $data_transaction_repository,
        DailyEarnProData $dailyEarnProData,
        RingoData $ringoData,
        WalletTransactionService $walletTransactionService
    )
    {
        $this->data_transaction_repository = $data_transaction_repository;
        $this->walletTransactionService = $walletTransactionService;
        $this->dailyEarnProData = $dailyEarnProData;
        $this->ringoData = $ringoData;
    }

    /**
     * @param array $dataTransaction
     * @return \App\DataTransaction
     * @throws
     */
    public function create(array $dataTransaction)
    {
        $dataPlan = DataPlanList::find($dataTransaction['data']);
        $vendor = null;

        if ($dataPlan->type === DataType::SME) {
            $vendor = $this->dailyEarnProData;
        } else {
            $vendor = $this->ringoData;
        }

        $vendor->check_api_wallet($dataPlan->amount);
        $amount = $vendor->apply_discount($dataTransaction, $dataPlan);
        $walletTransactionResult = $this->chargeUser($dataTransaction, $dataPlan, $amount);

        $dataPurchaseResponse = $vendor->purchase_data($dataTransaction, $walletTransactionResult['reference'], $dataPlan, 'transaction_successful', 'transaction_failed');

        $walletTransactionCollection = collect($walletTransactionResult);
        $dataData = [
            'network' => $dataPlan->network,
            'method' => $walletTransactionResult['wallet'],
            'data' => $dataPlan->plan
        ];

        if ($dataPurchaseResponse['success']) {
            $dataData['status'] = TransactionStatus::COMPLETED;
        } else {
            $dataData['status'] = TransactionStatus::FAILED;
            $airtimeTransactionData = array_merge($walletTransactionCollection->except(['transaction_type', 'description', 'status'])->toArray(), $dataData);
            $this->data_transaction_repository->create($airtimeTransactionData);

            $user = User::find($dataTransaction["user_id"]);
            if ($walletTransactionResult['wallet'] == WalletType::WALLET) {
                $user->wallet = $user->wallet + $amount;
            } else {
                $user->bonus_wallet = $user->bonus_wallet + $amount;
            }
            $user->save();
            $wallet_transaction = WalletTransaction::find($walletTransactionResult['id']);
            $wallet_transaction->status = TransactionStatus::FAILED;
            $wallet_transaction->save();
            throw new GraphqlError($dataPurchaseResponse['message']);
        }

        $airtimeTransactionData = array_merge($walletTransactionCollection->except(['transaction_type', 'description', 'status'])->toArray(), $dataData);
        return $this->data_transaction_repository->create($airtimeTransactionData);
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


        if ($dataTransaction->status === TransactionStatus::FAILED) {
            return $dataTransaction;
        }

        $walletTransactionData = $dataTransaction->only(['amount', 'user_id',])->toArray();
        $walletTransactionData['beneficiary'] = $dataTransaction['beneficiary'];
        $walletTransactionData['transaction_type'] = TransactionType::CREDIT;
        $walletTransactionData['description'] = "Unable to Process your Data Transaction Request";
        $walletTransactionData['reference'] = $dataTransaction['reference'];
        $this->walletTransactionService->create($walletTransactionData);
        return $this->data_transaction_repository->mark_transaction_failed($transaction_id);
    }


    static public function total_transaction_statistics($from, $to)
    {
        $total_glo_data = DataTransaction::where('network', NetworkType::GLO)->whereBetween('created_at', [$from, $to]);
        $total_etisalat_data = DataTransaction::where('network', NetworkType::NINE_MOBILE)->whereBetween('created_at', [$from, $to]);
        $total_airtel_data = DataTransaction::where('network', NetworkType::AIRTEL)->whereBetween('created_at', [$from, $to]);
        $total_mtn_data = DataTransaction::where('network', NetworkType::MTN)->whereBetween('created_at', [$from, $to]);
        $total_data_order = DataTransaction::whereBetween('created_at', [$from, $to]);


        $data_failed_order = DataTransaction::whereBetween('created_at', [$from, $to])->where('status', TransactionStatus::FAILED);
        $data_completed_order = DataTransaction::whereBetween('created_at', [$from, $to])->where('status', TransactionStatus::COMPLETED);
        $data_processing_order = DataTransaction::whereBetween('created_at', [$from, $to])->where('status', TransactionStatus::PROCESSING);


        return [
            'total_mtn_data_order' => $total_mtn_data->count(),
            'total_etisalat_data_order' => $total_etisalat_data->count(),
            'total_airtel_data_order' => $total_airtel_data->count(),
            'total_glo_data_order' => $total_glo_data->count(),
            'total_data_order' => $total_data_order->count(),
            'data_failed_order' => $data_failed_order->count(),
            'data_completed_order' => $data_completed_order->count(),
            'data_processing_order' => $data_processing_order->count(),

            'total_mtn_data_order_sum' => StatisticsService::sum_transaction($total_mtn_data->get()),
            'total_etisalat_data_order_sum' => StatisticsService::sum_transaction($total_etisalat_data->get()),
            'total_airtel_data_order_sum' => StatisticsService::sum_transaction($total_airtel_data->get()),
            'total_glo_data_order_sum' => StatisticsService::sum_transaction($total_glo_data->get()),
            'total_data_order_sum' => StatisticsService::sum_transaction($total_data_order->get()),
            'data_failed_order_sum' => StatisticsService::sum_transaction($data_failed_order->get()),
            'data_completed_order_sum' => StatisticsService::sum_transaction($data_completed_order->get()),
            'data_processing_order_sum' => StatisticsService::sum_transaction($data_processing_order->get())
        ];
    }

    /**
     * @param array $dataTransaction
     * @param $dataPlan
     * @param $amount
     * @return WalletTransaction
     * @throws GraphqlError
     */
    private function chargeUser(array $dataTransaction, $dataPlan, $amount)
    {
        $data = collect($dataTransaction);
        $walletTransactionData = $data->only(['transaction_type', 'description', 'beneficiary', 'user_id',])->toArray();
        $walletTransactionData['description'] = $amount . " " . $dataPlan->plan . " " . $dataPlan->network . " data purchase";
        $walletTransactionData['amount'] = $amount;

        return $this->walletTransactionService->create($walletTransactionData);
    }


}
