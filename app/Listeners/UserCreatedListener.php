<?php

namespace App\Listeners;

use App\Events\TransactionPin;
use App\Events\UserCreated;
use App\Mail\NewUserMail;
use App\Mail\TransactionPinMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class UserCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }



    /**
     * @param UserCreated $event
     */
    function notifyUser(UserCreated $event) {
        $user = $event->user;

        sleep(3);
        Mail::to($user)->send(new NewUserMail($user));
    }


    function notifyUserOfTransactionPin(TransactionPin $event) {
        $user = $event->user;
        $message = $event->message;

        sleep(3);
        Mail::to($user)->send(new TransactionPinMail($user,$message));
    }
    /**
 * @param $events
 */
    public function subscribe($events){
        $events->listen(
            'App\Events\UserCreated',
            'App\Listeners\UserCreatedListener@notifyUser'
        );

        $events->listen(
            'App\Events\TransactionPin',
            'App\Listeners\UserCreatedListener@notifyUserOfTransactionPin'
        );

    }



    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
