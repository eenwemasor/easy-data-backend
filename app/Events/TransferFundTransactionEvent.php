<?php

namespace App\Events;

use App\TransferFundTransaction;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransferFundTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var TransferFundTransaction
     */
    public $transferFundTransaction;
    /**
     * @var User
     */
    public $user;
    /**
     * @var User
     */
    public $admin;
    /**
     * @var User
     */
    public $recipient;

    /**
     * Create a new event instance.
     *
     * @param TransferFundTransaction $transferFundTransaction
     * @param User $user
     * @param User $admin
     * @param User $recipient
     */
    public function __construct(TransferFundTransaction $transferFundTransaction , User $user, User $admin, User $recipient)
    {
        //
        $this->transferFundTransaction = $transferFundTransaction;
        $this->user = $user;
        $this->admin = $admin;
        $this->recipient = $recipient;
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
