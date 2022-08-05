<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\Meeting;
use App\Models\StudentsMeeting;
use App\Models\Enrollment;

use App\Http\Controllers\BBB;

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetRecordingsParameters;

class MeetingController extends Controller
{
    public function index()
    {
    	$title       				= 	"Meeting";
    	$student                    =   auth()->user()->student;
        $meetings   				= 	$this->getMyMeetings();
        // $video_conference_status 	= 	$this->getVideoConferenceStatus();
        return view('student.meeting.dashboard', compact(['title', 'student', 'meetings']));
    }

    public function getMyMeetings()
    {
        $meeting_tag 	= 	StudentsMeeting::where('student_id', auth()->user()->student->id)->get();
        $meetings    	=  	Meeting::with(['employee'])
                                ->whereIn('id', $meeting_tag->pluck('meeting_id'))
                                ->orderBy('name')
                                ->get();
        return $meetings;
    }

    // public function joinConference($code)
    // {
    //     $student = auth()->user()->student;
    //     $url = BBB::joinEmployeeVideoConference($code, $student->fullname);
    //     return redirect()->to($url);
    // }

    public function joinConference($code)
    {
        $my_meetings  = $this->getMyMeetings();
        $meeting   = Meeting::where('code', $code)->first();

        if(! $meeting) {
            \Alert::warning("Meeting Not Found.")->flash();
            return redirect()->to('/student/meeting');
        }
        if(! in_array($meeting->id, $my_meetings->pluck('id')->toArray())) {
            \Alert::warning("Invalid Meeting.")->flash();
            return redirect()->to('/student/meeting');
        }
        if(! $meeting->conference_status) {
            \Alert::warning("Meeting is not yet Ongoing.")->flash();
            return redirect()->to('/student/meeting');
        }
        if($meeting->join_url) {
            return redirect()->to($meeting->join_url);
        }

        \Alert::warning("Meeting Join URL Not Found.")->flash();
        return redirect()->to('/student/meeting');
    }

    public function getVideoConferenceStatus(){

        $meetings = $this->getMyMeetings();
        $status = [];

        if($meetings){
           if(count($meetings) > 0){
                foreach ($meetings as $meeting) {
                    $meetingId = $meeting->code;
                    $password = "teacher-" . $meeting->code;
                    $video_conference_info = BBB::getConferenceStatus($meetingId, $password);
                    $status[] = [
                        'meetingId' => $meeting->code,
                        'data' => gettype($video_conference_info) == "object" ? $video_conference_info : null
                    ];
                }
            } 
        }

        return $status;
    }
}
