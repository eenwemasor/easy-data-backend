<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TalkToUsMessageEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $message;
    /**
     * @var string
     */
    public $recipient;

    /**
     * Create a new event instance.
     *
     * @param string $recipient
     * @param string $name
     * @param string $email
     * @param string $message
     */
    public function __construct(string $recipient, string $name, string $email, string $message)
    {
        //
        $this->name = $name;
        $this->email = $email;
        $this->message = $message;
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
