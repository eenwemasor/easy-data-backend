<?php

namespace App\Mail\AdminTransactionMails;

use App\User;
use App\UserBank;
use App\WithdrawFundTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawFundTransactionAdminMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $admin;
    /**
     * @var WithdrawFundTransaction
     */
    public $withdrawFundTransaction;
    /**
     * @var User
     */
    public $user;
    /**
     * @var UserBank
     */
    public $bank;

    /**
     * Create a new message instance.
     *
     * @param User $admin
     * @param WithdrawFundTransaction $withdrawFundTransaction
     * @param User $user
     * @param UserBank $bank
     */
    public function __construct(User $admin, WithdrawFundTransaction $withdrawFundTransaction , User $user, UserBank $bank)
    {
        //
        $this->admin = $admin;
        $this->withdrawFundTransaction = $withdrawFundTransaction;
        $this->user = $user;
        $this->bank = $bank;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.admin_withdraw_fund_transaction');
    }
}
