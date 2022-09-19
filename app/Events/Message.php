<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Message implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $data;
    private $ride_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data,$ride_id)
    {
        $this->data = $data;
        $this->data->ride_id = $ride_id;
        $this->ride_id = $ride_id;
    }

    public function broadcastWith()
    {
        return [
            'data' => $this->data,
            'ride_id' => $this->ride_id,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel($this->ride_id);
    }
}
