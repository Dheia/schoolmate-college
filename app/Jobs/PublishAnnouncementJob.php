<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Builder;

use App\Notifications\GlobalMessage;
use App\Events\ReloadEmployeeNotification;
use App\Events\ReloadStudentNotification;
use App\Events\ReloadParentNotification;

// Models
use App\User;
use App\ParentCredential;
use App\StudentCredential;

use App\Models\Student;
use App\Models\SchoolYear;
use App\Models\Announcement;
use App\Models\ParentStudent;

use App\Http\Traits\PusherBeamsTrait;

use Log;

class PublishAnnouncementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use PusherBeamsTrait;

    /**
     * The podcast instance.
     *
     * @var \App\Models\Announcement
     */
    protected $announcement;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->schoolYear = SchoolYear::where('isActive', 1)->first();

        $link       =  '/announcement/' . $this->announcement->id;
        $beams_data = [
            'type'              => 'announcement',
            'announcement_id'   => $this->announcement->id,
            'link'              => $link,
        ];

        switch ($this->announcement->audience) {
            case 'global':

                $interests      = [
                    "mob-" . env('SCHOOL_ID') . "-student",
                    "web-" . env('SCHOOL_ID') . "-employee",
                    "web-" . env('SCHOOL_ID') . "-student",
                    "web-" . env('SCHOOL_ID') . "-parent"
                ];
                $notification   = [
                    "title"       => 'Announcement',
                    "body"        => $this->announcement->message,
                    "deep_link"   => env('APP_URL') . $link,
                ];

                // Push Notification - Publish To Interests
                $this->publishToInterests($interests, $notification, $beams_data);

                // Get Users
                $users          =   User::has('employee')->get();

                // Get Students
                $students       =   Student::whereHas('enrollments', function (Builder $query) {
                                        $query->where('school_year_id', $this->schoolYear->id)->where('deleted_at', null);
                                    })->has('studentCredential')->get();

                // Get Parents
                $parent_student =   ParentStudent::has('parent')->whereIn('student_id', $students->pluck('id'))->get();

                $parents        =   ParentCredential::whereIn('parent_user_id', $parent_student->pluck('parent_user_id'))->get();


                // Notify Users
                Notification::sendNow($users, new GlobalMessage($this->announcement->id, $this->announcement->message, $this->announcement->audience));
                ReloadEmployeeNotification::dispatch();
                // foreach ($users as $user) {
                //     $user->notify(new GlobalMessage($this->announcement->id, $this->announcement->message));
                // }
                
                // Notify Parents
                Notification::sendNow($parents, new GlobalMessage($this->announcement->id, $this->announcement->message, $this->announcement->audience));
                ReloadParentNotification::dispatch();
                // foreach ($parents as $parent) {
                //     $parent->notify(new GlobalMessage($this->announcement->id, $this->announcement->message));
                // }

                // Notify Students        
                Notification::sendNow($students->pluck('studentCredential'), new GlobalMessage($this->announcement->id, $this->announcement->message, $this->announcement->audience));
                ReloadStudentNotification::dispatch();
                // foreach ($students as $student) {
                //     $student->notify(new GlobalMessage($this->announcement->id, $this->announcement->message));
                // }

                $log = [
                    'Users'     => $users->count(),
                    'Students'  => $students->count(),
                    'Parents'   => $parents->count(),
                ];
                Log::info($log);

                break;

            case 'employee':

                $interests      = [
                    "web-" . env('SCHOOL_ID') . "-employee"
                ];
                $notification   = [
                    "title"       => 'Announcement',
                    "body"        => $this->announcement->message,
                    "deep_link"   => env('APP_URL') . $link,
                ];

                // Push Notification - Publish To Interests
                $this->publishToInterests($interests, $notification, $beams_data);

                // Notify Users
                $users = User::has('employee')->get();
                Notification::sendNow($users, new GlobalMessage($this->announcement->id, $this->announcement->message, $this->announcement->audience));
                ReloadEmployeeNotification::dispatch();
                // foreach ($users as $user) {
                //     $user->notify(new GlobalMessage($this->announcement->id, $this->announcement->message));
                // }

                $log = [
                    'Users'     => $users->count()
                ];
                Log::info($log);

                break;

            case 'parent':

                $interests      = [
                    "global",
                    "web-" . env('SCHOOL_ID') . "-parent"
                ];
                $notification   = [
                    "title"       => 'Announcement',
                    "body"        => $this->announcement->message,
                    "deep_link"   => env('APP_URL') . $link,
                ];

                // Push Notification - Publish To Interests
                $this->publishToInterests($interests, $notification, $beams_data);

                // Get Students
                $students       =   Student::whereHas('enrollments', function (Builder $query) {
                                        $query->where('school_year_id', $this->schoolYear->id)->where('deleted_at', null);
                                    })->has('studentCredential')->get();
                
                // Get Students Parents
                $parent_student =   ParentStudent::has('parent')->whereIn('student_id', $students->pluck('id'))->get();

                $parents        =   ParentCredential::whereIn('parent_user_id', $parent_student->pluck('parent_user_id'))->get();

                // Notify Parents
                Notification::sendNow($parents, new GlobalMessage($this->announcement->id, $this->announcement->message, $this->announcement->audience));
                ReloadParentNotification::dispatch();
                // foreach ($parents as $parent) {
                //     $parent->notify(new GlobalMessage($this->announcement->id, $this->announcement->message));
                // }

                $log = [
                    'Parents'   => $parents->count(),
                ];
                Log::info($log);

                break;

            case 'student':

                $interests      = [
                    "mob-" . env('SCHOOL_ID') . "-student",
                    "web-" . env('SCHOOL_ID') . "-student"
                ];
                $notification   = [
                    "title"       => 'Announcement',
                    "body"        => $this->announcement->message,
                    "deep_link"   => env('APP_URL') . $link,
                ];

                // Push Notification - Publish To Interests
                $this->publishToInterests($interests, $notification, $beams_data);

                // Notify Students
                $students = Student::whereHas('enrollments', function (Builder $query) {
                    $query->where('school_year_id', $this->schoolYear->id)->where('deleted_at', null);
                })->has('studentCredential')->get();
        
                Notification::sendNow($students->pluck('studentCredential'), new GlobalMessage($this->announcement->id, $this->announcement->message, $this->announcement->audience));
                ReloadStudentNotification::dispatch();
                // foreach ($students as $student) {
                //     $student->notify(new GlobalMessage($this->announcement->id, $this->announcement->message));
                // }

                $log = [
                    'Students'  => $students->count(),
                ];
                Log::info($log);

                break;
            
            default: 

                break;
        }
    }
}
