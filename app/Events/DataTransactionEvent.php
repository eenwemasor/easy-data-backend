<?php

namespace App\Events;

use App\DataTransaction;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DataTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var DataTransaction
     */
    public $dataTransaction;
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
     * @param DataTransaction $dataTransaction
     * @param User $user
     * @param User $admin
     */
    public function __construct(DataTransaction $dataTransaction, User $user, User $admin)
    {
        //
        $this->dataTransaction = $dataTransaction;
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
