<?php

namespace App\Listeners;

use App\Events\AirtimeTransactionEvent;
use App\Events\CableTransactionEvent;
use App\Events\DataTransactionEvent;
use App\Events\ElectricityTransactionEvent;
use App\Events\QuickBuyEvent;
use App\Events\WalletTransactionEvent;
use App\Mail\UserMails\AirtimeTransactionUserMail;
use App\Mail\UserMails\CableTransactionUserMail;
use App\Mail\UserMails\DataTransactionUserMail;
use App\Mail\UserMails\ElectricityTransactionUserMail;
use App\Mail\UserMails\QuickBuyUserMail;
use App\Mail\UserMails\WalletTransactionUserMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class TransactionListener
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
    public function notifyUserOfAirtimeTransaction(AirtimeTransactionEvent $event)
    {
        $user = $event->user;
        $airtime_transaction = $event->airtimeTransaction;

        sleep(3);
        Mail::to($user)->send(new AirtimeTransactionUserMail($user, $airtime_transaction));
    }

    /**
     * @param CableTransactionEvent $event
     * @return void
     */
    function notifyUserOfCableTransaction(CableTransactionEvent $event){
        $user = $event->user;
        $cable_transaction = $event->cableTransaction;

        sleep(3);
        Mail::to($user)->send(new CableTransactionUserMail($user, $cable_transaction));
    }


    /**
     * @param DataTransactionEvent $event
     * @return void
     */
    function notifyUserOfDataTransaction(DataTransactionEvent $event){
        $user = $event->user;
        $data_transaction = $event->dataTransaction;

        sleep(3);
        Mail::to($user)->send(new DataTransactionUserMail($user, $data_transaction));
    }

    /**
     * @param ElectricityTransactionEvent $event
     */
    function notifyUserOfElectricityTransaction(ElectricityTransactionEvent $event){
        $user = $event->user;
        $electricity_transaction = $event->electricityTransaction;

        sleep(3);
        Mail::to($user)->send(new ElectricityTransactionUserMail($user, $electricity_transaction));
    }

    /**
     * @param QuickBuyEvent $event
     */
    function notifyUserOfQuickBuy(QuickBuyEvent $event){
        $user = $event->user;
        $quick_buy = $event->quickBuy;

        sleep(3);
        Mail::to($user)->send(new QuickBuyUserMail($user, $quick_buy));
    }

    /**
     * @param WalletTransactionEvent $event
     */
    function notifyUserOfWalletTransaction(WalletTransactionEvent $event) {
        $user = $event->user;
        $wallet_transaction = $event->walletTransaction;

        sleep(3);
        Mail::to($user)->send(new WalletTransactionUserMail($user, $wallet_transaction));
    }
    /**
     * @param $events
     */
    public function subscribe($events){
        $events->listen(
            'App\Events\AirtimeTransactionEvent',
            'App\Listeners\TransactionListener@notifyUserOfAirtimeTransaction'
        );

        $events->listen(
            'App\Events\CableTransactionEvent',
            'App\Listeners\TransactionListener@notifyUserOfCableTransaction'
        );

        $events->listen(
            'App\Events\DataTransactionEvent',
            'App\Listeners\TransactionListener@notifyUserOfDataTransaction'
        );

        $events->listen(
            'App\Events\ElectricityTransactionEvent',
            'App\Listeners\TransactionListener@notifyUserOfElectricityTransaction'
        );

        $events->listen(
            'App\Events\QuickBuyEvent',
            'App\Listeners\TransactionListener@notifyUserOfQuickBuy'
        );
        $events->listen(
            'App\Events\WalletTransactionEvent',
            'App\Listeners\TransactionListener@notifyUserOfWalletTransaction'
        );
    }
    /**
     *
     */
    public  function  handle(){

    }
}
