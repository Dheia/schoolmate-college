<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TurnstileLog;

use Carbon\Carbon;

class PayrollController extends Controller
{


    private function AuditDateAttendanceLog  ($rfid, $assessDate)
    {
        // FIRST IN
        $attendance_login   = TurnstileLog::where('rfid', $rfid)
                                            ->whereDate('created_at', '=', Carbon::parse($assessDate))
                                            ->selectRaw('*, date(created_at) as date_pro')
                                            ->first();
        // LAST OUT
        $attendance_logout  = TurnstileLog::where('rfid', $rfid)
                                            ->where('timeout', '!=', null)
                                            ->whereDate('created_at', '=', Carbon::parse($assessDate))
                                            ->selectRaw('*, date(created_at) as date_pro')
                                            ->orderBy('id', 'DESC')
                                            ->first();

        $start_time = $attendance_login->timein ?? null;
        $end_time   = $attendance_login->timeout ?? null;
        $week_day   = Carbon::parse($assessDate)->format('l');
        $remarks    = 'ABSENT';
        $duration   = null;
        

        // CALCULATE THE TOTAL DURATION
        if($start_time !== null && $end_time !== null)
        {
            $start_time = Carbon::parse($start_time);
            $end_time   = Carbon::parse($end_time);

            $diffInHours   = $end_time->diffInHours($start_time);
            $diffInMinutes = $end_time->diffInMinutes($start_time);
            $diffInSeconds = $end_time->diffInSeconds($start_time);
            $diff          = $end_time->diff($start_time);

            $duration['diffInHours']   = $diffInHours;
            $duration['diffInMinutes'] = $diffInMinutes;
            $duration['diffInSeconds'] = $diffInSeconds;
            $duration['diff'] = $diff;
        }

        if ($start_time !== null && $end_time !== null && $week_day !== 'Sunday') 
        { 
            $remarks = 'PRESENT'; 
        } 
        else if ($start_time  == null && $end_time !== null && $week_day !== 'Sunday') 
        { 
            $remarks = 'NTI'; 
        }
        else if ($start_time !== null && $end_time  == null && $week_day !== 'Sunday') 
        { 
            $remarks = 'NTO'; 
        }
        else if ($week_day  == 'Sunday')                                               
        { 
            $remarks = 'NO CLASSESS'; 
        }
        else                                                                           
        { 
            $remarks = $remarks; 
        }

        $data = [
                    'start_time'           => $start_time == null ? 'NTI' : $start_time,
                    'end_time'             => $end_time   == null ? 'NTO' : $end_time,
                    'start_time_formatted' => $start_time == null ? 'NTI' : Carbon::parse($start_time)->format('g:i A'),
                    'end_time_formatted'   => $end_time   == null ? 'NTO' : Carbon::parse($end_time)->format('g:i A'),
                    'date_string'          => $assessDate,
                    'date_format'          => Carbon::parse($assessDate)->format('F d, Y'),
                    'week_day'             => $week_day,
                    "remarks"              => $remarks,
                    "duration"             => $duration
                ];

        return $data;
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $logs = [];

        $start_date = Carbon::parse('01-03-2019');
        $end_date   = Carbon::parse('31-03-2019');

        $date_from = $start_date->format('M d, Y');
        $date_to   = $end_date->format('M d, Y');

        $original_format_date_start = $start_date;
        $start_day_incrementing    = $start_date->format('Y-m-d');

        while($start_date->format('Y-m-d') <= $end_date->format('Y-m-d')) 
        {
            $assessDate                       = $start_day_incrementing;
            $subjectLog                       = self::AuditDateAttendanceLog('1020867064', $assessDate);
            $logs[$subjectLog['date_string']] = $subjectLog;
            $start_day_incrementing           = $original_format_date_start->addDays(1)->format('Y-m-d');
        }

        $data = [
                  'attendance_logs' => $logs,
                  // 'date_period'     => $period_type,
                  'date_from'       => $date_from,
                  'date_to'         => $date_to,
                ];

        return $data;

        return response()->json($data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
