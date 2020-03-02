<?php

namespace App\Mail\UserMails;

use App\ElectricityTransaction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ElectricityTransactionUserMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    public $user;
    /**
     * @var ElectricityTransaction
     */
    public $electricityTransaction;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param ElectricityTransaction $electricityTransaction
     */
    public function __construct(User $user, ElectricityTransaction $electricityTransaction)
    {
        //
        $this->user = $user;
        $this->electricityTransaction = $electricityTransaction;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user.user_electricity_transaction');
    }
}
