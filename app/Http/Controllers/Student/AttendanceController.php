<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;

use App\Models\AuthenticationLog;
use App\Models\OnlineClassAttendance;

class AttendanceController extends Controller
{

    public function classAttendance()
    {
    	$student 	= auth()->user()->student;

    	$data = [
    		'student' 	=> $student,
    		'title' 	=> 'Class Attendance',
    	];

    	return view('studentPortal.attendances.classAttendance', $data);
    }

    public function classAttendanceLogs(Request $request)
    {
    	$student 		 	= 	auth()->user()->student;

    	if($request->input('period') == null) {
            return ["status" => "ERROR", "message" => "No Selected Period"];
        }

        $student  = auth()->user()->student;

        $data = [

        ];

        $user_id    = $student->id;
        $user_type  = 'App\Models\Student';
        $date_from  = $request->date_from;
        $date_to    = $request->date_to;

        $classAttendance = OnlineClassAttendance::getClassAttendanceLogs($user_id, $user_type, $request->period, $date_from, $date_to);

        $data = [
        	'classAttendance' => $classAttendance
        ];
        return response()->json($data);
    }

    public function systemAttendance()
    {
    	$student 	= auth()->user()->student;

    	$data = [
    		'student' 	=> $student,
    		'title' 	=> 'System Attendance',
    	];

    	return view('studentPortal.attendances.systemAttendance', $data);
    }

    public function systemAttendanceLogs(Request $request)
    {
    	$student           =   auth()->user()->student;

        if($request->input('period') == null) {
            return ["status" => "ERROR", "message" => "No Selected Period"];
        }

        $student  = auth()->user()->student;

        $data = [

        ];

        $user_id    = $student->id;
        $user_type  = 'App\Models\Student';
        $date_from  = $request->date_from;
        $date_to    = $request->date_to;

        $systemAttendance = AuthenticationLog::getAuthenticationLogs($user_id, $user_type, $request->period, $date_from, $date_to);

        $data = [
            'systemAttendance' => $systemAttendance
        ];
        return response()->json($data);
    }
}
