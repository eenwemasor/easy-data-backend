<?php

namespace App\Mail\UserTransactionMails;

use App\AirtimeToCashTransaction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AirtimeToCashTransactionUserMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $user;
    /**
     * @var AirtimeToCashTransaction
     */
    public $airtimeToCashTransaction;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param AirtimeToCashTransaction $airtimeToCashTransaction
     */
    public function __construct(User $user, AirtimeToCashTransaction $airtimeToCashTransaction)
    {
        //
        $this->user = $user;
        $this->airtimeToCashTransaction = $airtimeToCashTransaction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user.user_airtime_to_cash_transaction');
    }
}
