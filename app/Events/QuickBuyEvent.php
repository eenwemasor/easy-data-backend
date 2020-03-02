<?php

namespace App\Events;

use App\QuickBuy;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuickBuyEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var QuickBuy
     */
    public $quickBuy;
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
     * @param QuickBuy $quickBuy
     * @param  $user
     * @param User $admin
     */
    public function __construct(QuickBuy $quickBuy, $user, User $admin)
    {
        //
        $this->quickBuy = $quickBuy;
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
