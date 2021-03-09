<?php


namespace App\Services;


use App\AirtimeTransaction;
use App\Enums\NetworkType;
use App\Enums\TransactionStatus;
use App\Enums\WalletType;
use App\GraphQL\Errors\GraphqlError;
use App\Repositories\AirtimeTransactionRepository;
use App\User;
use App\Vendors\Ringo\RingoAirtime;
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
     * @var ringoAirtime
     */
    private $ringoAirtime;

    /**
     * AirtimeTransactionService constructor.
     * @param AirtimeTransactionRepository $airtime_transaction_repository
     * @param WalletTransactionService $walletTransactionService
     * @param RingoAirtime $ringoAirtime
     */
    public function __construct(
        AirtimeTransactionRepository $airtime_transaction_repository,
        WalletTransactionService $walletTransactionService,
        RingoAirtime $ringoAirtime
    )
    {
        $this->airtime_transaction_repository = $airtime_transaction_repository;
        $this->walletTransactionService = $walletTransactionService;
        $this->ringoAirtime = $ringoAirtime;
    }

    /**
     * @param array $airtimeTransaction
     * @return AirtimeTransaction
     * @throws
     */
    public function create(array $airtimeTransaction)
    {
        $this->ringoAirtime->check_api_wallet($airtimeTransaction['amount']);
        $data = collect($airtimeTransaction);
        $amount = $this->ringoAirtime->apply_discount($airtimeTransaction);

        $walletTransactionData = $data->only(['transaction_type', 'description', 'beneficiary', 'user_id'])->toArray();
        $walletTransactionData['description'] = $airtimeTransaction['amount'] . " " . $airtimeTransaction['network'] . " ringoAirtime purchase";
        $walletTransactionData['amount'] = $amount;


        $walletTransactionResult = $this->walletTransactionService->create($walletTransactionData);
        $airtimeData = $data->only(['phone',])->toArray();

        $initiateTransaction = $this->ringoAirtime->initiate_airtime_transaction(
            [
                'amount' => $airtimeTransaction['amount'],
                'request_id' => $walletTransactionResult['reference'],
                'msisdn' => $airtimeTransaction['phone'],
                'product_id' => $this->ringoAirtime->get_service_code($data['network'])
            ]
        );

        $walletResult = collect($walletTransactionResult);
        $airtimeData['method'] = $walletTransactionResult['wallet'];
        $airtimeData['network'] = $airtimeTransaction['network'];
        if ($initiateTransaction->message == "SUCCESSFUL" && $initiateTransaction->status == "200") {
            $airtimeData['status'] = TransactionStatus::COMPLETED;
            $airtimeTransactionData = array_merge($walletResult->except(['transaction_type', 'description', 'status'])->toArray(), $airtimeData);
            return $this->airtime_transaction_repository->create($airtimeTransactionData);
        } else {
            $airtimeData['status'] = TransactionStatus::FAILED;
            $airtimeTransactionData = array_merge($walletResult->except(['transaction_type', 'description', 'status'])->toArray(), $airtimeData);
            $this->airtime_transaction_repository->create($airtimeTransactionData);

            $user = User::find($airtimeTransaction["user_id"]);
            if ($walletTransactionResult['wallet'] == WalletType::WALLET) {
                $user->wallet = $user->wallet + $amount;
            } else {
                $user->bonus_wallet = $user->bonus_wallet + $amount;
            }
            $user->save();
            $wallet_transaction = WalletTransaction::find($walletTransactionResult['id']);
            $wallet_transaction->status = TransactionStatus::FAILED;
            $wallet_transaction->save();
            throw new GraphqlError($initiateTransaction->message);
        }
    }


    static public function total_transaction_statistics($from, $to)
    {
        $total_glo_airtime = AirtimeTransaction::where('network', NetworkType::GLO)->whereBetween('created_at', [$from, $to]);
        $total_etisalat_airtime = AirtimeTransaction::where('network', NetworkType::NINE_MOBILE)->whereBetween('created_at', [$from, $to]);
        $total_airtel_airtime = AirtimeTransaction::where('network', NetworkType::AIRTEL)->whereBetween('created_at', [$from, $to]);
        $total_mtn_airtime = AirtimeTransaction::where('network', NetworkType::MTN)->whereBetween('created_at', [$from, $to]);

        $total_airtime_order = AirtimeTransaction::whereBetween('created_at', [$from, $to]);
        $airtime_failed_order = AirtimeTransaction::whereBetween('created_at', [$from, $to])->where('status', TransactionStatus::FAILED);
        $airtime_completed_order = AirtimeTransaction::whereBetween('created_at', [$from, $to])->where('status', TransactionStatus::COMPLETED);
        $airtime_processing_order = AirtimeTransaction::whereBetween('created_at', [$from, $to])->where('status', TransactionStatus::PROCESSING);


        return [
            'total_mtn_airtime_order' => $total_mtn_airtime->count(),
            'total_etisalat_airtime_order' => $total_etisalat_airtime->count(),
            'total_airtel_airtime_order' => $total_airtel_airtime->count(),
            'total_glo_airtime_order' => $total_glo_airtime->count(),
            'total_airtime_order' => $total_airtime_order->count(),
            'airtime_failed_order' => $airtime_failed_order->count(),
            'airtime_completed_order' => $airtime_completed_order->count(),
            'airtime_processing_order' => $airtime_processing_order->count(),
            'total_mtn_airtime_order_sum' => StatisticsService::sum_transaction($total_mtn_airtime->get()),
            'total_etisalat_airtime_order_sum' => StatisticsService::sum_transaction($total_etisalat_airtime->get()),
            'total_glo_airtime_order_sum' => StatisticsService::sum_transaction($total_glo_airtime->get()),
            'total_airtel_airtime_order_sum' => StatisticsService::sum_transaction($total_airtel_airtime->get()),
            'total_airtime_order_sum' => StatisticsService::sum_transaction($total_glo_airtime->get()),
            'airtime_failed_order_sum' => StatisticsService::sum_transaction($total_glo_airtime->get()),
            'airtime_completed_order_sum' => StatisticsService::sum_transaction($total_glo_airtime->get()),
            'airtime_processing_order_sum' => StatisticsService::sum_transaction($total_glo_airtime->get()),
        ];
    }


    /**
     * @param string $transaction_id
     * @return AirtimeTransaction
     */
    public function mark_transaction_successful(string $transaction_id)
    {
        return $this->airtime_transaction_repository->mark_transaction_successful($transaction_id);
    }


    /**
     * @param string $transaction_id
     * @return AirtimeTransaction
     */
    public function mark_transaction_failed(string $transaction_id)
    {

        return $this->airtime_transaction_repository->mark_transaction_failed($transaction_id);
    }


}
