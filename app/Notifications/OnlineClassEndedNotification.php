<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

// Models
use App\User;
use App\StudentCredential;
use App\ParentCredential;

use Log;
use DB;

class OnlineClassEndedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $zoomMeeting;
    protected $class;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($zoomMeeting, $class)
    {
        $this->zoomMeeting  = $zoomMeeting;
        $this->class        = $class;
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
        $class_name = $this->class->subject ? $this->class->subject->subject_code : 'Unknown Subject';

        $message    = 'Your ' . $class_name . ' class has ended';
        $link       = '/online-post' . '?class_code=' . $this->class->code . '&notification_id=' . $this->id;

        return [
            'type'      => 'online-class-ended',
            'id'        => $this->class->id,
            'message'   => $message,
            'link'      => $link,
            'others'    => [
                'online_class_id'   => $this->class->id,
                'class_code'        => $this->class->code,
                'class_name'        => $class_name,
                'zoom_meeting_id'   => $this->zoomMeeting->id
            ]
        ];
    }

    public function toDatabase($notifiable)
    {
        $class_name = $this->class->subject ? $this->class->subject->subject_code : 'Unknown Subject';

        $message    = 'Your ' . $class_name . ' class has ended';
        $link       = '/online-post' . '?class_code=' . $this->class->code . '&notification_id=' . $this->id;

        return [
            'type'      => 'online-class-ended',
            'id'        => $this->class->id,
            'message'   => $message,
            'link'      => $link,
            'others'    => [
                'online_class_id'   => $this->class->id,
                'class_code'        => $this->class->code,
                'class_name'        => $class_name,
                'zoom_meeting_id'   => $this->zoomMeeting->id
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

        if($this->notification->notifiable_type == 'App\\User') {
            $user = User::where('id', $notifiable->id)->first();
        }

        if($this->notification->notifiable_type == 'App\\StudentCredential') {
            $user = StudentCredential::where('id', $notifiable->id)->first();
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
