<?php

namespace App\Mail\AdminTransactionMails;

use App\GiftcardTransaction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GiftcardTransactionAdminMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $admin;
    /**
     * @var GiftcardTransaction
     */
    public $giftcardTransaction;
    /**
     * @var User
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @param User $admin
     * @param GiftcardTransaction $giftcardTransaction
     * @param User $user
     */
    public function __construct(User $admin, GiftcardTransaction $giftcardTransaction, User $user)
    {
        //
        $this->admin = $admin;
        $this->giftcardTransaction = $giftcardTransaction;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.admin_gift_card_transaction');
    }
}
