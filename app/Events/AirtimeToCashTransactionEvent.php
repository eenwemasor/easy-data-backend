<?php

namespace App\Events;

use App\AirtimeToCashTransaction;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AirtimeToCashTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var AirtimeToCashTransaction
     */
    public $airtimeToCashTransaction;
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
     * @param AirtimeToCashTransaction $airtimeToCashTransaction
     * @param User $user
     * @param User $admin
     */
    public function __construct(AirtimeToCashTransaction $airtimeToCashTransaction, User $user, User $admin)
    {
        //
        $this->airtimeToCashTransaction = $airtimeToCashTransaction;
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
