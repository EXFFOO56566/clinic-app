<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActionChannel implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
      public $channel_id;
      public $messages;
      public $action;
      public $rowid;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($channel_id,$messages,$action,$rowid)
    {
        $this->channel_id=$channel_id;
        $this->messages=$messages;
        $this->action=$action;
        $this->rowid=$rowid;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['my-channel-action'];
    }

    public function broadcastAs()
  {
      return 'my-channel-action';
  }
}
