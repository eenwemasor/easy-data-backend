<?php

namespace App\Listeners;

use App\Events\TransactionRequest;
use App\Events\TransferFundRequest;
use App\Mail\TransactionOtpMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class TransactionRequestListener
{
    function sendSenderOtp(TransferFundRequest $event) {
        $otp = $event->otp;
        $sender = $event->sender;

        sleep(3);
        Mail::to($sender)->send(new TransactionOtpMail($sender,$otp));
    }

    /**
     * @param $events
     */
    public function subscribe($events){
            $events->listen(
            'App\Events\TransferFundRequest',
            'App\Listeners\TransactionRequestListener@sendSenderOtp'
        );

    }




    /**
     * Handle the event.
     * @return void
     */
    public function handle()
    {
        //
    }
}
