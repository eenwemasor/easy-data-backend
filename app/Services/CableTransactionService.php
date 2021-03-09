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
        $cablePlan = CablePlanList::find($data['plan']);

        $amount = $this->ringoCable->apply_discount($cableTransaction, $cablePlan->amount);
        $this->ringoCable->check_api_wallet($amount);

        $walletTransactionResult = $this->chargeUser($data, $cableTransaction['beneficiary_name'], $amount);

        $cableTransactionResult = $this->ringoCable->purchase_cable_tv($cableTransaction, $cablePlan, $walletTransactionResult['reference']);

        $walletTransactionResultCollection = collect($walletTransactionResult);
        $cableTransactionData = [
            'decoder' => $cablePlan->cable,
            'decoder_number' => $cableTransaction['decoder_number'],
            'beneficiary_name' => $cableTransaction['beneficiary_name'],
            'method' => $walletTransactionResult['wallet'],
            'plan' => $cablePlan->plan
        ];

        $cableTransactionData = array_merge($walletTransactionResultCollection->except(['transaction_type', 'description', 'status'])->toArray(), $cableTransactionData);
        if ($cableTransactionResult['success']) {
            $cableTransactionData['status'] = TransactionStatus::COMPLETED;
            $cableTransactionResult = $this->cableTransactionRepository->create($cableTransactionData);
            return $cableTransactionResult;
        } else {
            $cableTransactionData['status'] = TransactionStatus::FAILED;
            $this->cableTransactionRepository->create($cableTransactionData);

            $user = $user_cont->getUserById($cableTransaction["user_id"]);
            if ($walletTransactionResult['wallet'] == WalletType::WALLET) {
                $user->wallet = $user->wallet + $amount;
            } else {
                $user->bonus_wallet = $user->bonus_wallet + $amount;
            }
            $user->save();

            $wallet_transaction = WalletTransaction::find($walletTransactionResult['id']);
            $wallet_transaction->status = TransactionStatus::FAILED;
            $wallet_transaction->save();

            throw new GraphqlError($cableTransactionResult['message']);
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

    /**
     * @param \Illuminate\Support\Collection $data
     * @param $beneficiary_name
     * @param $amount
     * @return mixed
     * @throws GraphqlError
     */
    private function chargeUser(\Illuminate\Support\Collection $data, $beneficiary_name, $amount): mixed
    {
        $walletTransactionData = $data->only(['description', 'user_id',])->toArray();
        $walletTransactionData['transaction_type'] = TransactionType::DEBIT;
        $walletTransactionData['beneficiary'] = $beneficiary_name;
        $walletTransactionData['amount'] = $amount;
        $walletTransactionResult = $this->walletTransactionService->create($walletTransactionData);
        return $walletTransactionResult;
    }


}
