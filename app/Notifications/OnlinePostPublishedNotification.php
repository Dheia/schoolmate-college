<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Queue\SerializesModels;

use Illuminate\Notifications\Messages\MailMessage;
use App\Http\Traits\PusherBeamsTrait;

// Models
use App\Models\OnlinePost;

use App\User;
use App\StudentCredential;
use App\ParentCredential;

use Log;
use DB;

class OnlinePostPublishedNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;
    use PusherBeamsTrait;

    public $post;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $class      = $this->post->class;
        $class_name = $class->subject ? $class->subject->subject_code : 'Unknown Subject';

        $message    = $this->post->poster_name . ' posted in ' . $class_name;
        $link       = '/online-post/' . $this->post->id . '?class_code=' . $class->code . '&notification_id=' . $this->id;

        return [
            'type'      => 'online-post',
            'id'        => $this->post->id,
            'message'   => $message,
            'link'      => $link,
            'others'    => [
                'online_class_id'   => $this->post->online_class_id,
                'class_code'        => $class->code,
                'class_name'        => $class_name
            ]
        ];
    }

    public function toDatabase($notifiable)
    {
        $class      = $this->post->class;
        $class_name = $class->subject ? $class->subject->subject_code : 'Unknown Subject';

        $message    = $this->post->poster_name . ' posted in ' . $class_name;
        $link       = '/online-post/' . $this->post->id . '?class_code=' . $class->code . '&notification_id=' . $this->id;

        return [
            'type'      => 'online-post',
            'id'        => $this->post->id,
            'message'   => $message,
            'link'      => $link,
            'others'    => [
                'online_class_id'   => $this->post->online_class_id,
                'class_code'        => $class->code,
                'class_name'        => $class_name
            ]
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        $this->notification =    DB::table('notifications')
                                    ->where('id', $this->id)
                                    ->first();

        $class      = $this->post->class;
        $class_name = $class->subject ? $class->subject->subject_code : 'Unknown Subject';

        $message    = $this->post->poster_name . ' posted in ' . $class_name;
        $link       = '/online-post/' . $this->post->id . '?class_code=' . $class->code . '&notification_id=' . $this->notification->id;

        if($this->notification->notifiable_type == 'App\\User') {
            $user = User::where('id', $notifiable->id)->first();
        }

        if($this->notification->notifiable_type == 'App\\StudentCredential') {
            $user = StudentCredential::where('id', $notifiable->id)->first();
        
            // $interests      = [
            //     "mob-" . env('SCHOOL_ID') . "-student-" . $user->student->id,
            //     "web-" . env('SCHOOL_ID') . "-student-" . $user->student->id
            // ];
            // $beams_data   = [
            //     "title"       => 'New Class Post',
            //     "body"        => $message,
            //     "deep_link"   => env('APP_URL') . $link,
            // ];

            // // Push Notification - Publish To Interests
            // $this->publishToInterests($interests, $beams_data);
        }

        $message = [
            'id'                => $this->notification->id,
            'type'              => $this->notification->type,
            'notifiable_type'   => $this->notification->notifiable_type,
            'notifiable_id'     => $this->notification->notifiable_id,
            'data'              => $this->notification->data,
            'created_at'        => $this->notification->created_at
        ];

        return (new BroadcastMessage($message));
    }
}