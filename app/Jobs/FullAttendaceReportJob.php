<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Employee;
use App\Models\Rfid;
use App\Models\TurnstileLog;
use App\Models\Holiday;

use Carbon\Carbon;

class FullAttendaceReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $date_from;
    private $date_to;
    private $period;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($date_from, $date_to, $period)
    {
        //
        $this->date_from = $date_from;
        $this->date_to = $date_to;
        $this->period = $period;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $employees = Employee::select('id', 'employee_id', 'firstname', 'lastname', 'middlename')->take(1)->get();

        $employees->map(function ($employee) {
            $employee['logs'] = self::employeeAttendanceLogs($employee->employee_id); 
            (object)$employee['logs'];    
        });

        \Log::info($employees);
        \Log::info("Success");
    }

    public function employeeAttendanceLogs ($id)
    {
        // $period = $request->
        if($this->period == null) {
            return ["status" => "ERROR", "message" => "No Selected Period"];
        }

        $rfid = Rfid::where('studentNumber', $id)->first();
        $data = [];

        if($rfid !== null) {
            $rfid = $rfid->rfid;
            switch ($this->period) {
                case 'today':

                    $start_date = Carbon::today();
                    $end_date   = Carbon::today();
                    $data       = self::GenerateDynamicAttendance($rfid, 'today', $start_date, $end_date);

                    break;

                case 'this_week':

                    $start_date = Carbon::now()->startOfWeek();
                    $end_date   = Carbon::now()->endOfWeek();
                    $data       = self::GenerateDynamicAttendance($rfid, 'this_week', $start_date, $end_date);

                    break;

                case 'this_month':

                    $start_date = Carbon::now()->startOfMonth();
                    $end_date   = Carbon::now()->endOfMonth();
                    $data       = self::GenerateDynamicAttendance($rfid, 'this_month', $start_date, $end_date);

                    break;

                case 'custom':

                    if( $this->date_from == null && $this->date_to == null) {
                        return  ["status" => "ERROR", "message" => "No Selected Date"];
                    }

                    if( self::ValidateDate($this->date_from) == false && self::validateDate($this->date_to) == false) {
                        return  ["status" => "ERROR", "message" => "Invalid Date Format"];
                    }

                    $start_date = Carbon::parse($request->date_from);
                    $end_date   = Carbon::parse($request->date_to);
                    $data       = self::GenerateDynamicAttendance($rfid, 'custom', $this->date_from, $this->date_to);

                    break;
                
                default: 
                    return ["status" => "ERROR", "message" => "Invalid Period Type"];;
                    break;
            }
        }

        return $data;        
    }

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
            $start_time                = Carbon::parse($start_time);
            $end_time                  = Carbon::parse($end_time);
            $diffInHours               = $end_time->diffInHours($start_time);
            $diffInMinutes             = $end_time->diffInMinutes($start_time);
            $diffInSeconds             = $end_time->diffInSeconds($start_time);
            $diff                      = $end_time->diff($start_time);
            $duration['diffInHours']   = $diffInHours;
            $duration['diffInMinutes'] = $diffInMinutes;
            $duration['diffInSeconds'] = $diffInSeconds;
            $duration['diff']          = $diff;
        }

        if ($start_time !== null && $end_time !== null && $week_day !== 'Sunday')       { $remarks = 'PRESENT'; } 
        else if ($start_time == null && $end_time !== null && $week_day !== 'Sunday')   { $remarks = 'NTI';  }
        else if ($start_time !== null && $end_time  == null && $week_day !== 'Sunday')  { $remarks = 'NTO';  }
        else if ($week_day  == 'Sunday')                                                { $remarks = 'NO CLASSESS';  }
        else                                                                            { $remarks = $remarks;  }

        // CHECK HOLIDAY
        $holiday = Holiday::with(['schoolYear' => function ($q) {
                                    $q->active(); 
                            }])
                            ->whereDate('date', Carbon::parse($assessDate))->first();

        if($holiday !== null) { $remarks = $holiday->name; }

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

    private function GenerateDynamicAttendance ($rfid, $period_type, $start_date, $end_date)
    {
        $logs = [];

        $date_from = $start_date->format('M d, Y');
        $date_to   = $end_date->format('M d, Y');

        $original_format_date_start = $start_date;
        $start_day_incrementing    = $start_date->format('Y-m-d');

        while($start_date->format('Y-m-d') <= $end_date->format('Y-m-d')) 
        {
            $assessDate                       = $start_day_incrementing;
            $subjectLog                       = self::AuditDateAttendanceLog($rfid, $assessDate);
            $logs[$subjectLog['date_string']] = $subjectLog;
            $start_day_incrementing           = $original_format_date_start->addDays(1)->format('Y-m-d');
        }

        $data = [
                  'attendance_logs' => $logs,
                  'date_period'     => $period_type,
                  'date_from'       => $date_from,
                  'date_to'         => $date_to,
                ];

        return $data;
    }
}
