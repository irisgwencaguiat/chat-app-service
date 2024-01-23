<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRoomUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $userId;
    public $userRoom;

    public function __construct($userId, $userRoom)
    {
      $this->userId = $userId;
      $this->userRoom = $userRoom;
    }


    public function broadcastOn()
    {
        return new Channel("user.room.{$this->userId}");
    }

    public function broadcastAs()
    {
        return 'last-read-at-update';
    }
}
