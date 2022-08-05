<?php

namespace App\Http\Controllers\API\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Http\Controllers\API\Student\OnlineClassController;

// Models
use App\Models\SchoolYear;
use App\Models\TurnstileLog;
use App\Models\SystemAttendance;
use App\Models\OnlineClassAttendance;
use App\Models\OnlineClass;

class AttendanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ONLINE CLASS ATTENDANCE
    |--------------------------------------------------------------------------
    */
    public function classAttendance(Request $request)
    {
        $student    = $request->user()->student;
        $user_id    = $student->id;
        $user_type  = 'App\Models\Student';

        $period     = $request->period;
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $response   = [
            'status'    => null,
            'data'      => null,
            'message'   => null
        ];

        switch ($period) {
            case 'today':
                $start_date         =   Carbon::today();
                $end_date           =   Carbon::today();

                break;

            case 'this_week':
                $start_date         =   Carbon::now()->startOfWeek();
                $end_date           =   Carbon::now()->endOfWeek();

                break;

            case 'this_month':
                $start_date         =   Carbon::now()->startOfMonth();
                $end_date           =   Carbon::now()->endOfMonth();

                break;

            case 'custom':
                if( $start_date == null && $end_date == null ) {
                    return   response()->json(["status" => "error", "message" => "No Selected Date"], 400);
                }

                if( self::validateDate($start_date) == false && self::validateDate($end_date) == false) {
                    return   response()->json(["status" => "error", "message" => "Invalid Date Format"], 400);
                }

                $start_date         =   Carbon::parse($start_date);
                $end_date           =   Carbon::parse($end_date);

                break;
            
            default: 
                $response['status'] = 'error';
                $response['message'] = 'Invalid Period.';

                return response()->json($response, 400);
                break;
        }

        $classAttendance =  OnlineClassAttendance::where('user_type', $user_type)
                                ->where('user_id', $user_id)
                                ->whereDate('created_at', '>=' , $start_date)
                                ->whereDate('created_at', '<=' , $end_date)
                                ->get();

        $response['status'] = 'success';
        $response['data']   = [
            'class_attendances' => $classAttendance,
            'period'            => $period,
            'start_date'        => $start_date->format('Y-m-d'),
            'end_date'          => $end_date->format('Y-m-d')
        ];
        $response['message'] = count($classAttendance) > 0 
                                ? count($classAttendance) . ' Attendance Found.' 
                                : 'No Attendance Found.';

        return response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | SINGLE ONLINE CLASS ATTENDANCE
    |--------------------------------------------------------------------------
    */
    public function singleClassAttendance($class_code, Request $request)
    {
        $student    = $request->user()->student;
        $user_id    = $student->id;
        $user_type  = 'App\Models\Student';

        $period     = $request->period;
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $response   = [
            'status'    => null,
            'data'      => null,
            'message'   => null
        ];

        $schoolYear      = SchoolYear::active()->first();
        $online_class    = OnlineClass::where('code', $class_code)->first();

        /* Check If Online Class is Found */
        if(! $online_class) {
            $response = [
                'status' => 'error',
                'data'   => null,
                'message' => 'Online Class Not Found.'
            ];
            
            return response()->json($response, 400);
        }

        $onlineClassController = new OnlineClassController();
        $online_classes = $onlineClassController->getOnlineClasses($student, $schoolYear);

        /* Check If Student has Online Classes */
        if(! count($online_classes) > 0 ) {
            $response = [
                'status' => 'error',
                'data'   => null,
                'message' => 'Mismatch Class.'
            ];
            
            return response()->json($response, 400);
        }

        $online_classes_codes = json_decode($online_classes->pluck('code'));
        
        /* Check If Online Class is IN Student's Online Classes */
        if(! in_array($online_class->code, $online_classes_codes)) {
            $response = [
                'status' => 'error',
                'data'   => null,
                'message' => 'Mismatch Class.'
            ];
            
            return response()->json($response, 400);
        }

        switch ($period) {
            case 'today':
                $start_date         =   Carbon::today();
                $end_date           =   Carbon::today();

                break;

            case 'this_week':
                $start_date         =   Carbon::now()->startOfWeek();
                $end_date           =   Carbon::now()->endOfWeek();

                break;

            case 'this_month':
                $start_date         =   Carbon::now()->startOfMonth();
                $end_date           =   Carbon::now()->endOfMonth();

                break;

            case 'custom':
                if( $start_date == null && $end_date == null ) {
                    return   response()->json(["status" => "error", "message" => "No Selected Date"], 400);
                }

                if( self::validateDate($start_date) == false && self::validateDate($end_date) == false) {
                    return   response()->json(["status" => "error", "message" => "Invalid Date Format"], 400);
                }

                $start_date         =   Carbon::parse($start_date);
                $end_date           =   Carbon::parse($end_date);

                break;
            
            default: 
                $response['status'] = 'error';
                $response['message'] = 'Invalid Period.';

                return response()->json($response, 400);
                break;
        }

        $classAttendance =  OnlineClassAttendance::where('online_class_id', $online_class->id)
                                ->where('user_type', $user_type)
                                ->where('user_id', $user_id)
                                ->whereDate('created_at', '>=' , $start_date)
                                ->whereDate('created_at', '<=' , $end_date)
                                ->get();

        $response['status'] = 'success';
        $response['data']   = [
            'class_attendances' => $classAttendance,
            'period'            => $period,
            'start_date'        => $start_date->format('Y-m-d'),
            'end_date'          => $end_date->format('Y-m-d')
        ];
        $response['message'] = count($classAttendance) > 0 
                                ? count($classAttendance) . ' Attendance Found.' 
                                : 'No Attendance Found.';

        return response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | ONLINE CLASS TAP IN / TAP OUT
    |--------------------------------------------------------------------------
    */
    public function tapClassAttendance($class_code)
    {
        $response = [
            'status' => null,
            'data'   => null,
            'message' => null
        ];

        $student      = request()->user()->student;
        $schoolYear   = SchoolYear::active()->first();
        $online_class = OnlineClass::where('code', $class_code)->first();

        $currentDate     =  Carbon::now()->toDateString();
        $currentTime     =  Carbon::now()->toTimeString();
        $currentDateTime =  Carbon::now();

        /* Check If Online Class is Found */
        if(! $online_class) {
            $response = [
                'status' => 'error',
                'data'   => null,
                'message' => 'Online Class Not Found.'
            ];
            
            return response()->json($response, 400);
        }

        $onlineClassController = new OnlineClassController();
        $online_classes = $onlineClassController->getOnlineClasses($student, $schoolYear);

        /* Check If Student has Online Classes */
        if(! count($online_classes) > 0 ) {
            $response = [
                'status' => 'error',
                'data'   => null,
                'message' => 'Mismatch Class.'
            ];
            
            return response()->json($response, 400);
        }

        $online_classes_codes = json_decode($online_classes->pluck('code'));
        
        /* Check If Online Class is IN Student's Online Classes */
        if(! in_array($online_class->code, $online_classes_codes)) {
            $response = [
                'status' => 'error',
                'data'   => null,
                'message' => 'Mismatch Class.'
            ];
            
            return response()->json($response, 400);
        }

        $user_attendance =  OnlineClassAttendance::where('user_id', $student->id)
                                ->where('user_type', 'App\Models\Student')
                                ->where('created_at', '>=', $currentDate)
                                ->where('created_at', '<=', $currentDate . ' 23:59:59')
                                ->where('online_class_id', $online_class->id)
                                ->first();

        /* Student Class Tap In */
        if(! $user_attendance) {

            $user_attendance =  OnlineClassAttendance::create([
                                    'user_id'   => $student->id,
                                    'user_type' => 'App\Models\Student',
                                    'online_class_id' => $online_class->id,
                                    'time_in'   => $currentDateTime,
                                ]);

            $response['status']  = 'success';
            $response['message'] = 'Successfully Tap In.';
            $response['data']    = $user_attendance;

            return response()->json($response);
        }

        /* Student Class Tap Out */
        if(! $user_attendance->time_out) {
            $user_attendance->time_out = $currentDateTime;
            $user_attendance->update();
            
            $response['status']  = 'success';
            $response['message'] = 'Successfully Tap Out.';
            $response['data']    = $user_attendance;
        } else {
            $response['status']  = 'error';
            $response['message'] = "You already tapped out.";
            return response()->json($response, 400);
        }

        return response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | SYSTEM ATTENDANCE
    |--------------------------------------------------------------------------
    */
    public function systemAttendance(Request $request)
    {
        $student    = $request->user()->student;
        $user_id    = $student->id;
        $user_type  = 'App\Models\Student';

        $period     = $request->period;
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $response   = [
            'status'    => null,
            'data'      => null,
            'message'   => null
        ];

        switch ($period) {
            case 'today':
                $start_date         =   Carbon::today();
                $end_date           =   Carbon::today();

                break;

            case 'this_week':
                $start_date         =   Carbon::now()->startOfWeek();
                $end_date           =   Carbon::now()->endOfWeek();

                break;

            case 'this_month':
                $start_date         =   Carbon::now()->startOfMonth();
                $end_date           =   Carbon::now()->endOfMonth();

                break;

            case 'custom':
                if( $start_date == null && $end_date == null ) {
                    return   response()->json(["status" => "error", "message" => "No Selected Date"], 400);
                }

                if( self::validateDate($start_date) == false && self::validateDate($end_date) == false) {
                    return   response()->json(["status" => "error", "message" => "Invalid Date Input."], 400);
                }

                $start_date         =   Carbon::parse($start_date);
                $end_date           =   Carbon::parse($end_date);

                break;
            
            default: 
                $response['status'] = 'error';
                $response['message'] = 'Invalid Period.';

                return response()->json($response, 400);
                break;
        }

        $system_attendance  =   SystemAttendance::where('user_id', $user_id)
                                    ->where('user_type', $user_type)
                                    ->whereDate('created_at', '>=' , $start_date)
                                    ->whereDate('created_at', '<=' , $end_date)
                                    ->get();

        $response['status'] = 'success';
        $response['data']   = [
            'system_attendances' => $system_attendance,
            'period'            => $period,
            'start_date'        => $start_date->format('Y-m-d'),
            'end_date'          => $end_date->format('Y-m-d')
        ];
        $response['message'] = count($system_attendance) > 0 
                                ? count($system_attendance) . ' Attendance Found.' 
                                : 'No Attendance Found.';

        return response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | SYSTEM TAP IN / TAP OUT
    |--------------------------------------------------------------------------
    */
    public function tapSystemAttendance()
    {
        $response = [
            'status' => null,
            'data'   => null,
            'message' => null
        ];

        $student      = request()->user()->student;

        $currentDate     =  Carbon::now()->toDateString();
        $currentTime     =  Carbon::now()->toTimeString();
        $currentDateTime =  Carbon::now();

        $system_attendance = null;

        if($student->rfid_number) {
            $system_attendance  =   SystemAttendance::with('user')
                                        ->where('rfid', $student->rfid_number)
                                        ->where('created_at', '>=', $currentDate)
                                        ->where('created_at', '<=', $currentDate . ' 23:59:59')
                                        ->first();
        }

        if(! $system_attendance){
            $system_attendance  =   SystemAttendance::where('user_id', $student->id)
                                        ->where('user_type', 'App\Models\Student')
                                        ->where('created_at', '>=', $currentDate)
                                        ->where('created_at', '<=', $currentDate . ' 23:59:59')
                                        ->first();
        }

        /* Student System Attendance Tap In  */
        if(! $system_attendance) {

            $system_attendance =    SystemAttendance::create([
                                        'user_id'   => $student->id,
                                        'user_type' => 'App\Models\Student',
                                        'time_in'   => $currentDateTime,
                                    ]);

            $response['status']  = 'success';
            $response['message'] = 'Successfully Tap In.';
            $response['data']    = $system_attendance;

            return response()->json($response);
        }

        /* Student System Attendance Tap Out  */
        if(! $system_attendance->time_out) {
            $system_attendance->time_out = $currentDateTime;
            $system_attendance->update();
            
            $response['status']  = 'success';
            $response['message'] = 'Successfully Tap Out.';
            $response['data']    = $system_attendance;
        } else {
            $response['status']  = 'error';
            $response['message'] = "You already tapped out.";
            return response()->json($response, 400);
        }

        return response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATE DATE
    |--------------------------------------------------------------------------
    */
    private function validateDate($date, $format = 'Y-m-d')
    {
        $d = new \DateTime();
        $d = $d->createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

}
