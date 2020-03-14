<?php

namespace App\Mail\AdminTransactionMails;

use App\BitcoinTransaction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BitcoinTransactionAdminMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $admin;
    /**
     * @var BitcoinTransaction
     */
    public $bitcoinTransaction;
    /**
     * @var User
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @param User $admin
     * @param BitcoinTransaction $bitcoinTransaction
     * @param User $user
     */
    public function __construct(User $admin, BitcoinTransaction $bitcoinTransaction, User $user)
    {
        //
        $this->admin = $admin;
        $this->bitcoinTransaction = $bitcoinTransaction;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.admin_bitcoin_transaction');
    }
}
