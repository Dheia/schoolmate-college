<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentCrudEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;
    public $action;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($comment, $action)
    {
        $this->comment  =   $comment;
        $this->action   =   $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channel    =  env('SCHOOL_ID') . '-' . strtolower($this->action) . '-comment-channel';
        return new Channel($channel);
        // return new Channel(env('SCHOOL_ID') . '-' . 'new-comment-channel');
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        $broadcastAs   =  env('SCHOOL_ID')  .  ucfirst($this->action) . 'CommentEvent';
        return  $broadcastAs;
        // return  env('SCHOOL_ID')  . 'NewCommentEvent';
    }

    public function broadcastWith()
    {
        return ['data' => $this->comment];
    }
}
