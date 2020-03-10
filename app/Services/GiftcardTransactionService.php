<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/03/2020
 * Time: 22:22
 */

namespace App\Services;


use App\Http\Controllers\UserController;
use App\Repositories\GiftcardTransactionRepository;

class GiftcardTransactionService
{
    /**
     * @var GiftcardTransactionRepository
     */
    private $giftcard_transaction_repository;

    /**
     * GiftcardTransactionService constructor.
     * @param GiftcardTransactionRepository $giftcard_transaction_repository
     */
    function __construct(GiftcardTransactionRepository $giftcard_transaction_repository)
    {
        $this->giftcard_transaction_repository = $giftcard_transaction_repository;
    }


    /**
     * @param array $giftcardTransaction
     * @return \App\GiftcardTransaction
     */
    public function create(array  $giftcardTransaction )
    {
        $gift_card_transaction = $this->giftcard_transaction_repository->create($giftcardTransaction);
        $user_cont = New UserController();
        $user = $user_cont->getUserById($giftcardTransaction["user_id"]);
        $admin = $user_cont->getAdmin();



        event(new GiftcardTransactionEvent($gift_card_transaction,$user, $admin));

        return $gift_card_transaction;
    }
}