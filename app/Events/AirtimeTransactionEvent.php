<?php

namespace App\Events;

use App\AirtimeTransaction;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AirtimeTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var \App\AirtimeTransaction
     */
    public $airtimeTransaction;
    /**
     * @var User
     */
    public $user;
    public $admin;


    /**
     * AirtimeTransactionEvent constructor.
     * @param AirtimeTransaction $airtimeTransaction
     * @param User $user
     * @param $admin
     */
    public function __construct(AirtimeTransaction $airtimeTransaction, User $user, $admin)
    {

        $this->airtimeTransaction = $airtimeTransaction;
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
