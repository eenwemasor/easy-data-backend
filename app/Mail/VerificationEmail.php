<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;
    public  $email, $url, $name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $url, $name)
    {
        $this->email = $email;
        $this->url = $url;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('verify_email');
    }
}
