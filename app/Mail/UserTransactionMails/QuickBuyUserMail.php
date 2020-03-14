<?php

namespace App\Mail\UserTransactionMails;

use App\QuickBuy;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuickBuyUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    /**
     * @var QuickBuy
     */
    public $quickBuy;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param QuickBuy $quickBuy
     */
    public function __construct($user, QuickBuy $quickBuy)
    {
        //
        $this->user = $user;
        $this->quickBuy = $quickBuy;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user.user_quick_buy');
    }
}
