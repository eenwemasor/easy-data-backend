<?php

namespace App\Listeners;

use App\Events\TalkToUsMessage;
use App\Events\TalkToUsMessageEvent;
use App\Mail\TalkToUsMessageMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class TalkToUsMessageListener
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
     * @param TalkToUsMessageEvent $event
     */
    public function forward_to_admin(TalkToUsMessageEvent $event)
    {
        $name = $event->name;
        $message = $event->message;
        $email = $event->email;
        $recipient = $event->recipient;

        sleep(3);
        Mail::to($recipient)->send(new TalkToUsMessageMail($name,$email,$message));
    }
    /**
     * @param $events
     */
    public function subscribe($events){
        $events->listen(
            'App\Events\TalkToUsMessageEvent',
            'App\Listeners\TalkToUsMessageListener@forward_to_admin'
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
