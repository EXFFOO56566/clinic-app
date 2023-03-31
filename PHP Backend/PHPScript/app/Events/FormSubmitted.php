<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FormSubmitted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
      public $title;
      public $messages;
      public $reciver_id;
      public $image;
      public $sender_id;
      public $time;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($title,$messages,$reciver_id,$image,$sender_id,$time)
    {
        $this->title=$title;
        $this->messages=$messages;
        $this->reciver_id=$reciver_id;
        $this->image=$image;
        $this->sender_id=$sender_id;
        $this->time=$time;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['my-channel'];
    }

    public function broadcastAs()
  {
      return 'my-channel';
  }
}
