<?php

namespace App\Mail\UserTransactionMails;

use App\BitcoinTransaction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BitcoinTransactionUserMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $user;
    /**
     * @var BitcoinTransaction
     */
    public $bitcoinTransaction;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param BitcoinTransaction $bitcoinTransaction
     */
    public function __construct(User $user, BitcoinTransaction $bitcoinTransaction)
    {
        //
        $this->user = $user;
        $this->bitcoinTransaction = $bitcoinTransaction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user.user_bitcoin_transaction');
    }
}
