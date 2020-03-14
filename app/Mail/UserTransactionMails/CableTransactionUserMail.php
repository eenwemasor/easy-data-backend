<?php

namespace App\Mail\UserTransactionMails;

use App\CableTransaction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CableTransactionUserMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $user;
    /**
     * @var CableTransaction
     */
    public $cableTransaction;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param CableTransaction $cableTransaction
     */
    public function __construct(User $user, CableTransaction $cableTransaction)
    {
        //
        $this->user = $user;
        $this->cableTransaction = $cableTransaction;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user.user_cable_transaction');
    }
}
