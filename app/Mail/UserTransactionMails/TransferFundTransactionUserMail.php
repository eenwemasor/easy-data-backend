<?php

namespace App\Mail\UserTransactionMails;

use App\TransferFundTransaction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransferFundTransactionUserMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $user;
    /**
     * @var TransferFundTransaction
     */
    public $transferFundTransaction;
    /**
     * @var User
     */
    public $recipient;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param TransferFundTransaction $transferFundTransaction
     * @param User $recipient
     */
    public function __construct(User $user, TransferFundTransaction $transferFundTransaction, User $recipient)
    {
        //
        $this->user = $user;
        $this->transferFundTransaction = $transferFundTransaction;
        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user.user_transfer_fund_transaction');
    }
}
