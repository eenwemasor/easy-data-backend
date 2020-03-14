<?php

namespace App\Mail\AdminTransactionMails;

use App\AirtimeToWalletTransaction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AirtimeToWalletTransactionAdminMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $admin;
    /**
     * @var AirtimeToWalletTransaction
     */
    public $airtimeToWalletTransaction;
    /**
     * @var User
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @param User $admin
     * @param AirtimeToWalletTransaction $airtimeToWalletTransaction
     * @param User $user
     */
    public function __construct(User $admin, AirtimeToWalletTransaction $airtimeToWalletTransaction, User $user)
    {
        //
        $this->admin = $admin;
        $this->airtimeToWalletTransaction = $airtimeToWalletTransaction;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.admin_airtime_to_wallet_transaction');
    }
}
