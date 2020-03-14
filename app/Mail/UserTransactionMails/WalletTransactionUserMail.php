<?php

namespace App\Mail\UserTransactionMails;

use App\User;
use App\WalletTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WalletTransactionUserMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $user;
    /**
     * @var WalletTransaction
     */
    public $walletTransaction;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param WalletTransaction $walletTransaction
     */
    public function __construct(User $user, WalletTransaction $walletTransaction)
    {
        //
        $this->user = $user;
        $this->walletTransaction = $walletTransaction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user.user_wallet_transaction');
    }
}
