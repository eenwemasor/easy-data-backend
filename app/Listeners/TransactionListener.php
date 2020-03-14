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
     * @param AirtimeToCashTransactionEvent $event
     */
    function notifyUserOfAirtimeToCashTransaction(AirtimeToCashTransactionEvent $event) {
        $user = $event->user;
        $airtime_to_cash_transaction = $event->airtimeToCashTransaction;

        sleep(3);
        Mail::to($user)->send(new AirtimeToCashTransactionUserMail($user, $airtime_to_cash_transaction));
    }


    /**
     * @param AirtimeToWalletTransactionEvent $event
     */
    function notifyUserOfAirtimeToWalletTransaction(AirtimeToWalletTransactionEvent $event) {
        $user = $event->user;
        $airtime_to_wallet_transaction = $event->airtimeToWalletTransaction;

        sleep(3);
        Mail::to($user)->send(new AirtimeToWalletTransactionUserMail($user, $airtime_to_wallet_transaction));
    }

    /**
     * @param BitcoinTransactionEvent $event
     */
    function notifyUserOfBitcoinTransaction(BitcoinTransactionEvent $event) {
        $user = $event->user;
        $bitcoin_transaction = $event->bitcoinTransaction;

        sleep(3);
        Mail::to($user)->send(new BitcoinTransactionUserMail($user, $bitcoin_transaction));
    }


    function notifyUserOfGiftcardTransaction(GiftcardTransactionEvent $event) {
        $user = $event->user;
        $gift_card_transaction = $event->giftcardTransaction;

        sleep(3);
        Mail::to($user)->send(new GiftcardTransactionUserMail($user, $gift_card_transaction));
    }

    /**
     * @param TransferFundTransactionEvent $event
     */
    function notifyUserOfTransferFundTransaction(TransferFundTransactionEvent $event) {
        $user = $event->user;
        $recipient = $event->recipient;
        $transfer_fund_transaction = $event->transferFundTransaction;

        sleep(3);
        Mail::to($user)->send(new TransferFundTransactionUserMail($user, $transfer_fund_transaction,$recipient));
    }


    /**
     * @param WithdrawFundTransactionEvent $event
     */
    function notifyUserOfWithdrawFundTransaction(WithdrawFundTransactionEvent $event) {
        $user = $event->user;
        $bank = $event->bank;
        $withdraw_fund_transaction = $event->withdrawFundTransaction;

        sleep(3);
        Mail::to($user)->send(new WithdrawFundTransactionUserMail($user, $withdraw_fund_transaction,$bank));
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

        $events->listen(
            'App\Events\AirtimeToCashTransactionEvent',
            'App\Listeners\TransactionListener@notifyUserOfAirtimeToCashTransaction'
        );
        $events->listen(
            'App\Events\AirtimeToWalletTransactionEvent',
            'App\Listeners\TransactionListener@notifyUserOfAirtimeToWalletTransaction'
        );
        $events->listen(
            'App\Events\BitcoinTransactionEvent',
            'App\Listeners\TransactionListener@notifyUserOfBitcoinTransaction'
        );$events->listen(
            'App\Events\GiftcardTransactionEvent',
            'App\Listeners\TransactionListener@notifyUserOfGiftcardTransaction'
        );
        $events->listen(
            'App\Events\TransferFundTransactionEvent',
            'App\Listeners\TransactionListener@notifyUserOfTransferFundTransaction'
        );$events->listen(
            'App\Events\WithdrawFundTransactionEvent',
            'App\Listeners\TransactionListener@notifyUserOfWithdrawFundTransaction'
        );
    }
    /**
     *
     */
    public  function  handle(){

    }
}
