<?php

namespace App\Mail\UserTransactionMails;

use App\AirtimeTransaction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AirtimeTransactionUserMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $user;
    /**
     * @var AirtimeTransaction
     */
    public $airtimeTransaction;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param AirtimeTransaction $airtimeTransaction
     */
    public function __construct(User $user, AirtimeTransaction $airtimeTransaction)
    {
        //
        $this->user = $user;
        $this->airtimeTransaction = $airtimeTransaction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user.user_airtime_transaction');
    }
}
