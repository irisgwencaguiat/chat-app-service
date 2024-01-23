<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $roomId;
    public $message;
    /**
     * Create a new event instance.
     */
    public function __construct($roomId, $message)
    {
        $this->roomId = $roomId;
        $this->message = $message;
    }


    public function broadcastOn()
    {
        return new Channel("room.{$this->roomId}");
    }

    public function broadcastAs()
    {
        return 'message';
    }
}
