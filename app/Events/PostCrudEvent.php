<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostCrudEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;
    public $action;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($post, $action)
    {
        $this->post     =   $post;
        $this->action   =   $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channel    =  env('SCHOOL_ID') . '-' . strtolower($this->action) . '-post-channel';
        return new Channel($channel);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        $broadcastAs   =  env('SCHOOL_ID')  .  ucfirst($this->action) . 'PostEvent';
        return  $broadcastAs;
    }

    public function broadcastWith()
    {
        return ['data' => $this->post];
        // $posts = OnlinePost::paginate(2);
        // $posts->setPath(url()->current());   
        // return response()->json(['posts' => $posts]);
    }
}
