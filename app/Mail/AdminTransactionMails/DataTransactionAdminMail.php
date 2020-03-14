<?php

namespace App\Mail\AdminTransactionMails;

use App\DataTransaction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DataTransactionAdminMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $admin;
    /**
     * @var DataTransaction
     */
    public $dataTransaction;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param User $admin
     * @param DataTransaction $dataTransaction
     * @param $user
     */
    public function __construct(User  $admin, DataTransaction $dataTransaction, $user)
    {
        //
        $this->admin = $admin;
        $this->dataTransaction = $dataTransaction;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.admin_data_transaction');
    }
}
