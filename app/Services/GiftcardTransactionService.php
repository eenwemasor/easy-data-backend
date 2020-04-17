<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:22
 */

namespace App\Services;


use App\Enums\TransactionStatus;
use App\Events\GiftcardTransactionEvent;
use App\GiftcardTransaction;
use App\GraphQL\Errors\GraphqlError;
use App\Http\Controllers\UserController;
use App\Repositories\GiftcardTransactionRepository;
use App\User;

class GiftcardTransactionService
{
    /**
     * @var GiftcardTransactionRepository
     */
    private $giftcard_transaction_repository;
    /**
     * @var WalletTransactionService
     */
    private $walletTransactionService;

    /**
     * GiftcardTransactionService constructor.
     * @param GiftcardTransactionRepository $giftcard_transaction_repository
     * @param WalletTransactionService $walletTransactionService
     */
    function __construct(GiftcardTransactionRepository $giftcard_transaction_repository, WalletTransactionService $walletTransactionService)
    {
        $this->giftcard_transaction_repository = $giftcard_transaction_repository;
        $this->walletTransactionService = $walletTransactionService;
    }


    /**
     * @param array $giftcardTransaction
     * @return \App\GiftcardTransaction
     * @throws
     */
    public function create(array  $giftcardTransaction )
    {
        $user = User::find($giftcardTransaction["user_id"]);
        if (!$user->active) {
            throw new GraphqlError("Account not activated, please fund your wallet or pay our one time activation fee to continue.");
        }

        $giftcardTransaction['status'] =TransactionStatus::PROCESSING;
        $giftcardTransaction['reference'] =uniqid();


        $gift_card_transaction = $this->giftcard_transaction_repository->create($giftcardTransaction);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($giftcardTransaction["user_id"]);
        $admin = $user_cont->getAdmin();



        event(new GiftcardTransactionEvent($gift_card_transaction,$user, $admin));

        return $gift_card_transaction;
    }



    /**
     * @param string $transaction_id
     * @return GiftcardTransaction
     */
    public function mark_transaction_successful(string $transaction_id){
        return $this->giftcard_transaction_repository->mark_transaction_successful($transaction_id);
    }

    /**
     * @param string $transaction_id
     * @return GiftcardTransaction
     */
    public function mark_transaction_failed(string $transaction_id){

        return $this->giftcard_transaction_repository->mark_transaction_failed($transaction_id);
    }


}