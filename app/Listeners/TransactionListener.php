<?php

namespace App\Listeners;

use App\Events\AirtimeToCashTransactionEvent;
use App\Events\AirtimeToWalletTransactionEvent;
use App\Events\AirtimeTransactionEvent;
use App\Events\BitcoinTransactionEvent;
use App\Events\CableTransactionEvent;
use App\Events\DataTransactionEvent;
use App\Events\ElectricityTransactionEvent;
use App\Events\GiftcardTransactionEvent;
use App\Events\QuickBuyEvent;
use App\Events\TransferFundTransactionEvent;
use App\Events\WalletTransactionEvent;
use App\Events\WithdrawFundTransactionEvent;
use App\Mail\UserTransactionMails\AirtimeToCashTransactionUserMail;
use App\Mail\UserTransactionMails\AirtimeToWalletTransactionUserMail;
use App\Mail\UserTransactionMails\AirtimeTransactionUserMail;
use App\Mail\UserTransactionMails\BitcoinTransactionUserMail;
use App\Mail\UserTransactionMails\CableTransactionUserMail;
use App\Mail\UserTransactionMails\DataTransactionUserMail;
use App\Mail\UserTransactionMails\ElectricityTransactionUserMail;
use App\Mail\UserTransactionMails\GiftcardTransactionUserMail;
use App\Mail\UserTransactionMails\QuickBuyUserMail;
use App\Mail\UserTransactionMails\TransferFundTransactionUserMail;
use App\Mail\UserTransactionMails\WalletTransactionUserMail;
use App\Mail\UserTransactionMails\WithdrawFundTransactionUserMail;
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
    }
    /**
     *
     */
    public  function  handle(){

    }
}
