<?php

namespace App\Mail\AdminTransactionMails;

use App\ElectricityTransaction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ElectricityTransactionAdminMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $admin;
    /**
     * @var ElectricityTransaction
     */
    public $electricityTransaction;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param User $admin
     * @param ElectricityTransaction $electricityTransaction
     * @param $user
     */
    public function __construct(User $admin, ElectricityTransaction $electricityTransaction, $user)
    {
        //
        $this->admin = $admin;
        $this->electricityTransaction = $electricityTransaction;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.admin_electricity_transaction');
    }
}
