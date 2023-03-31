<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class videoChannel implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
      public $channel_id;
      public $action;
      public $sender_id;
      public $name;
      public $image;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($channel_id,$action,$sender_id,$name,$image)
    {
        $this->channel_id=$channel_id;
        $this->action=$action;
        $this->sender_id=$sender_id;
        $this->name=$name;
        $this->image=$image;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['my-video-action'];
    }

    public function broadcastAs()
  {
      return 'my-video-action';
  }
}
