<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StartRideEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function broadcastWith()
    {
        return [
            'data' => $this->data,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if(isset($this->data->id)){
            $id = $this->data->id;
        }elseif(isset($this->data['ride']->id)){
            $id = $this->data['ride']->id;
        }
        else{
            $id = $this->data['id'];
        }
        return new Channel($id);
    }
}
