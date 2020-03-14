<?php

namespace App\Events;

use App\User;
use App\UserBank;
use App\WithdrawFundTransaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WithdrawFundTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var WithdrawFundTransaction
     */
    public $withdrawFundTransaction;
    /**
     * @var User
     */
    public $user;
    /**
     * @var User
     */
    public $admin;
    /**
     * @var UserBank
     */
    public $bank;

    /**
     * Create a new event instance.
     *
     * @param WithdrawFundTransaction $withdrawFundTransaction
     * @param User $user
     * @param User $admin
     * @param UserBank $bank
     */
    public function __construct(WithdrawFundTransaction $withdrawFundTransaction, User $user, User $admin, UserBank $bank)
    {
        //
        $this->withdrawFundTransaction = $withdrawFundTransaction;
        $this->user = $user;
        $this->admin = $admin;
        $this->bank = $bank;
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
