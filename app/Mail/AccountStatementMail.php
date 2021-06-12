<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountStatementMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var User
     */
    public $user;
    /**
     * @var string
     */
    public $statement_path;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $statement_path
     */
    public function __construct(User $user, string $statement_path)
    {
        //
        $this->user = $user;
        $this->statement_path = $statement_path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('account_statement.account_statement_template')
            ->attach(public_path($this->statement_path));
    }
}
