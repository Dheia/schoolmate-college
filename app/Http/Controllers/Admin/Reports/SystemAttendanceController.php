<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemAttendance;
use DB;
use Carbon\Carbon;


class SystemAttendanceController extends Controller
{
   public function index(){
       return view('reports.systemAttendance.system_attendance');
   }
   public function generateReport(Request $request){
        $startDate = $request->date_from;
        $endDate   = $request->date_to;
       
        $attendance =   SystemAttendance::with('user')
                            ->where('created_at', '>=', $startDate)
                            ->where('created_at', '<=', $endDate . ' 23:59:59')
                            ->whereHas('user')
                            ->get();
    
        $data = [
            'attendances'     => $attendance,
            'schoollogo'      => config('settings.schoollogo') ? (string)\Image::make(config('settings.schoollogo'))->encode('data-url') : null,
            'schoolmate_logo' =>(string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url'),
            'startDate'       =>  $startDate,
            'endDate'         => $endDate,
        ];

        return view('reports.systemAttendance.generateReport', $data );
   }
   public function logs(){
        $response = [
            'error'     => false,
            'message'   => null,
            'data'      => null
        ];

        $startDate  = request()->date_from;
        $endDate    = request()->date_to;
      

        $attendance =   SystemAttendance::with('user')->where('created_at', '>=', $startDate)
                                 ->where('created_at', '<=', $endDate . ' 23:59:59')
                                 ->whereHas('user')
                                 ->get();

        $response['data']   =   $attendance;
        return $response;
   }
  
   
}
