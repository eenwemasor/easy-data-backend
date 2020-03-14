<?php

namespace App\Events;

use App\AirtimeToWalletTransaction;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AirtimeToWalletTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var AirtimeToWalletTransaction
     */
    public $airtimeToWalletTransaction;
    /**
     * @var User
     */
    public $user;
    /**
     * @var User
     */
    public $admin;

    /**
     * Create a new event instance.
     *
     * @param AirtimeToWalletTransaction $airtimeToWalletTransaction
     * @param User $user
     * @param User $admin
     */
    public function __construct(AirtimeToWalletTransaction $airtimeToWalletTransaction, User $user, User $admin)
    {
        //
        $this->airtimeToWalletTransaction = $airtimeToWalletTransaction;
        $this->user = $user;
        $this->admin = $admin;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
