<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LatestRoom implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $room;
    /**
     * Create a new event instance.
     */
    public function __construct($userId, $room)
    {
     $this->userId = $userId;
     $this->room = $room;
    }

    public function broadcastOn()
    {
        return new Channel("latest.room.user.{$this->userId}");

    }

    public function broadcastAs()
    {
        return 'latest-room';
    }
}
