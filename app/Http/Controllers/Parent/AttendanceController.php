<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\ParentUser;
use App\Models\ParentStudent;
use App\Models\AuthenticationLog;
use App\Models\OnlineClassAttendance;

class AttendanceController extends Controller
{
    public function classAttendance()
    {
    	$parent 	= 	auth()->user()->parent;
    	$students 	=	ParentStudent::where('parent_user_id', $parent->id)->get()->pluck('student');

    	$data = [
    		'students' 	=> $students,
    		'title' 	=> 'Class Attendance',
    	];

    	return view('parentPortal.attendances.classAttendance', $data);
    }

    public function classAttendanceLogs(Request $request)
    {

    	$parent         =   auth()->user()->parent;
        $studentnumber  =   $request->studentnumber;
        if(! $studentnumber) {
            return ["status" => "ERROR", "message" => "Student Not Found."];
        }

        $student        =   Student::where('studentnumber', $studentnumber)->first();
        if(! $student) {
        	return ["status" => "ERROR", "message" => "Student Not Found."];
        }

        $parentStudent  =   ParentStudent::where('parent_user_id', $parent->id)->where('student_id', $student->id)->first();
        if(! $parentStudent) {
        	return ["status" => "ERROR", "message" => "Unauthorized action."];
        }

    	if($request->input('period') == null) {
            return ["status" => "ERROR", "message" => "No Selected Period"];
        }

        $data = [];

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
        $parent     =   auth()->user()->parent;
        $students   =   ParentStudent::where('parent_user_id', $parent->id)->get()->pluck('student');

        $data = [
            'students'  => $students,
            'title'     => 'System Attendance',
        ];

        return view('parentPortal.attendances.systemAttendance', $data);
    }

    public function systemAttendanceLogs(Request $request)
    {
        $parent         =   auth()->user()->parent;
        $studentnumber  =   $request->studentnumber;
        if(! $studentnumber) {
            return ["status" => "ERROR", "message" => "Student Not Found."];
        }

        $student        =   Student::where('studentnumber', $studentnumber)->first();
        if(! $student) {
            return ["status" => "ERROR", "message" => "Student Not Found."];
        }

        $parentStudent  =   ParentStudent::where('parent_user_id', $parent->id)->where('student_id', $student->id)->first();
        if(! $parentStudent) {
            return ["status" => "ERROR", "message" => "Unauthorized action."];
        }

        if($request->input('period') == null) {
            return ["status" => "ERROR", "message" => "No Selected Period"];
        }

        $data = [];

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
