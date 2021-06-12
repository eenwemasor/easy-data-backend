<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionOtpMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $sender;
    /**
     * @var User
     */
    public $receiver;
    /**
     * @var string
     */
    public $otp;

    /**
     * Create a new message instance.
     *
     * @param User $sender
     * @param string $otp
     */
    public function __construct(User $sender, string $otp)
    {
        //
        $this->sender = $sender;
        $this->otp = $otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('transaction.transfer_fund_opt_mail');
    }
}
