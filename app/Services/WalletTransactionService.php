<?php


namespace App\Services;


use App\Enums\TransactionType;
use App\Enums\WalletType;
use App\Events\WalletTransactionEvent;
use App\GraphQL\Errors\GraphqlError;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Wallet;
use App\Repositories\WalletTransactionRepository;

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
     * @param array $walletTransaction
     * @return mixed
     * @throws GraphqlError
     */
    public function create(array  $walletTransaction)
    {
        // TODO: Implement create() method.
        $user_cont = New UserController();
        $user = $user_cont->getUserById($walletTransaction["user_id"]);

        $transaction_type = $walletTransaction["transaction_type"];
        $amount = $walletTransaction["amount"];

        if($transaction_type == TransactionType::CREDIT){
            $this->walletTransactionData = array_merge($walletTransaction, $this->wallet_controller->fundWallet($user, $amount));
        }else if($transaction_type == TransactionType::DEBIT){
            if($user->wallet >= $amount){
                $this->walletTransactionData = array_merge($walletTransaction, $this->wallet_controller->deductFromWallet($user, $amount, WalletType::WALLET));
            }else if($user->bonus_wallet >= $amount){
                $this->walletTransactionData = array_merge($walletTransaction, $this->wallet_controller->deductFromBonusWallet($user, $amount, WalletType::BONUS_WALLET));
            }else{
                throw new GraphqlError('Insufficient Balance');
            }
        }



        $wallet_transaction = $this->wallet_transaction_repository->create( $this->walletTransactionData);
        event(new WalletTransactionEvent($wallet_transaction, $user));

        return $wallet_transaction;
    }

}