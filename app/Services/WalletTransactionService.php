<?php


namespace App\Services;


use App\Enums\TransactionType;
use App\Enums\WalletType;
use App\GraphQL\Errors\GraphqlError;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Wallet;
use App\Repositories\WalletTransactionRepository;
use App\User;
use App\WalletTransaction;

class WalletTransactionService
{
    /**
     * @var WalletTransactionRepository
     */
    private $wallet_transaction_repository;

    /**
     * WalletTransactionService constructor.
     * @param WalletTransactionRepository $wallet_transaction_repository
     */
    private $wallet_controller;
    private $walletTransactionData;

    public function __construct(WalletTransactionRepository $wallet_transaction_repository)
    {
        $this->wallet_transaction_repository = $wallet_transaction_repository;
        $this->wallet_controller = new Wallet();
    }

    static public function total_online_wallet_funding($target_bonus_wallet, $to)
    {
        $wallet_funding = WalletTransaction::where('description', 'Wallet Deposit')->whereBetween('created_at', [$target_bonus_wallet, $to]);
        return [
            'total_online_wallet_funding' => $wallet_funding->count(),
            'total_online_wallet_funding_sum' => StatisticsService::sum_transaction($wallet_funding->get())
        ];
    }

    /**
     * @param string $user_id
     * @throws
     *
     */
    public function withdraw_bonus_to_wallet(string $user_id)
    {
        $user = User::find($user_id);

        if ($user->bonus_wallet <= 0) {
            throw new GraphqlError("Your bonus wallet is currently empty");
        } else {
            $this->create(
                [
                    'transaction_type' => TransactionType::CREDIT,
                    'description' => "Bonus wallet to wallet funding",
                    'amount' => $user->bonus_wallet,
                    'beneficiary' => "Self",
                    'user_id' => $user_id
                ]
            );
        }

        $user->bonus_wallet = 0;
        $user->save();

        $new_user = User::find($user_id);
        return $new_user;
    }

    /**
     * @param array $walletTransaction
     * @param null $target_bonus_wallet
     * @return mixed
     * @throws GraphqlError
     */
    public function create(array $walletTransaction, $target_bonus_wallet = false)
    {
        $user_cont = new UserController();
        $user = $user_cont->getUserById($walletTransaction["user_id"]);

        $transaction_type = $walletTransaction["transaction_type"];
        $amount = $walletTransaction["amount"];

        if ($transaction_type == TransactionType::CREDIT) {
            $walletTransaction = $this->credit($walletTransaction, $user, $amount, $target_bonus_wallet);
        } else if ($transaction_type == TransactionType::DEBIT) {
            $this->debit($user, $amount, $walletTransaction, $target_bonus_wallet);
        }

        if (isset($walletTransaction['reference'])) {
            $this->walletTransactionData['reference'] = $walletTransaction['reference'];
        }

        return $this->wallet_transaction_repository->create($this->walletTransactionData);

    }

    /**
     * @param array $walletTransaction
     * @param $user
     * @param float $amount
     * @param $target_bonus_wallet
     * @return array
     */
    private function credit(array $walletTransaction, $user, float $amount, $target_bonus_wallet): array
    {
        $this->walletTransactionData = array_merge(
            $walletTransaction,
            $this->wallet_controller->fund_wallet(
                $user,
                $amount,
                $target_bonus_wallet,
                isset($walletTransaction['reference']) ?: null));
        return $walletTransaction;
    }

    /**
     * @param array $walletTransaction
     * @param $user
     * @param float $amount
     * @throws GraphqlError
     */
    private function debit_wallet(array $walletTransaction, $user, float $amount): void
    {
        if($user->wallet >= $amount){
            $this->walletTransactionData = array_merge(
                $walletTransaction,
                $this->wallet_controller->deduct_from_wallet($user, $amount,
                    WalletType::WALLET));
        }else{
            throw new GraphqlError('Insufficient balance');
        }
    }

    /**
     * @param array $walletTransaction
     * @param $user
     * @param float $amount
     * @throws GraphqlError
     */
    private function debit_bonus_wallet(array $walletTransaction, $user, float $amount): void
    {
        if($user->bonus_wallet >= $amount){
            $this->walletTransactionData = array_merge(
                $walletTransaction,
                $this->wallet_controller->deduct_from_bonus_wallet($user, $amount,
                    WalletType::BONUS_WALLET));
        }else{
            throw new GraphqlError('Insufficient balance');
        }

    }

    /**
     * @param $user
     * @param float $amount
     * @param array $walletTransaction
     * @param $target_bonus_wallet
     * @throws GraphqlError
     */
    private function debit($user, float $amount, array $walletTransaction, $target_bonus_wallet): void
    {
        if($target_bonus_wallet){
            $this->debit_bonus_wallet($walletTransaction, $user, $amount);
        }else{
            $this->debit_wallet($walletTransaction, $user, $amount);
        }

    }
}
