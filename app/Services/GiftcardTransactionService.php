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
use App\Http\Controllers\UserController;
use App\Repositories\GiftcardTransactionRepository;

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
     */
    public function create(array  $giftcardTransaction )
    {

        $data = collect($giftcardTransaction);

        $walletTransactionData = $data->only([
            'transaction_type',
            'description',
            'beneficiary',
            'user_id',
        ])->toArray();
        $walletTransactionData['amount'] = $giftcardTransaction['amount_to_receive'];

        $walletTransactionResult =  $this->walletTransactionService->create($walletTransactionData);
        $giftcardnData = $data->only([
            'amount_to_sell',
            'amount_to_receive',
            'gift_card_type',
        ])->toArray();

        $wallet_result = collect($walletTransactionResult);
        $wallet_result['status'] =TransactionStatus::SENT;
        $giftcardTransactionData = array_merge(
            $wallet_result->except([
                'transaction_type',
                'description',
                'amount'
            ])->toArray(), $giftcardnData);


        $gift_card_transaction = $this->giftcard_transaction_repository->create($giftcardTransactionData);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($giftcardTransaction["user_id"]);
        $admin = $user_cont->getAdmin();



        event(new GiftcardTransactionEvent($gift_card_transaction,$user, $admin));

        return $gift_card_transaction;
    }
}