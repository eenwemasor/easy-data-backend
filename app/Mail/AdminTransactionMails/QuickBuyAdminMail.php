<?php

namespace App\Mail\AdminTransactionMails;

use App\QuickBuy;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuickBuyAdminMail extends Mailable
{
    use Queueable, SerializesModels;
    public $admin;
    /**
     * @var QuickBuy
     */
    public $quickBuy;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param $admin
     * @param QuickBuy $quickBuy
     * @param $user
     */
    public function __construct($admin, QuickBuy $quickBuy, $user)
    {
        //
        $this->admin = $admin;
        $this->quickBuy = $quickBuy;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.admin_quick_buy');
    }
}
