<?php

namespace App\Events;

use App\CableTransaction;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CableTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var CableTransaction
     */
    public $cableTransaction;
    /**
     * @var User
     */
    public $user;
    public $admin;

    /**
     * Create a new event instance.
     *
     * @param CableTransaction $cableTransaction
     * @param User $user
     * @param $admin
     */
    public function __construct(CableTransaction $cableTransaction, User $user, $admin)
    {
        //
        $this->cableTransaction = $cableTransaction;
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
