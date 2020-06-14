<?php


namespace App\Services;


use App\Enums\TransactionType;
use App\Enums\WalletType;
use App\Events\WalletTransactionEvent;
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
            $this->create(['transaction_type' => TransactionType::CREDIT, 'description' => "Bonus wallet to wallet funding", 'amount' => $user->bonus_wallet, 'beneficiary' => "Self", 'user_id' => $user_id,]);
        }

        $user->bonus_wallet = 0;
        $user->save();

        $new_user = User::find($user_id);
        return $new_user;
    }

    /**
     * @param array $walletTransaction
     * @param null $from
     * @return mixed
     * @throws GraphqlError
     */
    public function create(array $walletTransaction, $from = null)
    {
        // TODO: Implement create() method.
        $user_cont = New UserController();
        $user = $user_cont->getUserById($walletTransaction["user_id"]);

        $transaction_type = $walletTransaction["transaction_type"];
        $amount = $walletTransaction["amount"];

        if ($transaction_type == TransactionType::CREDIT) {
            $this->walletTransactionData = array_merge($walletTransaction, $this->wallet_controller->fund_wallet($user, $amount, $from));
        } else if ($transaction_type == TransactionType::DEBIT) {
            if ($user->wallet >= $amount) {
                $this->walletTransactionData = array_merge($walletTransaction, $this->wallet_controller->deduct_from_wallet($user, $amount, WalletType::WALLET));
            } else if ($user->bonus_wallet >= $amount) {
                $this->walletTransactionData = array_merge($walletTransaction, $this->wallet_controller->deduct_from_bonus_wallet($user, $amount, WalletType::BONUS_WALLET));
            } else {
                throw new GraphqlError('Insufficient balance');
            }
        }

        if (isset($walletTransaction['reference'])) {
            $this->walletTransactionData['reference'] = $walletTransaction['reference'];
        }

        $wallet_transaction = $this->wallet_transaction_repository->create($this->walletTransactionData);
//        event(new WalletTransactionEvent($wallet_transaction, $user));

        return $wallet_transaction;
    }


    static public function total_online_wallet_funding($from, $to){
        $wallet_funding = WalletTransaction::where('description','Wallet Deposit')->whereBetween('created_at', [$from, $to]);

        return [
            'total_online_wallet_funding'=>$wallet_funding->count(),
            'total_online_wallet_funding_sum'=>StatisticsService::sum_transaction($wallet_funding->get())
        ];
    }
}