<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

use Illuminate\Notifications\Messages\MailMessage;

use App\User;
use App\StudentCredential;
use App\ParentCredential;

use Log;
use DB;

class GlobalMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public $data;
    public $notification;
    public $notification_id;
    public $message;
    public $audience;
    public $schoolid;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notification_id, $message, $audience = null)
    {
        //
        $this->notification_id = $notification_id;
        $this->message = $message;
        $this->audience = $audience;
        $this->schoolid = env('SCHOOL_ID');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
        return [
            'type'      => 'announcement',
            'id'        => $this->notification_id,
            'message'   => $this->message,
            'link'      => '/announcement/' . $this->notification_id . '?notification_id=' . $this->id,
            'others'    => [
                'audience' => $this->audience
            ]
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type'      => 'announcement',
            'id'        => $this->notification_id,
            'message'   => $this->message,
            'link'      => '/announcement/' . $this->notification_id . '?notification_id=' . $this->id,
            'others'    => [
                'audience' => $this->audience
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

        if($this->notification->notifiable_type == 'App\\ParentCredential') {
            $user = ParentCredential::where('id', $notifiable->id)->first();
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