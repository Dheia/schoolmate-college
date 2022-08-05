<?php

namespace App\Http\Controllers\Admin\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttendanceAndTapInAndOutLogsController extends Controller
{
    public function init ()
    {
    	$user = backpack_user()->with('employee')->first(); 

        $data = [
            'tap_in_today' => null,
            'tap_out_today' => null,
            'earliest_tap_in_this_month' => null,
            'tardiness_in_this_month' => null
        ];

        if($user->employee === null) { return $data; }

        $employee       = \App\Models\Employee::where('employee_id', $user->employee->employee_id)->with(['schedule' => function ($query) { $query->with('scheduleTemplate'); }])->first();
        $today_logs_asc = \App\Models\TurnstileLog::where('rfid', '54321')->whereDate('created_at', now())->orderBy('created_at', 'ASC')->select(['id', 'rfid', 'timein', 'timeout'])->get();
        $total_logs     = count($today_logs_asc);

        if($total_logs > 0) {

            $tapInToday             = $today_logs_asc[0]->timein;
            $tapOutToday            = $today_logs_asc[$total_logs - 1]->timeout;
            $data['tap_in_today']   = $tapInToday;
            $data['tap_out_today']  = $tapOutToday;

            $logs_by_current_month = \App\Models\TurnstileLog::where('rfid', '54321')->whereMonth('created_at', now()->month)->select(['id', 'rfid', 'timeout'])->get();
            $total_logs_by_current_month = count($logs_by_current_month);

            if(count($logs_by_current_month) > 0) {

                // EARLIEST TAP IN THIS CURRENT MONTH
                $earliestTapInThisMonth = $logs_by_current_month->sortBy('timein')->first();
                $data['earliest_tap_in_this_month'] = $earliestTapInThisMonth;

                // GET TARDINESS IN THiS CURRENT MONTH
                $tardinessInThisMonth = $logs_by_current_month->sortByDesc('timeout')->first(); 
                $data['tardiness_in_this_month'] = $tardinessInThisMonth;
            }

        }

        return  response()->json($data);
    }
}
