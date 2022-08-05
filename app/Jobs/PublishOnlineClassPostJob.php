<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

use App\Http\Traits\PusherBeamsTrait;

// Notification
use App\Notifications\OnlinePostPublishedNotification;

// Models
use App\User;
use App\ParentCredential;
use App\StudentCredential;

use App\Models\Student;
use App\Models\OnlinePost;

use Arr;
use Log;

class PublishOnlineClassPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use PusherBeamsTrait;

    /**
     * The podcast instance.
     *
     * @var \App\Models\OnlinePost
     */
    protected $post;
    protected $class;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(OnlinePost $post)
    {
        $this->post  = $post;
        $this->class = $this->post->class;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->class->activeStudentSectionAssignment) {

            $studentnumbers = $this->class->activeStudentSectionAssignment->students;
            
            if(count(json_decode($studentnumbers))>0) {

                $students           = Student::whereIn('studentnumber', json_decode($studentnumbers))
                                        ->has('studentCredential')
                                        ->get();
                $studentCredentials = $students->pluck('studentCredential');

                // Send Notification to Students
                Notification::sendNow($studentCredentials, new OnlinePostPublishedNotification($this->post));

                /**
                 * Execute the Pusher Beams Push Notification.
                 */
                $class      = $this->post->class;
                $class_name = $class->subject ? $class->subject->subject_code : 'Unknown Subject';
                $message    = $this->post->poster_name . ' posted in ' . $class_name;
                $link       = '/online-post/' . $this->post->id . '?class_code=' . $class->code;

                $mobile_interests   = $studentCredentials->pluck('private_mobile_beams');
                $web_interests      = $studentCredentials->pluck('private_web_beams');

                $interests      = Arr::collapse([$mobile_interests, $web_interests]);
                // Log::info($interests);
                $beams_notification   = [
                    "title"       => 'New Class Post',
                    "body"        => $message,
                    "deep_link"   => env('APP_URL') . $link,
                ];
                $beams_data     = [
                    'type'              => 'online-post',
                    'online_post_id'    => $this->post->id,
                    'online_class_id'   => $this->class->id,
                    'online_class_code' => $this->class->code,
                    'link'              => $link,
                ];

                // // Push Notification - Publish To Interests
                $this->publishToInterests($interests, $beams_notification, $beams_data);
            }
        }
        
        Log::info('PublishOnlineClassPostJob');
    }
}
