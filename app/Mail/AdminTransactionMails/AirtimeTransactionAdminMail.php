<?php

namespace App\Mail\AdminTransactionMails;

use App\AirtimeTransaction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AirtimeTransactionAdminMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $admin;
    /**
     * @var AirtimeTransaction
     */
    public $airtimeTransaction;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param User $admin
     * @param AirtimeTransaction $airtimeTransaction
     * @param $user
     */
    public function __construct($admin, AirtimeTransaction $airtimeTransaction, $user)
    {
        //
        $this->admin = $admin;
        $this->airtimeTransaction = $airtimeTransaction;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.admin_airtime_transaction');
    }
}
