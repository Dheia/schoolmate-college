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
use App\Notifications\OnlineClassEndedNotification;

use App\StudentCredential;

// Models
use App\Models\Student;
use App\Models\ZoomMeeting;

use Arr;
use Log;

class EndOnlineClassJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use PusherBeamsTrait;

    /**
     * The podcast instance.
     *
     * @var \App\Models\ZoomMeeting
     */
    protected $zoomMeeting;
    protected $class;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ZoomMeeting $zoomMeeting)
    {
        $this->zoomMeeting  = $zoomMeeting;
        $this->class        = $this->zoomMeeting->meetingable;
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
                Notification::sendNow($studentCredentials, new OnlineClassEndedNotification($this->zoomMeeting, $this->class));

                /**
                 * Execute the Pusher Beams Push Notification.
                 */

                $class_name = $this->class->subject ? $this->class->subject->subject_code : 'Unknown Subject';
                $message    = 'Your ' . $class_name . ' class has ended';
                $link       = '/online-post' . '?class_code=' . $this->class->code;

                $mobile_interests   = $studentCredentials->pluck('private_mobile_beams');
                $web_interests      = $studentCredentials->pluck('private_web_beams');

                $interests      = Arr::collapse([$mobile_interests, $web_interests]);
                // Log::info($interests);
                $notification   = [
                    "title"       => 'Class Ended',
                    "body"        => $message,
                    "deep_link"   => env('APP_URL') . $link
                ];
                $beams_data = [
                    'type' => 'online-class-ended',
                    'zoom_meeting_id'   => $this->zoomMeeting->id,
                    'online_class_id'   => $this->class->id,
                    'online_class_code' => $this->class->code,
                    'link'              => $link
                ];

                // Push Notification - Publish To Interests
                $this->publishToInterests($interests, $notification, $beams_data);

            }
            Log::info('EndOnlineClassJob');
        }
    }
}
