<?php

namespace App\Mail\UserTransactionMails;

use App\User;
use App\UserBank;
use App\WithdrawFundTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawFundTransactionUserMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $user;
    /**
     * @var WithdrawFundTransaction
     */
    public $withdrawFundTransaction;
    /**
     * @var UserBank
     */
    public $bank;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param WithdrawFundTransaction $withdrawFundTransaction
     * @param UserBank $bank
     */
    public function __construct(User $user, WithdrawFundTransaction $withdrawFundTransaction, UserBank $bank)
    {
        //
        $this->user = $user;
        $this->withdrawFundTransaction = $withdrawFundTransaction;
        $this->bank = $bank;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user.user_withdraw_fund_transaction');
    }
}
