<?php

namespace App\Mail\UserTransactionMails;

use App\GiftcardTransaction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GiftcardTransactionUserMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $user;
    /**
     * @var GiftcardTransaction
     */
    public $giftcardTransaction;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param GiftcardTransaction $giftcardTransaction
     */
    public function __construct(User $user, GiftcardTransaction $giftcardTransaction)
    {
        //
        $this->user = $user;
        $this->giftcardTransaction = $giftcardTransaction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user.user_gift_card_transaction');
    }
}
