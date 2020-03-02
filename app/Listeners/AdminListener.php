<?php

namespace App\Listeners;

use App\Events\AirtimeTransactionEvent;
use App\Events\CableTransactionEvent;
use App\Events\DataTransactionEvent;
use App\Events\ElectricityTransactionEvent;
use App\Events\QuickBuyEvent;
use App\Events\WalletTransactionEvent;
use App\Mail\AdminMails\AirtimeTransactionAdminMail;
use App\Mail\AdminMails\CableTransactionAdminMail;
use App\Mail\AdminMails\DataTransactionAdminMail;
use App\Mail\AdminMails\ElectricityTransactionAdminMail;
use App\Mail\AdminMails\QuickBuyAdminMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class AdminListener
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
     * Handle the event.
     *
     * @param  AirtimeTransactionEvent  $event
     * @return void
     */
    public function notifyAdminOfAirtimeTransaction(AirtimeTransactionEvent $event)
    {
        $admin = $event->admin;
        $airtime_transaction = $event->airtimeTransaction;
        $user = $event->user;

        sleep(3);
        Mail::to($admin)->send(new AirtimeTransactionAdminMail($admin, $airtime_transaction,$user));
    }

    /**
     * @param CableTransactionEvent $event
     * @return void
     */
    function notifyAdminOfCableTransaction(CableTransactionEvent $event){
        $admin = $event->admin;
        $user = $event->user;
        $cable_transaction = $event->cableTransaction;

        sleep(3);
        Mail::to($admin)->send(new CableTransactionAdminMail($admin, $cable_transaction, $user));
    }


    /**
     * @param DataTransactionEvent $event
     * @return void
     */
    function notifyAdminOfDataTransaction(DataTransactionEvent $event){
        $admin = $event->admin;
        $data_transaction = $event->dataTransaction;
        $user = $event->user;
        sleep(3);
        Mail::to($admin)->send(new DataTransactionAdminMail($admin, $data_transaction, $user));
    }

    /**
     * @param ElectricityTransactionEvent $event
     */
    function notifyAdminOfElectricityTransaction(ElectricityTransactionEvent $event){
        $admin = $event->admin;
        $electricity_transaction = $event->electricityTransaction;
        $user = $event->user;
        sleep(3);
        Mail::to($admin)->send(new ElectricityTransactionAdminMail($admin, $electricity_transaction, $user));
    }

    /**
     * @param QuickBuyEvent $event
     */
    function notifyAdminOfQuickBuy(QuickBuyEvent $event){
        $admin = $event->admin;
        $quick_buy = $event->quickBuy;
        $user = $event->user;
        sleep(3);
        Mail::to($admin)->send(new QuickBuyAdminMail($admin, $quick_buy, $user));
    }

    /**
     * @param $events
     */
    public function subscribe($events){
        $events->listen(
            'App\Events\AirtimeTransactionEvent',
            'App\Listeners\AdminListener@notifyAdminOfAirtimeTransaction'
        );

        $events->listen(
            'App\Events\CableTransactionEvent',
            'App\Listeners\AdminListener@notifyAdminOfCableTransaction'
        );

        $events->listen(
            'App\Events\DataTransactionEvent',
            'App\Listeners\AdminListener@notifyAdminOfDataTransaction'
        );

        $events->listen(
            'App\Events\ElectricityTransactionEvent',
            'App\Listeners\AdminListener@notifyAdminOfElectricityTransaction'
        );

        $events->listen(
            'App\Events\QuickBuyEvent',
            'App\Listeners\AdminListener@notifyAdminOfQuickBuy'
        );
    }

    /**
     *
     */
    public  function  handle(){

    }

}
