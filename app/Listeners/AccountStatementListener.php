<?php

namespace App\Listeners;

use App\Events\AccountStatementEvent;
use App\Mail\AccountStatementMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class AccountStatementListener
{

    public function send_account_statement(AccountStatementEvent $event)
    {
        $user = $event->user;
        $statement_path = $event->statement_path;

        sleep(3);
        Mail::to($user)->send(new AccountStatementMail($user,$statement_path));
    }

    /**
     * @param $events
     */
    public function subscribe($events){
        $events->listen(
            'App\Events\AccountStatementEvent',
            'App\Listeners\AccountStatementListener@send_account_statement'
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
