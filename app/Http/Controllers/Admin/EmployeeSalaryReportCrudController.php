<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EmployeeSalaryReportRequest as StoreRequest;
use App\Http\Requests\EmployeeSalaryReportRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

// MODELS
use App\Models\Rfid;
use App\Models\TurnstileLog;
use App\Models\Holiday;
use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\ScheduleTagging;
use App\Models\EmployeeMandatorySSS;
use App\Models\EmployeeMandatoryPhilHealth;
use App\Models\EmployeeMandatoryPagIbig;
use App\Models\EmployeeTaxManagement;
use App\Models\SSSLoan;
use App\Models\PagIbigLoan;
use App\Models\PayrollRun;
use App\Models\PayrollRunItem;
use App\Models\EmployeeAdjustment;


use Carbon\Carbon;
use Config;

/**
 * Class EmployeeSalaryReportCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EmployeeSalaryReportCrudController extends CrudController
{

    private $salary;
    private $scheduleEntity;
    private $salaryEntity;
    private $hours_in_a_year;
    private $hours_per_week;
    private $hours_per_month;
    private $hours_per_day;
    private $hourly_pay;
    private $daily_pay;
    private $minutely_pay;
    private $total_no_of_days_cutoff;
    private $totaL_no_of_dayys;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\EmployeeSalaryReport');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/employee-salary-report');
        $this->crud->setEntityNameStrings('Employee Salary Report', 'Employee Salary Report');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();
        
        $this->crud->setListView('employeeSalaryReport.list');


       
        /*
        |--------------------------------------------------------------------------
        | Setup Cut off
        |--------------------------------------------------------------------------
        */
       
        // Get the date today
        $today          = Carbon::today();
        $today_year     = (int)$today->format('Y');
        $today_month    = (int)$today->format('m');
        $today_day      = (int)$today->format('d');
        $first_cutoff   = (int)Config::get('settings.firstcutoff');
        $second_cutoff  = (int)Config::get('settings.secondcutoff');

        // dd($today_year . '/' . $today_month . '/' . $today_day);
        // dd($today_day, $first_cutoff);

        if($today_day <= $first_cutoff)
        {
            $first = Carbon::today()->subDays(15);
        }

        $this->crud->data['holidays'] = Holiday::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)->get();

        // add asterisk for fields that are required in EmployeeSalaryReportRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
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


    private function ValidateDate($date, $format = 'Y-m-d')
    {
        $d = new \DateTime();
        $d = $d->createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public function payroll ()
    {
        $payroll_id         = uniqid();
        $payroll_status     = 'UNPUBLISH';
        $sss                = isset( request()->sss ) ? 1 : 0;
        $tax                = isset( request()->tax ) ? 1 : 0;
        $philhealth         = isset( request()->philhealth ) ? 1 : 0;
        $hdmf               = isset( request()->hdmf ) ? 1 : 0;

        $tax_type           = request()->tax_type;
        $sss_type           = request()->sss_type;
        $philhealth_type    = request()->philhealth_type;
        $hdmf_type          = request()->hdmf_type;

        // LOANS
        $sss_loan           = isset( request()->sss_loan ) ? 1 : 0;
        $philhealth_loan    = isset( request()->philhealth_loan ) ? 1 : 0;
        $hdmf_loan          = isset( request()->hdmf_loan ) ? 1 : 0;

        $tax_loan_type           = request()->tax_loan_type;
        $sss_loan_type           = request()->sss_loan_type;
        $philhealth_loan_type    = request()->philhealth_loan_type;
        $hdmf_loan_type          = request()->hdmf_loan_type;


        $this->data['crud'] = $this->crud;
        $payroll            = [];
        $first_cutoff       = request()->from;
        $second_cutoff      = request()->to;

                $employees = Employee::whereHas('rfid')
                                    ->with('latestEmploymentStatusHistory')
                                    ->select('id', 'employee_id', 'firstname', 'middlename', 'lastname')->get()
                                    ->filter(function($item) { return $item->is_resigned == 0; });
        $employee_ids   = $employees->pluck('employee_id');
        $payrollRun     = PayrollRun::whereDate('date_from', request()->from)->whereDate('date_to', request()->to);

        // If Exists Override value of `$employees` and `$employee_ids`
        if($payrollRun->exists()) {
            $item = $payrollRun->first();
            if($item->status === "PUBLISHED") {
                $payrollRunItemEntity   = PayrollRunItem::where('payroll_run_id', $item->id)->get();
                $employees              = Employee::whereIn('employee_id', $payrollRunItemEntity->pluck('employee_id')->toArray())->get();
                $employee_ids           = $payrollRunItemEntity->pluck('employee_id')->toArray();
                $payroll_status         = 'PUBLISHED';
            }
        }

        $data = [
            'payroll_id'            => $payroll_id,
            'payroll_status'        => $payroll_status,
            'employees'             => $employees,
            'employee_ids'          => $employee_ids,
            'first_cutoff'          => $first_cutoff,
            'second_cutoff'         => $second_cutoff,
            'tax'                   => $tax,
            'sss'                   => $sss,
            'philhealth'            => $philhealth,
            'hdmf'                  => $hdmf,
            'tax_type'              => $tax_type,
            'sss_type'              => $sss_type,
            'philhealth_type'       => $philhealth_type,
            'hdmf_type'             => $hdmf_type,
            'sss_loan'              => $sss_loan,
            'hdmf_loan'             => $hdmf_loan,
            'tax_loan_type'         => $tax_loan_type,
            'sss_loan_type'         => $sss_loan_type,
            'hdmf_loan_type'        => $hdmf_loan_type,
        ];


        if(!$payrollRun->exists()) {
            $payRoll = new PayrollRun;
            $payRoll->payroll_id        = $payroll_id;
            $payRoll->date_from         = Carbon::parse(request()->from);
            $payRoll->date_to           = Carbon::parse(request()->to);
            $payRoll->run_by            = backpack_auth()->user()->id;
            $payRoll->tax               = $tax;
            $payRoll->tax_type          = $tax_type;
            $payRoll->sss               = $sss;
            $payRoll->sss_type          = $sss_type;
            $payRoll->philhealth        = $philhealth;
            $payRoll->philhealth_type   = $philhealth_type;
            $payRoll->hdmf              = $hdmf;
            $payRoll->hdmf_type         = $hdmf_type;
            $payRoll->sss_loan          = $sss_loan;
            $payRoll->sss_loan_type     = $sss_loan_type;
            $payRoll->hdmf_loan         = $hdmf_loan;
            $payRoll->hdmf_loan_type    = $hdmf_loan_type;

            if($payRoll->save()) {
                $items = [];
                $data['payroll_run_id'] = $payRoll->id;
                foreach ($employees as $employee) {
                    $items[] = [
                        'payroll_run_id'    => $payRoll->id,
                        'employee_id'       => $employee->employee_id,
                        'full_name'         => $employee->full_name,
                    ];
                }
                $payrollRunItem = PayrollRunItem::insert($items);
                return view('employeeSalaryReport.payroll', $this->data, $data);
            }

            \Alert::warning("Error Run Report")->flash();
        } else {
            $payRoll                = PayrollRun::whereDate('date_from', request()->from)->whereDate('date_to', request()->to)->first();
            $data['payroll_run_id'] = $payRoll->id;
            return view('employeeSalaryReport.payroll', $this->data, $data);
        }
        return redirect()->back();
    }

    public function getEmployeePayroll ($employee_id)
    {
        // CHECK IF PAYROLL IS ALREADY PUBLISHED THEN RETURN THE `PAYROLL(column table of payroll_run_items)` JSON WERE SAVE
        $payrollRunEntity = PayrollRun::where('id', request()->payroll_run_id);
        if($payrollRunEntity->exists()) {
            $entity = $payrollRunEntity->first(); 
            if($entity->status === "PUBLISHED") {
                $payrollRunItemEntity = PayrollRunItem::where('payroll_run_id', request()->payroll_run_id)->where('employee_id', $employee_id);
                if($payrollRunItemEntity->exists()) {
                    $item = $payrollRunItemEntity->where('employee_id', $employee_id)->first();
                    $payroll = $item->payroll;
                    return response()->json(json_decode($payroll));
                }
            } 
        }

        // IF UNPUBLISHED
        $payroll = [];

        $first_cutoff                = Carbon::parse(request()->from);
        $second_cutoff               = Carbon::parse(request()->to);
        $includes['tax']             = (int)request()->tax ?? 0;
        $includes['sss']             = (int)request()->sss ?? 0;
        $includes['philhealth']      = (int)request()->philhealth ?? 0;
        $includes['hdmf']            = (int)request()->hdmf ?? 0;
        $includes['tax_type']        = request()->tax_type ?? 'full';
        $includes['sss_type']        = request()->sss_type ?? 'full';
        $includes['philhealth_type'] = request()->philhealth_type ?? 'full';
        $includes['hdmf_type']       = request()->hdmf_type ?? 'full';

        $employee = Employee::where('employee_id', $employee_id)->select('id', 'employee_id', 'firstname', 'middlename', 'lastname')->first();
        $payrolls = self::employeeAttendanceLogs($employee_id, $employee, $first_cutoff, $second_cutoff, $includes);
        $payroll['items'] = $payrolls;
        $payroll['employee'] = $employee;

        $payrollRunItemEntity = PayrollRunItem::where('payroll_run_id', request()->payroll_run_id)->where('employee_id', $employee_id);
        if($payrollRunItemEntity->exists()) {
            $payrollRunItemEntity->update(['payroll' => json_encode($payroll), 'updated_by' => backpack_auth()->user()->id]);
        } else {
            $newPayrollItem = new PayrollRunItem;
            $newPayrollItem->payroll_run_id = request()->payroll_run_id;
            $newPayrollItem->employee_id    = $employee_id;
            $newPayrollItem->full_name      = $employee->full_name;
            $newPayrollItem->payroll        = json_encode($payroll);
            $newPayrollItem->updated_by     = backpack_auth()->user()->id;
            $newPayrollItem->save();
        }
        return response()->json($payroll);
    }

    public function employeeAttendanceLogs ($id, $employee, $first_cutoff, $second_cutoff, $includes)
    {
        $start_first_cutoff             = $first_cutoff;
        $end_first_cutoff               = $second_cutoff;
        $this->total_no_of_days_cutoff  = $start_first_cutoff->diffInDays($end_first_cutoff) - 2;

        $rfid                   = Rfid::where('studentNumber', $id)->first();
        $this->scheduleEntity   = ScheduleTagging::where('employee_id', $id)->with('scheduleTemplate')->first();

        if($rfid !== null && $this->scheduleEntity !== null)
        {
            return  self::GenerateDynamicAttendance($id, $rfid->rfid, 'today', $start_first_cutoff, $end_first_cutoff);
        }
        return;
    }


    private function GenerateDynamicAttendance ($employee_id, $rfid, $period_type, $start_date, $end_date)
    {
        $logs = [];

        $date_from = $start_date->format('M d, Y');
        $date_to   = $end_date->format('M d, Y');

        $original_format_date_start = $start_date;
        $start_day_incrementing     = $start_date->format('Y-m-d');


        while($start_date->format('Y-m-d') <= $end_date->format('Y-m-d')) 
        {
            $assessDate = $start_day_incrementing;
            $subjectLog = self::AuditDateAttendanceLog($rfid, $assessDate);

            if($subjectLog) { $logs[] = $subjectLog; }

            $start_day_incrementing = $original_format_date_start->addDays(1)->format('Y-m-d');
        }

        $total_hours             = collect($logs)->sum('schedule.total_hours');
        $total_actual_minutes_rendered = collect($logs)->sum('actual_minutes');
        $total_weeks             = ceil(count($logs) / 7);
        $total_days              = count($logs);

        $this->salaryEntity     = EmployeeSalary::where('employee_id', $employee_id)->first();
        if($this->salaryEntity === null) { return null; }

        $basic_salary           = $this->salaryEntity->salary;
        $salary                 = $basic_salary / $total_weeks;
        $salary_per_day         = $salary / $total_days;
        $salary_per_hour        = $salary / ( $total_hours > 0 ? $total_hours : 1 );
        $salary_per_minute      = $salary_per_hour / 60;
        $total_deductions       = 0;
        $total_late_and_absent  = 0;
        $admin_pay              = (float)$this->salaryEntity->admin_pay;
        $other_pay              = (float)$this->salaryEntity->other_pay;
        $gross_pay              = 0;
        $net_pay                = 0;
        $total_adjustment       = 0;
        $required_minutes       = ( $this->scheduleEntity->scheduleTemplate->total_weekly_hours * $total_weeks ) * 60;
        $government_services    = [
            'sss' => 0,
            'philhealth' => 0,
            'hdmf' => 0,
            'taxable_income' => 0,
            'tax' => 0,
            'loans' => [
                'sss' => 0,
                'hdmf' => 0
            ]
        ];

        // ADJUSTMENT
        $adjustment         = EmployeeAdjustment::where('payroll_run_id', request()->payroll_run_id)->where('employee_id', $employee_id)->get();
        $total_adjustment   = $adjustment->sum('amount');

        // GROSS PAY
        $gross_pay  = $salary + $other_pay + $admin_pay - $total_late_and_absent;

        foreach ($logs as $key => $log) {
            $logs[$key]['deductions']['late_deduction'] = $log['total_minutes_late'] * $salary_per_minute;
            $total_late_and_absent += $log['total_minutes_late'] * $salary_per_minute;
            $total_deductions += $log['total_minutes_late'] * $salary_per_minute;

            if($log['remarks'] === "ABSENT") {
                $logs[$key]['deductions']['absent'] = $salary_per_day;
                $total_late_and_absent += $salary_per_day;
                $total_deductions += $salary_per_day;
            }
        }

        /** GOVERNMENT SERVICES **/

        // GET THE SSS
        if(request()->sss) {
            $sss = EmployeeMandatorySSS::where('range_of_compensation_min', '<=',  $salary)->where('range_of_compensation_max', '>=', $salary)->first() ?? 0;
            if($sss) {
                if(request()->sss_type === "full") {
                    $government_services['sss'] = (float)$sss->social_security_ee ?? 0;
                    $total_deductions += (float)$sss->social_security_ee ?? 0;
                } else if (request()->sss_type === "half") {
                    $government_services['sss'] = (float)$sss->social_security_ee ? $sss->social_security_ee / 2 : 0;
                    $total_deductions += (float)$sss->social_security_ee ? $sss->social_security_ee / 2 : 0;
                } else {   }
            }
        }

        // GET THE PHILHEALTH
        if(request()->philhealth) {
            $philhealth = EmployeeMandatoryPhilHealth::where('active', 1)->first();
            if($philhealth) {
                $deduction = EmployeeMandatoryPhilHealth::getMonthlyPremiumBySalary($gross_pay);
                
                if(request()->philhealth_type === "full") {
                    $government_services['philhealth'] = $deduction;
                    $total_deductions += $deduction;
                } else if (request()->philhealth_type === "half") {
                    $government_services['philhealth'] = $deduction / 2;
                    $total_deductions += $deduction / 2;
                } else {   }
            }
        }

        // GET THE PAGIBIG
        if(request()->hdmf) {
            $pagibig = EmployeeMandatoryPagIbig::where('active', 1)->first();
            if($pagibig) {
                if(request()->hdmf_type === "full") {
                    $government_services['hdmf'] = $deduction;
                    $total_deductions += $deduction;
                } else if (request()->hdmf_type === "half") {
                    $government_services['hdmf'] = $deduction / 2;
                    $total_deductions += $deduction / 2;
                } else {   }
            }
        }

        // GET THE TAX
        if(request()->tax) {
            $tax = EmployeeTaxManagement::where('active', 1)->first();
            // $taxable_income = ($salary + (float)$this->salaryEntity->other_pay + (float)$this->salaryEntity->admin_pay) - ( $government_services['sss'] + $government_services['hdmf'] + $government_services['philhealth'] - $total_late_and_absent);
            $taxable_income = ($gross_pay) - ( $government_services['sss'] + $government_services['hdmf'] + $government_services['philhealth']);
            $government_services['taxable_income'] = $taxable_income;
            $deduction = EmployeeTaxManagement::getTax($taxable_income);
            // $deduction = EmployeeTaxManagement::getTax($basic_salary);

            if($tax) {
                if(request()->tax_type === "full") {
                    $government_services['tax'] = $deduction;
                    $total_deductions += $deduction;
                } else if (request()->tax_type === "half") {
                    $government_services['tax'] = $deduction / 2;
                    $total_deductions += $deduction / 2;
                } else {   }
            }
        }

        // LOANS
        //  SSS LOAN
        if(request()->sss_loan) {
            $sss_loan = SSSLoan::where('employee_id', $employee_id)->latest()->first();

            if($sss_loan) {
                $sss_start_date     = Carbon::parse($sss_loan->start_date);
                $sss_expiry_date    = Carbon::parse($sss_loan->expiry_date);
                $payroll_date_from  = Carbon::parse(request()->from);
                $payroll_date_to    = Carbon::parse(request()->to);

                if( $sss_start_date->lte($payroll_date_from) && $sss_expiry_date->gte($payroll_date_to) ) {

                    $total_months = $sss_expiry_date->diffInMonths($sss_start_date);
                    if(!$total_months) { $total_months = 1; }

                    $deduction = $government_services['loans']['sss'] = (float)$sss_loan->amount;
                    if(request()->sss_loan_type === "full") {

                        $government_services['loans']['sss'] = $deduction / $total_months;
                        $total_deductions += $deduction / $total_months;

                    } else if (request()->sss_loan_type === "half") {

                        $government_services['loans']['sss'] = ($deduction / $total_months) / 2;
                        $total_deductions += ($deduction / $total_months) / 2;

                    } else {   }
                }
            }
        }

        //  PAGIBIG LOAN
        if(request()->hdmf_loan) {
            $pagibig_loan = PagIbigLoan::where('employee_id', $employee_id)->latest()->first();

            if($pagibig_loan) {
                $pagibig_start_date     = Carbon::parse($pagibig_loan->start_date);
                $pagibig_expiry_date    = Carbon::parse($pagibig_loan->expiry_date);
                $payroll_date_from      = Carbon::parse(request()->from);
                $payroll_date_to        = Carbon::parse(request()->to);

                if( $pagibig_start_date->lte($payroll_date_from) && $pagibig_expiry_date->gte($payroll_date_to) ) {

                    $total_months = $pagibig_expiry_date->diffInMonths($pagibig_start_date);
                    if(!$total_months) { $total_months = 1; }

                    $deduction = $government_services['loans']['hdmf'] = (float)$pagibig_loan->amnount;
                    if(request()->hdmf_loan_type === "full") {

                        $government_services['loans']['hdmf'] = $deduction / $total_months;
                        $total_deductions += $deduction / $total_months;

                    } else if (request()->hdmf_loan_type === "half") {

                        $government_services['loans']['hdmf'] = ($deduction / $total_months) / 2;
                        $total_deductions += ($deduction / $total_months) / 2;

                    } else {   }
                }
            }
        }

        //** GOVERMENT SERVICES **//

        $net_pay    = $gross_pay - $government_services['sss'] - $government_services['philhealth'] - $government_services['hdmf'] - $government_services['tax'] - $government_services['loans']['sss'] - $government_services['loans']['hdmf'];

        if($this->scheduleEntity->deduction_type === 'Based On Hours Per Week') {
             $total_deductions = ( $required_minutes - $total_actual_minutes_rendered ) * $salary_per_minute;
        }


        $data = [
            'includes'              =>  [
                                            'tax'                   => request()->tax,
                                            'tax_type'              => request()->tax_type,
                                            'sss'                   => request()->sss,
                                            'sss_type'              => request()->sss_type,
                                            'philhealth'            => request()->philhealth,
                                            'philhealth_type'       => request()->philhealth_type,
                                            'hdmf'                  => request()->hdmf,
                                            'hdmf_type'             => request()->hdmf_type,
                                            'sss_loan'              => request()->sss_loan,
                                            'sss_loan_type'         => request()->sss_loan_type,
                                            'hdmf_loan'             => request()->hdmf_loan,
                                            'hdmf_loan_type'        => request()->hdmf_loan_type,
                                        ],
            'attendance_logs'       => $logs,
            'required_minutes'      => $required_minutes, 
            'total_actual_minutes_rendered'   => $total_actual_minutes_rendered,
            'basic_salary'          => (float)$this->salaryEntity->salary,
            'total_hours'           => $total_hours,
            'total_weeks'           => $total_weeks,
            'salary'                => $salary - $total_deductions,
            'total_late_and_absent' => $total_late_and_absent,
            'total_deductions'      => $total_deductions,
            'admin_pay'             => $admin_pay,
            'other_pay'             => $other_pay,
            'gross_pay'             => $gross_pay,
            'net_pay'               => $net_pay + $total_adjustment,
            'total_adjustment'      => $total_adjustment,
            'salary_per_day'        => $salary_per_day,
            'salary_per_hour'       => $salary_per_hour, 
            'salary_per_minute'     => $salary_per_minute, 
            'government_services'   => $government_services, 
            'date_period'           => $period_type,
            'date_from'             => $date_from,
            'date_to'               => $date_to,
        ];

        return $data;
    }


    private function AuditDateAttendanceLog  ($rfid, $assessDate)
    {
        if($this->scheduleEntity == null) { return; }

        $todayWeekDay           = Carbon::parse($assessDate)->format('D');
        $todayWeekDay           = lcfirst($todayWeekDay);
        $schedule_timein        = $this->scheduleEntity->scheduleTemplate->{$todayWeekDay . '_timein'};
        $schedule_timeout       = $this->scheduleEntity->scheduleTemplate->{$todayWeekDay . '_timeout'};
        $lunchBreakTimeStart    = $this->scheduleEntity->scheduleTemplate->{'lunch_break_time_start_' . $todayWeekDay};
        $lunchBreakTimeStart    = $lunchBreakTimeStart ? Carbon::parse($lunchBreakTimeStart) : null;
        $lunchBreakTimeEnd      = $this->scheduleEntity->scheduleTemplate->{'lunch_break_time_end_' . $todayWeekDay};
        $lunchBreakTimeEnd      = $lunchBreakTimeEnd ? Carbon::parse($lunchBreakTimeEnd) : null;

        $totalHours             = $this->scheduleEntity->scheduleTemplate->{'no_of_hours_' . $todayWeekDay};
        $isRestDay              = $this->scheduleEntity->scheduleTemplate->{'rest_day_' . $todayWeekDay};
        $lunch_break_in_minutes = $this->scheduleEntity->scheduleTemplate->{'lunch_break_minutes_' . $todayWeekDay};

        if($isRestDay) { return; }

        // FIRST TAP IN 
        $attendance_login   = TurnstileLog::where('rfid', $rfid)
                                            ->whereDate('created_at', Carbon::parse($assessDate))
                                            ->selectRaw('*, date(created_at) as date_pro')
                                            ->first();
        // LAST TAP OUT
        $attendance_logout  = TurnstileLog::where('rfid', $rfid)
                                            ->where('timeout', '!=', null)
                                            ->whereDate('created_at', Carbon::parse($assessDate))
                                            ->selectRaw('*, date(created_at) as date_pro')
                                            ->orderBy('id', 'DESC')
                                            ->first();

        $start_time = $attendance_login->timein  ?? null;
        $end_time   = $attendance_login->timeout ?? null;
        $week_day   = Carbon::parse($assessDate)->format('l');
        $remarks    = 'ABSENT';
        $duration   = null;

        // SALARY VARIABLES
        $annually_salary    = 0;
        $monthly_salary     = 0;
        $daily_salary       = 0;
        $hourly_salary      = 0;
        $minute_salary      = 0;
        $compensation       = 0;
        $total_hours_late   = 0;
        $total_minutes_late = 0;
        $admin_pay          = 0;
        $other_pay          = 0;
        $deduction          = ['late_deduction' => 0];
        $total_deduction    = 0;

        $with_lunch_break   = 1;        
        $todayWeekDay       = Carbon::parse($assessDate)->format('D');
        $sched_timein       = Carbon::parse($this->scheduleEntity->scheduleTemplate->{lcfirst($todayWeekDay) . '_timein'});
        $sched_timeout      = Carbon::parse($this->scheduleEntity->scheduleTemplate->{lcfirst($todayWeekDay) . '_timeout'});
        $hours_per_day      = $sched_timein->diffInHours($sched_timeout) - $with_lunch_break;
        $hourly_pay         = $this->daily_pay / $hours_per_day;
        $minutely_pay       = $hourly_pay / 60;
       
        $schedule = [
            'timein'                    => $sched_timein ? $sched_timein->format('g:i a') : null,
            'timeout'                   => $sched_timeout ? $sched_timeout->format('g:i a') : null,
            'lunch_break_time_start'    => $lunchBreakTimeStart ? $lunchBreakTimeStart->format('g:i a') : null,
            'lunch_break_time_end'      => $lunchBreakTimeEnd ? $lunchBreakTimeEnd->format('g:i a') : null,
            'timeout'                   => $sched_timeout ? $sched_timeout->format('g:i a') : null,
            'week_day'                  => $todayWeekDay,
            'total_lunch_in_minutes'    => $lunchBreakTimeEnd && $lunchBreakTimeEnd ? $lunchBreakTimeEnd->diffInMinutes($lunchBreakTimeStart) : 0,
            'total_hours'               => $totalHours,
        ];


        // CALCULATE THE TOTAL DURATION
        if($start_time !== null && $end_time !== null)
        {
            $start_time                = $start_time ? Carbon::parse($start_time) : null;
            $end_time                  = $end_time ? Carbon::parse($end_time) : null;
            $diffInHours               = $end_time->diffInHours($start_time);
            $diffInMinutes             = $end_time->diffInMinutes($start_time);
            $diffInSeconds             = $end_time->diffInSeconds($start_time);
            $diff                      = $end_time->diff($start_time);
            $duration['diffInHours']   = $diffInHours;
            $duration['diffInMinutes'] = $diffInMinutes;
            $duration['diffInSeconds'] = $diffInSeconds;
            $duration['diff']          = $diff;
        }


         // PREPARE THE DATA
        if ($start_time !== null && $end_time !== null && $week_day !== 'Sunday') 
        { 
            
            $remarks        = 'PRESENT'; 
            $monthly_salary = $this->salary / 2;

            // GET THE SCHEDULE OF THE EMPLOYEE AND CHECK IF THERE"S A LATE FOUND
            $schedule_timein  = $sched_timein;
            $schedule_timeout = $sched_timeout;

            
            // CHECK IF THE USER IS LATE

            // if ($this->scheduleEntity->deduction_type === "Minutely Late") {
                $total_minutes_late += $schedule_timein->diffInMinutes($start_time);
            // }

            // if ($this->scheduleEntity->deduction_type === "Hourly Late") {
                // $total_minutes_late += $schedule_timein->diffInHours($start_time) * 60;
            // }   

            // if($start_time > $schedule_timein)
            // {
            //     // $total_hours_late            += $schedule_timein->diffInHours($start_time);
            //     // $deduction['late_deduction'] += $total_minutes_late;
            //     // $total_deduction             += $total_minutes_late;
            // }

            // if($end_time < $schedule_timeout)
            // {   
            //     // dd($end_time->format('g:i'), $schedule_timeout->format('g:i'), "EARLY TIME OUT");
            //     // $total_hours_late            += $schedule_timeout->diffInHours($end_time);
            //     // $total_minutes_late          += $schedule_timeout->diffInMinutes($end_time);
            //     // $deduction['late_deduction'] += $total_minutes_late * $minutely_pay;
            //     // $total_deduction             += $total_minutes_late * $minutely_pay;
            // }

            $compensation = $this->daily_pay;


        } 
        else if ($start_time == null && $end_time !== null && $week_day !== 'Sunday') 
        { 
            $remarks             = 'NTI'; 
            $deduction['absent'] =  $this->daily_pay;
            $total_deduction     += $this->daily_pay;
        }
        else if ($start_time !== null && $end_time  == null && $week_day !== 'Sunday') 
        { 
            $remarks             = 'NTO';  
            $deduction['absent'] = $this->daily_pay;
            $total_deduction     += $this->daily_pay;
        }
        else if ($week_day  == 'Sunday')                                               
        { 
            $remarks = 'NO CLASSESS';
        }
        else                                                                           
        { 
            $remarks             = $remarks;
            $deduction['absent'] = $this->daily_pay;
            $total_deduction    += $this->daily_pay;
        }


        // CHECK HOLIDAY
        $holiday = Holiday::with(['schoolYear' => function ($q) { $q->active(); } ])->whereDate('date', Carbon::parse($assessDate))->first();

        if($holiday !== null) 
        {
            $remarks            = 'Holiday: ' . $holiday->name;
            $total_minutes_late = 0;
        }

        $data = [
                    'start_time'           => $start_time == null ? 'NTI' : $start_time,
                    'end_time'             => $end_time   == null ? 'NTO' : $end_time,
                    'actual_minutes'       => $start_time && $end_time ? $end_time->diffInMinutes($start_time) : 0,
                    'start_time_formatted' => $start_time == null ? 'NTI' : Carbon::parse($start_time)->format('g:i A'),
                    'end_time_formatted'   => $end_time   == null ? 'NTO' : Carbon::parse($end_time)->format('g:i A'),
                    'date_string'          => $assessDate,
                    'date_format'          => Carbon::parse($assessDate)->format('F d, Y'),
                    'week_day'             => $week_day,
                    "remarks"              => $remarks,
                    // "duration"             => $duration,
                    'total_minutes_late'   => $total_minutes_late,
                    'schedule'             => $schedule, 
                ];

        return $data;
    }

    public function addAdjustment ()
    {

        $payroll = PayrollRun::where('id', request()->payroll_run_id)->first();
        if($payroll) {
            if($payroll->status === "PUBLISHED") {
                return response()->json(['error' => true, 'messsage' => 'This Payroll Is Already Published', 'data' => null]);
            }
        }

        $validator = Validator::make(request()->all(), [
            'payroll_run_id'    => 'required|exists:payroll_runs,id',
            'employee_id'       => 'required|exists:employees,employee_id',
            'amount'            => 'required|numeric',
            'description'       => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['error' => true, 'messsage' => 'Missing Required Parameters', 'data' => $validator->errors()]);
        }

        $empAdjEntity                   = new EmployeeAdjustment;
        $empAdjEntity->payroll_run_id   = request()->payroll_run_id;
        $empAdjEntity->employee_id      = request()->employee_id;
        $empAdjEntity->amount           = request()->amount;
        $empAdjEntity->description      = request()->description;
        
        if($empAdjEntity->save()) {
            return response()->json(['error' => false, 'message' => 'Successfully Added Adjustment', 'data' => $empAdjEntity]);
        }
        return response()->json(['error' => true, 'message' => 'Error Adding Adjustments', 'data' => null]);
    }

    public function publishPayroll ($id)
    {   
        $payroll = PayrollRun::where('id', $id)->first();
        if($payroll) {
            if($payroll->status === 'PUBLISHED') {
                return response()->json(['error' => true, 'message' => 'Already Published']);
            }
            $updatePayroll = PayrollRun::where('id', $id)->update(['status' => 'PUBLISHED']);
            if($updatePayroll) {
                return response()->json(['error' => false, 'message' => 'Successfully Published']);
            }
            return response()->json(['error' => true, 'message' => 'Error Publishing...']);
        }
    }

    public function getAdjustment ($payroll_run_id, $employee_id) 
    {
        $adjustments = EmployeeAdjustment::where('payroll_run_id', $payroll_run_id)->where('employee_id', $employee_id)->get();
        return response()->json($adjustments);
    }
}
