<?php
namespace App\Http\Controllers\Admin;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EmployeeAttendanceRequest as StoreRequest;
use App\Http\Requests\EmployeeAttendanceRequest as UpdateRequest;

// IMPORT
use Illuminate\Http\Request;
use Carbon\Carbon;

// Jobs
use App\Jobs\FullAttendaceReportJob;


// MODELS
use App\Models\Employee;
use App\Models\Rfid;
use App\Models\TurnstileLog;
use App\Models\Holiday;
use App\Models\ScheduleTemplate;
use App\Models\ScheduleTagging;
use App\Models\EmployeeAttendance;
use Log;

/**
 * Class EmployeeAttendanceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EmployeeAttendanceCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\EmployeeAttendance');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/employee-attendance');
        $this->crud->setEntityNameStrings('Attendance', 'Employee Attendance');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in EmployeeAttendanceRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');


        $this->crud->addField([
            'name'  => 'searchInput',
            'type'  => 'searchEmployee',
            'label' => 'Search',
            'attributes' => [
                'id'           => 'searchInput',
                'placeholder'  => 'Search For Name or ID Number (ex. 1100224)',
                'autocomplete' => 'off' 
            ]
        ])->beforeField('employee_id');

        $this->crud->addField([
            'name' => 'employee_id',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'studentNumber'
            ]
        ]);

        $this->crud->addField([
            'name'  => 'attendance_period',
            'type'  => 'select_from_array',
            'label' => 'Select Period',
            'options' => [
                            'today'      => 'Today', 
                            'this_week'  => 'This Week', 
                            'this_month' => 'This Month', 
                            'custom'     => 'Custom'
                        ],
            'attributes' => [
                                'id' => 'attendance_period'
                            ],
            'wrapperAttributes' => [
                'class' => 'col-md-4 col-xs-12'
            ]
        ]);

        $this->crud->addField([
            'name'  => 'date_from',
            'type'  => 'date',
            'label' => 'Date From',
            'attributes' => [
                'id' => 'date_from'
            ],
            'wrapperAttributes' => [
                'class' => 'col-md-4 date_from'
            ]
        ]);

        $this->crud->addField([
            'name'  => 'date_to',
            'type'  => 'date',
            'label' => 'Date To',
            'attributes' => [
                'id' => 'date_to'
            ],
            'wrapperAttributes' => [
                'class' => 'col-md-4 date_to'
            ]
        ]);


        // BUTTON RUN
        $this->crud->addField([
            'value' => '<br><button id="btn-run" class="btn btn-success">Run</button> <a href="#" id="btn-full-run-report" class="btn btn-primary">Full Run Report</a> 
            <button id="btn-download" class="btn btn-default"><i class="fa fa-download"></i> Download</button>',
            'type'  => 'custom_html',
            'name'  => 'button_run',
            
        ]);
        


        $this->crud->setListView('employeeAttendance.list');

        // PERMISSION
        // $pathRoute = str_replace("admin/", "", $this->crud->route);
        // $user = backpack_auth()->user();

        // $permissions = collect($user->getAllPermissions());
        // $allowed_method_access = array();

        // foreach ($permissions as $permission) {
            
        //     if($permission->page_name == $pathRoute) {
        //         $methodName = strtolower( explode(' ', $permission->name)[0] );
        //         array_push($allowed_method_access, $methodName);
        //     }
        // }

        // $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone', 'show']);
        // $this->crud->allowAccess($allowed_method_access);
        

        // CUSTOM LIST

        // self::employeeAttendanceLogs($this->request);
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function destroy ($id)
    {
        $model = $this->crud->model::findOrFail($id);
        $model->delete();
    }

    private function ValidateDate($date, $format = 'Y-m-d')
    {
        $d = new \DateTime();
        $d = $d->createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public function employeeAttendanceLogs ($id, Request $request)
    {
        // $period = $request->
        if($request->input('period') == null) {
            return ["status" => "ERROR", "message" => "No Selected Period"];
        }

        $rfid = Rfid::where('studentNumber', $id)->first();
        $data = [];

        if($rfid !== null) {
            $rfid = $rfid->rfid;
            switch ($request->period) {
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
                    if( $request->input('date_from') == null && $request->input('date_to') == null) {
                        return  ["status" => "ERROR", "message" => "No Selected Date"];
                    }

                    if( self::ValidateDate($request->date_from) == false && self::validateDate($request->date_to) == false) {
                        return  ["status" => "ERROR", "message" => "Invalid Date Format"];
                    }

                    $start_date = Carbon::parse($request->date_from);
                    $end_date   = Carbon::parse($request->date_to);
                    $data       = self::GenerateDynamicAttendance($rfid, 'custom', $start_date, $end_date);

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

        $rfidModel = Rfid::where('rfid', $rfid)->first(); 
        // FIRST IN
        $attendance_login   = TurnstileLog::where('rfid', $rfid)
                                            ->whereDate('created_at', '=', Carbon::parse($assessDate))
                                            ->orderBy('timein', 'ASC')
                                            ->selectRaw('*, date(created_at) as date_pro')
                                            ->first();
        // LAST OUT
        $attendance_logout  = TurnstileLog::where('rfid', $rfid)
                                            ->whereDate('created_at', Carbon::parse($assessDate))
                                            ->latest()
                                            ->first();

        $start_time = $attendance_login->timein ?? null;
        $end_time   = $attendance_logout->timeout ?? null;

        $week_day   = Carbon::parse($assessDate)->format('l');


        $scheduleTemplate = ScheduleTagging::where('employee_id', $rfidModel->studentnumber)->with('scheduleTemplate')->first();

        $data = [];
        if($scheduleTemplate !== null) {
            if($scheduleTemplate->scheduleTemplate !== null) {
                $data['schedule_template'] = $scheduleTemplate->scheduleTemplate;
            } else {
                $data['schedule_template'] = null;
            }
        }        

        $remarks    = 'ABSENT';
        $duration   = null;
        

        // CALCULATE THE TOTAL DURATION
        if($start_time !== null && $end_time !== null && $scheduleTemplate !== null)
        {
            // Get The Time-In Of Schedule And The Time-In On Turnstile Logs, And Get The Time Of His/Her Late As Minute
            $weekDayThreeLetters = Carbon::parse($assessDate)->format('D');
            $weekday_timein      = strtolower($weekDayThreeLetters) . '_timein';
            $weekday_timeout     = strtolower($weekDayThreeLetters) . '_timeout';

            $schedule_timein     = Carbon::parse($data['schedule_template']->{$weekday_timein});
            $schedule_timeout    = Carbon::parse($data['schedule_template']->{$weekday_timeout});

            $start_time                       = Carbon::parse($start_time);
            $end_time                         = Carbon::parse($end_time);
            $diffInHours                      = $end_time->diffInHours($start_time);
            $diffInMinutes                    = $end_time->diffInMinutes($start_time);
            $diffInSeconds                    = $end_time->diffInSeconds($start_time);
            $diff                             = $end_time->diff($start_time);
            $duration['timeinLateInMinutes']  = $schedule_timein->diffInMinutes($start_time);
            $duration['underTimeInMinutes']   = $schedule_timeout->diffInMinutes($end_time);
            $duration['diffInHours']          = $diffInHours;
            $duration['diffInMinutes']        = $diffInMinutes;
            $duration['diffInSeconds']        = $diffInSeconds;
            $duration['diff']                 = $diff;
        }

        if ($start_time !== null && $end_time !== null && $week_day !== 'Saturday')       { $remarks = 'PRESENT'; } 
        else if ($start_time == null && $end_time !== null && $week_day !== 'Saturday')   { $remarks = 'NTI';  }
        else if ($start_time !== null && $end_time  == null && $week_day !== 'Saturday')  { $remarks = 'NTO';  }
        else if ($week_day  == 'Saturday')                                                { $remarks = 'NO CLASSESS';  }
        else                                                                              { $remarks = $remarks;  }

        if ($start_time !== null && $end_time !== null && $week_day !== 'Sunday')       { $remarks = 'PRESENT'; } 
        else if ($start_time == null && $end_time !== null && $week_day !== 'Sunday')   { $remarks = 'NTI';  }
        else if ($start_time !== null && $end_time  == null && $week_day !== 'Sunday')  { $remarks = 'NTO';  }
        else if ($week_day  == 'Sunday')                                                { $remarks = 'NO CLASSESS';  }
        else                                                                            { $remarks = $remarks;  }

        // CHECK HOLIDAY
        $holiday = Holiday::with(['schoolYear' => function ($q) { $q->active(); }])->whereDate('date', Carbon::parse($assessDate))->first();

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

        $date_from                  = $start_date->format('M d, Y');
        $date_to                    = $end_date->format('M d, Y');

        $original_format_date_start = $start_date;
        $start_day_incrementing     = $start_date->format('Y-m-d');

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

        return response()->json($data);
    }


    public function fullRunReport (Request $request)
    {
        // dd(Employee::where('employee_id', '10031')->with(['latestEmploymentStatusHistory' => function ($q) { $q->with('employmentStatus'); }])->first());
        $start_date     = $request->date_from;
        $end_date       = $request->date_to;
        $period_type    = $request->period;

        $employees = Employee::whereHas('rfid')->whereHas('latestEmploymentStatusHistory')->get()->filter(function($item) {
                        return $item->is_resigned == 0;
                    });

        $employee_ids   = $employees->pluck('employee_id')->toArray();
        $days_of_length = null;

        $start_month = Carbon::parse($start_date)->format('my');
        $end_month   = Carbon::parse($end_date)->format('my');

        // if($start_month !== $end_month) {
        //     \Alert::warning("Please Must Match The Start Of Month And Year")->flash();
        //     return redirect('admin/employee-attendance');
        // }

        switch ($period_type) {

            case 'today':

                $start_date     = Carbon::today();
                $end_date       = Carbon::today();
                $days_of_length = $end_date->diffInDays($start_date) + 1;

                break;

            case 'this_week':

                $start_date     = Carbon::now()->startOfWeek();
                $end_date       = Carbon::now()->endOfWeek();
                $days_of_length = $end_date->diffInDays($start_date) + 1;

                break;

            case 'this_month':

                $start_date     = Carbon::now()->startOfMonth();
                $end_date       = Carbon::now()->endOfMonth();
                $days_of_length = $end_date->diffInDays($start_date) + 1;

                break;

            case 'custom':

                if( $request->input('date_from') == null && $request->input('date_to') == null) {
                    return  ["status" => "ERROR", "message" => "No Selected Date"];
                }

                if( self::ValidateDate($request->date_from) == false && self::validateDate($request->date_to) == false) {
                    return  ["status" => "ERROR", "message" => "Invalid Date Format"];
                }

                $start_date     = Carbon::parse($request->date_from);
                $end_date       = Carbon::parse($request->date_to);
                $days_of_length = $end_date->diffInDays($start_date) + 1;

                break;
        }

        $start_date = Carbon::parse($start_date)->format('Y-m-d');
        $end_date = Carbon::parse($end_date)->format('Y-m-d');

        return view('employeeAttendance.fullRunReport', compact('employees', 'days_of_length', 'period_type', 'start_date', 'end_date', 'employee_ids'));
    }
    
    public function downloadEmployeeAttendance($id, Request $request)
    {
     

    $rfid       = Rfid::where('studentNumber', $id)->first();
    $employee   = Employee::where('employee_id',$id)->first();
    $datas      = [];
 
    if($rfid !== null) {
        $rfid = $rfid->rfid;
            switch ($request->period) {
                case 'today':

                    $start_date = Carbon::today();
                    $end_date   = Carbon::today();
                    $datas       = self::GenerateDynamicAttendance($rfid, 'today', $start_date, $end_date);

                    break;

                case 'this_week':

                    $start_date = Carbon::now()->startOfWeek();
                    $end_date   = Carbon::now()->endOfWeek();
                    $datas       = self::GenerateDynamicAttendance($rfid, 'this_week', $start_date, $end_date);

                    break;

                case 'this_month':

                    $start_date = Carbon::now()->startOfMonth();
                    $end_date   = Carbon::now()->endOfMonth();
                    $datas       = self::GenerateDynamicAttendance($rfid, 'this_month', $start_date, $end_date);

                    break;

                case 'custom':
                    if( $request->input('date_from') == null && $request->input('date_to') == null) {
                        return  ["status" => "ERROR", "message" => "No Selected Date"];
                    }

                    if( self::ValidateDate($request->date_from) == false && self::validateDate($request->date_to) == false) {
                        return  ["status" => "ERROR", "message" => "Invalid Date Format"];
                    }

                    $start_date = Carbon::parse($request->date_from);
                    $end_date   = Carbon::parse($request->date_to);
                    $datas       = self::GenerateDynamicAttendance($rfid, 'custom', $start_date, $end_date);

                    break;
                
                default: 
                    return ["status" => "ERROR", "message" => "Invalid Period Type"];;
                    break;
             }
        }
        $date_input = $contents = $datas->getdata();
        $contents = $datas->getdata()->attendance_logs;
        $schoollogo             =   config('settings.schoollogo') 
                                        ? (string)\Image::make(config('settings.schoollogo'))->encode('data-url') 
                                        : null;
        $schoolmate_logo        =   (string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url');

        return view('employeeAttendance.generateReport', compact('employee','contents','schoollogo', 'schoolmate_logo','date_input'));
    }
}
