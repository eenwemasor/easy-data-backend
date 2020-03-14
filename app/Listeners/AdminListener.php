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
use App\Mail\AdminTransactionMails\AirtimeToCashTransactionAdminMail;
use App\Mail\AdminTransactionMails\AirtimeToWalletTransactionAdminMail;
use App\Mail\AdminTransactionMails\AirtimeTransactionAdminMail;
use App\Mail\AdminTransactionMails\BitcoinTransactionAdminMail;
use App\Mail\AdminTransactionMails\CableTransactionAdminMail;
use App\Mail\AdminTransactionMails\DataTransactionAdminMail;
use App\Mail\AdminTransactionMails\ElectricityTransactionAdminMail;
use App\Mail\AdminTransactionMails\GiftcardTransactionAdminMail;
use App\Mail\AdminTransactionMails\QuickBuyAdminMail;
use App\Mail\AdminTransactionMails\TransferFundTransactionAdminMail;
use App\Mail\AdminTransactionMails\WithdrawFundTransactionAdminMail;
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
     * @param AirtimeToCashTransactionEvent $event
     */
    function notifyAdminOfAirtimeToCashTransaction(AirtimeToCashTransactionEvent $event) {
        $admin = $event->admin;
        $user = $event->user;
        $airtime_to_cash_transaction = $event->airtimeToCashTransaction;

        sleep(3);
        Mail::to($admin)->send(new AirtimeToCashTransactionAdminMail($admin, $airtime_to_cash_transaction,$user));
    }


    /**
     * @param AirtimeToWalletTransactionEvent $event
     */
    function notifyAdminOfAirtimeToWalletTransaction(AirtimeToWalletTransactionEvent $event) {
        $admin = $event->admin;
        $user = $event->user;
        $airtime_to_wallet_transaction = $event->airtimeToWalletTransaction;

        sleep(3);
        Mail::to($admin)->send(new AirtimeToWalletTransactionAdminMail($admin, $airtime_to_wallet_transaction,$user));
    }

    /**
     * @param BitcoinTransactionEvent $event
     */
    function notifyAdminOfBitcoinTransaction(BitcoinTransactionEvent $event) {
        $admin = $event->admin;
        $user = $event->user;
        $bitcoin_transaction = $event->bitcoinTransaction;

        sleep(3);
        Mail::to($admin)->send(new BitcoinTransactionAdminMail($admin, $bitcoin_transaction,$user));
    }


    /**
     * @param GiftcardTransactionEvent $event
     */
    function notifyAdminOfGiftcardTransaction(GiftcardTransactionEvent $event) {
        $admin = $event->admin;
        $user = $event->user;
        $gift_card_transaction = $event->giftcardTransaction;

        sleep(3);
        Mail::to($admin)->send(new GiftcardTransactionAdminMail($admin, $gift_card_transaction,$user));
    }

    /**
     * @param TransferFundTransactionEvent $event
     */
    function notifyAdminOfTransferFundTransaction(TransferFundTransactionEvent $event) {
        $admin = $event->admin;
        $user = $event->user;
        $recipient = $event->recipient;
        $transfer_fund_transaction = $event->transferFundTransaction;

        sleep(3);
        Mail::to($admin)->send(new TransferFundTransactionAdminMail($admin, $transfer_fund_transaction,$user,$recipient));
    }


    /**
     * @param WithdrawFundTransactionEvent $event
     */
    function notifyAdminOfWithdrawFundTransaction(WithdrawFundTransactionEvent $event) {
        $admin = $event->admin;
        $user = $event->user;
        $bank = $event->bank;
        $withdraw_fund_transaction = $event->withdrawFundTransaction;

        sleep(3);
        Mail::to($admin)->send(new WithdrawFundTransactionAdminMail($admin, $withdraw_fund_transaction,$user,$bank));
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

        $events->listen(
            'App\Events\AirtimeToCashTransactionEvent',
            'App\Listeners\AdminListener@notifyAdminOfAirtimeToCashTransaction'
        );
        $events->listen(
            'App\Events\AirtimeToWalletTransactionEvent',
            'App\Listeners\AdminListener@notifyAdminOfAirtimeToWalletTransaction'
        );
        $events->listen(
            'App\Events\BitcoinTransactionEvent',
            'App\Listeners\AdminListener@notifyAdminOfBitcoinTransaction'
        );
        $events->listen(
            'App\Events\GiftcardTransactionEvent',
            'App\Listeners\AdminListener@notifyAdminOfGiftcardTransaction'
        );
        $events->listen(
            'App\Events\TransferFundTransactionEvent',
            'App\Listeners\AdminListener@notifyAdminOfTransferFundTransaction'
        );$events->listen(
            'App\Events\WithdrawFundTransactionEvent',
            'App\Listeners\AdminListener@notifyAdminOfWithdrawFundTransaction'
        );
    }

    /**
     *
     */
    public  function  handle(){

    }

}
