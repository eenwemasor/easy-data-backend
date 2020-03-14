<?php

namespace App\Mail\AdminTransactionMails;

use App\AirtimeToCashTransaction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AirtimeToCashTransactionAdminMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $admin;
    /**
     * @var AirtimeToCashTransaction
     */
    public $airtimeToCashTransaction;
    /**
     * @var User
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @param User $admin
     * @param AirtimeToCashTransaction $airtimeToCashTransaction
     * @param User $user
     */
    public function __construct(User $admin, AirtimeToCashTransaction $airtimeToCashTransaction, User $user)
    {
        //
        $this->admin = $admin;
        $this->airtimeToCashTransaction = $airtimeToCashTransaction;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.admin_airtime_to_cash_transaction');
    }
}
