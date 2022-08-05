<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use Illuminate\Support\Facades\Redirect;
use Config\Config;

use App\Models\UsersMeeting;
use App\Models\Employee;
use App\Models\Meeting;

class BBB extends Controller
{

    //
    public static function createMeeting($meetingId, $class){
        $bbb = new BigBlueButton();

        // dd($class->teacher->full_name);

        $meetingID = $meetingId;
        $meetingName = $class->name;
        $attendee_password = 'student-'.$meetingId;
        $moderator_password = 'teacher-'.$meetingId;
    
        // dd(get_class_methods(backpack_auth()));

        $isStudent = false;
        
        $urlLogout = env('APP_URL').'/login/';

        // If true redirect is admin
        if(backpack_auth()->check()) {
            $urlLogout = env('APP_URL').'/admin/teacher-online-class/';
        }
        $user = backpack_auth()->user()->full_name;
        
        $isRecordingTrue = true;
        
        $createMeetingParams = new CreateMeetingParameters($meetingID, $meetingName);

        
        $createMeetingParams->setAttendeePassword($attendee_password);
        $createMeetingParams->setModeratorPassword($moderator_password);
        $createMeetingParams->setLogo(env('APP_URL').'/'.\Config::get('settings.schoollogo'));
        $createMeetingParams->setLogoutUrl($urlLogout);
        $createMeetingParams->setWelcomeMessage("Welcome to SchoolMATE Online <br/>" .  $class->description);
        $createMeetingParams->setDuration(300);
        if ($isRecordingTrue) {
            $createMeetingParams->setRecord(true);
            $createMeetingParams->setAllowStartStopRecording(true);
            $createMeetingParams->setAutoStartRecording(true);
        }

        $response = $bbb->createMeeting($createMeetingParams);

        if ($response->getReturnCode() == 'FAILED') {
            return 'Can\'t create room! please contact our administrator.';
        } else {
            if(!$isStudent){
                // $moderator_password for moderator
                $joinMeetingParams = new JoinMeetingParameters($meetingId, $meetingName, $moderator_password);
                $joinMeetingParams->setRedirect(true);
                $joinMeetingParams->setUsername($user);
                $url = $bbb->getJoinMeetingURL($joinMeetingParams);
                // dd($url);
                // echo "<a href='".$url ."' target='_blank'>Click here to begin conference call</a>" ;
                return redirect()->intended($url);
            }
            
            
        }
    }


    public static function getConferenceStatus($meetingId, $password) {
        $bbb = new BigBlueButton();

        $getMeetingInfoParams = new GetMeetingInfoParameters($meetingId, $password);
        
        $response = $bbb->getMeetingInfo($getMeetingInfoParams);
        
        if ($response->getReturnCode() == 'FAILED') {
            return false;
        } else {
            return response()->json($response->getRawXml());
        }

       
        

    }

    public function endMeeting($meetingId,$password) {
        
        $bbb = new BigBlueButton();

        $endMeetingParams = new EndMeetingParameters($meetingId, $password);
        $response = $bbb->endMeeting($endMeetingParams);
        dd($response);
    }


    public static function joinVideoConference($code, $student){
        
       
        $bbb = new BigBlueButton();

        $password = "student-".$code;

        // $moderator_password for moderator
        $joinMeetingParams = new JoinMeetingParameters($code, $student, $password);
        $joinMeetingParams->setRedirect(true);

        $url = $bbb->getJoinMeetingURL($joinMeetingParams);

        return $url;
    }

    // EMPLOYEE MEETING
    public static function createEmployeeMeeting($meetingId, $meeting){
        $bbb = new BigBlueButton();


        $meetingID = $meetingId;
        $meetingName = $meeting->name;
        $attendee_password = 'employee-'.$meeting->code;
        $moderator_password = 'teacher-'.$meetingId;


        // dd(get_class_methods(backpack_auth()));

        $usersMeeting = UsersMeeting::where('meeting_id', $meeting->id)->where('employee_id', backpack_auth()->user()->employee->id)->first();
        if(!$usersMeeting)
        {
            $usersMeeting = Meeting::where('id', $meeting->id)->where('employee_id', backpack_auth()->user()->employee->id)->first();
        }
        $urlLogout = env('APP_URL').'/login/';

        // If true redirect is admin
        if(backpack_auth()->check()) {
            $urlLogout = env('APP_URL').'/admin/dashboard/';
        }
        $user = backpack_auth()->user()->full_name;
        
        $isRecordingTrue = true;
        
        $createMeetingParams = new CreateMeetingParameters($meetingID, $meetingName);

        
        $createMeetingParams->setAttendeePassword($attendee_password);
        $createMeetingParams->setModeratorPassword($moderator_password);
        $createMeetingParams->setLogo(env('APP_URL').'/'.\Config::get('settings.schoollogo'));
        $createMeetingParams->setLogoutUrl($urlLogout);
        $createMeetingParams->setWelcomeMessage("Welcome to SchoolMATE Online <br/>" .  $meeting->description);
        $createMeetingParams->setDuration(300);
        if ($isRecordingTrue) {
            $createMeetingParams->setRecord(true);
            $createMeetingParams->setAllowStartStopRecording(true);
            $createMeetingParams->setAutoStartRecording(true);
        }

        $response = $bbb->createMeeting($createMeetingParams);

        if ($response->getReturnCode() == 'FAILED') {
            return 'Can\'t create room! please contact our administrator.';
        } else {
            if($usersMeeting){
                // $moderator_password for moderator
                $joinMeetingParams = new JoinMeetingParameters($meetingId, $meetingName, $moderator_password);
                $joinMeetingParams->setRedirect(true);
                $joinMeetingParams->setUsername($user);
                $url = $bbb->getJoinMeetingURL($joinMeetingParams);
                // dd($url);
                // echo "<a href='".$url ."' target='_blank'>Click here to begin conference call</a>" ;
                return redirect()->intended($url);
            }
            else
            {
                if(backpack_user()->hasRole('School Head'))
                {
                    // $moderator_password for moderator
                    $joinMeetingParams = new JoinMeetingParameters($meetingId, $meetingName, $moderator_password);
                    $joinMeetingParams->setRedirect(true);
                    $joinMeetingParams->setUsername($user);
                    $url = $bbb->getJoinMeetingURL($joinMeetingParams);
                    // dd($url);
                    // echo "<a href='".$url ."' target='_blank'>Click here to begin conference call</a>" ;
                    return redirect()->intended($url);
                }
                else
                {
                    return redirect()->to('dashboard');
                }

            }
            
            
        }
    }

    public static function joinEmployeeVideoConference($code, $employee){
        
       
        $bbb = new BigBlueButton();
        $password = "employee-".$code;

        // $moderator_password for moderator
        $joinMeetingParams = new JoinMeetingParameters($code, $employee, $password);
        $joinMeetingParams->setRedirect(true);

        $url = $bbb->getJoinMeetingURL($joinMeetingParams);

        return $url;
    }
}
