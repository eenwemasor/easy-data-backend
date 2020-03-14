<?php

namespace App\Events;

use App\BitcoinTransaction;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BitcoinTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var BitcoinTransaction
     */
    public $bitcoinTransaction;
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
     * @param BitcoinTransaction $bitcoinTransaction
     * @param User $user
     * @param User $admin
     */
    public function __construct(BitcoinTransaction $bitcoinTransaction, User $user, User $admin)
    {
        //
        $this->bitcoinTransaction = $bitcoinTransaction;
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
