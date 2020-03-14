<?php

namespace App\Mail\UserTransactionMails;

use App\AirtimeToWalletTransaction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AirtimeToWalletTransactionUserMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $user;
    /**
     * @var AirtimeToWalletTransaction
     */
    public $airtimeToWalletTransaction;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param AirtimeToWalletTransaction $airtimeToWalletTransaction
     */
    public function __construct(User $user, AirtimeToWalletTransaction $airtimeToWalletTransaction)
    {
        //
        $this->user = $user;
        $this->airtimeToWalletTransaction = $airtimeToWalletTransaction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user.user_airtime_to_wallet_transaction');
    }
}
