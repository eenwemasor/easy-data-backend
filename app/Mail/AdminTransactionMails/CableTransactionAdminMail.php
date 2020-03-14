<?php

namespace App\Mail\AdminTransactionMails;

use App\CableTransaction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CableTransactionAdminMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $admin;
    /**
     * @var CableTransaction
     */
    public $cableTransaction;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param User $admin
     * @param CableTransaction $cableTransaction
     * @param $user
     */
    public function __construct(User $admin, CableTransaction $cableTransaction, $user)
    {
        //
        $this->admin = $admin;
        $this->cableTransaction = $cableTransaction;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.admin_cable_transaction');
    }
}
