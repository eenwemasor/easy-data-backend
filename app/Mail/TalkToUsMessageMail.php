<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TalkToUsMessageMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $content;

    /**
     * Create a new message instance.
     *
     * @param string $name
     * @param string $email
     * @param string $content
     */
    public function __construct(string $name, string $email, string $content)
    {
        //
        $this->name = $name;
        $this->email = $email;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.talk_to_us_message');
    }
}
