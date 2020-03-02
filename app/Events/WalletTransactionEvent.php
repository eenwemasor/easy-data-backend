<?php

namespace App\Events;

use App\User;
use App\WalletTransaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WalletTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var WalletTransaction
     */
    public $walletTransaction;
    /**
     * @var User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param WalletTransaction $walletTransaction
     * @param User $user
     */
    public function __construct(WalletTransaction $walletTransaction, User $user)
    {
        //
        $this->walletTransaction = $walletTransaction;
        $this->user = $user;
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
