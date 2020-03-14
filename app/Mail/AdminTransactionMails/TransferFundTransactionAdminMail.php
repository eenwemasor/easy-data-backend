<?php

namespace App\Mail\AdminTransactionMails;

use App\TransferFundTransaction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransferFundTransactionAdminMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $admin;
    /**
     * @var TransferFundTransaction
     */
    public $transferFundTransaction;
    /**
     * @var User
     */
    public $user;
    /**
     * @var User
     */
    public $recipient;

    /**
     * Create a new message instance.
     *
     * @param User $admin
     * @param TransferFundTransaction $transferFundTransaction
     * @param User $user
     * @param User $recipient
     */
    public function __construct(User $admin, TransferFundTransaction $transferFundTransaction, User $user, User $recipient)
    {
        //
        $this->admin = $admin;
        $this->transferFundTransaction = $transferFundTransaction;
        $this->user = $user;
        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.admin_transfer_fund_transaction');
    }
}
