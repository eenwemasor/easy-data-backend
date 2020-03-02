<?php

namespace App\Mail\UserMails;

use App\DataTransaction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DataTransactionUserMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    public $user;
    /**
     * @var DataTransaction
     */
    public $dataTransaction;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param DataTransaction $dataTransaction
     */
    public function __construct(User  $user, DataTransaction $dataTransaction)
    {
        //
        $this->user = $user;
        $this->dataTransaction = $dataTransaction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user.user_data_transaction');
    }
}
