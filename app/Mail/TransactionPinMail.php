<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionPinMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $user;
    /**
     * @var string
     */
    public $m;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $message
     */
    public function __construct(User $user, string $message)
    {
        //
        $this->user = $user;
        $this->m = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user.transaction_pin');
    }
}
