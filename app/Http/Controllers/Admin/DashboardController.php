<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Announcement;
use App\Models\AnnouncementRead;
use App\Models\CurriculumManagement;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Enrollment;
use App\Models\Fund;
use App\Models\InventoryTransaction;
use App\Models\ItemInventory;
use App\Models\NonAcademicDepartment;
use App\Models\SchoolYear;
use App\Models\ScheduleTagging;
use App\Models\ScheduleTemplate;
use App\Models\SmsLog;
use App\Models\SpecialDiscount;
use App\Models\Student;
use App\Models\TextBlast;
use App\Models\TrackManagement;
use App\Models\TurnstileLog;
use App\Models\YearManagement;
use App\Models\SystemAttendance;

use App\Models\Meeting;
use App\Models\SchoolCalendar;
use App\Http\Controllers\Admin\MeetingCrudController;

use App\FundInTransaction;
use App\SelectedOtherProgram;
use App\PaymentHistory;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * [index description]
     * @return [type] [description]
     */
    public function index()
    {
        $announcements = $this->announcements();
        
        $schoolyear = SchoolYear::where('isActive',1)->first();
        $schoolyear = $schoolyear !== null ? $schoolyear : null;

        $school_year = null;
        $enrolment_data_total = 0;

        $students = 0;
        $rawChartDS = [];
        $rawChartLabel = [];
        $dataMaleGender = null;
        $dataFemaleGender = null;
        $enrolment_by_level = [];
        if(SchoolYear::all()->count() <= 0){
            \Alert::warning("First Step: Create School Year and Select")->flash();
            return redirect('admin/schoolyear');
        }

        if(Department::all()->count() <= 0) {
            \Alert::warning("Next Step: Setup your School Academic Departments")->flash();
            return redirect('admin/department');
        }

        if(NonAcademicDepartment::all()->count() <= 0) {
            \Alert::warning("Next Step: Setup your School Non-Academic Departments")->flash();
            return redirect('admin/non-academic-department');
        }
        if(YearManagement::all()->count() <= 0) {
            \Alert::warning("Next Step: Setup Year Levels")->flash();
            return redirect('admin/year_management');
        }
        // if(TrackManagement::all()->count() <= 0) {
        //     \Alert::warning("Next Step: Setup Your School Tracks (Branches)")->flash();
        //     return redirect('admin/strand');
        // }
        if(CurriculumManagement::all()->count() <= 0) {
            \Alert::warning("Next Step: Setup Your School Curriculum")->flash();
            return redirect('admin/curriculum_management');
        }

        $school_year = $schoolyear->schoolYear; 
        $enrolment_data_total = Enrollment::where('school_year_id', $schoolyear->id)
                                    ->where('deleted_at', null)
                                    ->where('is_applicant', 0)
                                    ->groupBy('studentnumber')
                                    ->get();
        // dd($enrolment_data_total);

        $enrolment_data_full = $enrolment_data_total->where('term_type', "Full")->count();
        $enrolment_data_first = $enrolment_data_total->where('term_type', "First")->count();
        $enrolment_data_second = $enrolment_data_total->where('term_type', "Second")->count();

        $enrolment_data = [
            "enrollment_total"              => $enrolment_data_full + $enrolment_data_first + $enrolment_data_second,
            "enrollment_data_full"          => $enrolment_data_full,
            "enrollment_data_first"         => $enrolment_data_first,
            "enrollment_data_second"        => $enrolment_data_second,

        ];


        // ATTENDANCE AND TAP IN AND OUT LOGS
        $user = backpack_auth()->user(); 

        $my_attendance = [
            'tap_in_today'                  => null,
            'tap_out_today'                 => null,
            'earliest_tap_in_this_month'    => null,
            'tardiness_in_this_month'       => null,
            'attendance_logs'               => [],
            'myschedule'                    => [],
        ];

        $schedule = null;

        if($user->employee_id !== null) {
            $employee = Employee::where('id', $user->employee_id)->with(['schedule' => function ($query) { $query->with('scheduleTemplate'); }])->first();
            if($employee !== null) {
               
                $today_logs_asc = TurnstileLog::where('rfid', $employee->rfid)->whereDate('created_at', Carbon::today())->orderBy('created_at', 'ASC')->select(['id', 'rfid', 'timein', 'timeout'])->get()->toArray();

                $total_logs     = count($today_logs_asc);
                $schedule       = $employee->schedule;

                if($total_logs > 0) {

                    $tapInToday                         = $today_logs_asc[0]['timein'] ? Carbon::parse($today_logs_asc[0]['timein'])->format('h:i a') : null;
                    $tapOutToday                        = array_last($today_logs_asc)['timeout'] ? Carbon::parse(array_last($today_logs_asc)['timeout'])->format('h:i a') : null;
                    $my_attendance['tap_in_today']      = $tapInToday;
                    $my_attendance['tap_out_today']     = $tapOutToday;
                    $my_attendance['attendance_logs']   = TurnstileLog::where('rfid', $employee->rfid)->orderBy('created_at', 'desc')->take(10)->get();
                    $logs_by_current_month              = TurnstileLog::where('rfid', $employee->rfid)
                                                                        ->whereMonth('created_at', now()->month)
                                                                        ->select(['id', 'rfid', 'timein', 'timeout', 'created_at'])
                                                                        ->get();
                    $schedule                           = ScheduleTagging::where('employee_id', $employee->employee_id)->first();

                    $my_attendance['myschedule']        = $schedule ? $schedule->scheduleTemplate : null;

                    $total_logs_by_current_month        = count($logs_by_current_month);

                    if(count($logs_by_current_month) > 0) 
                    {
                        // EARLIEST TAP IN THIS CURRENT MONTH
                        $earliestTapInThisMonth                         = $logs_by_current_month->sortBy('timein')->first();
                        $my_attendance['earliest_tap_in_this_month']    = $earliestTapInThisMonth;

                          /***************************************/
                         /* GET TARDINESS IN THIS CURRENT MONTH */
                        /***************************************/
                        $tardinessInThisMonth   = $logs_by_current_month->sortByDesc('timeout')->first(); 
                        $tardiness_timeout      = $tardinessInThisMonth->timeout ? Carbon::parse($tardinessInThisMonth->timeout)->format('H:i') : '-';

                        // Get Week Day As Name
                        $tardiness_week_name    = strtolower(Carbon::parse($tardinessInThisMonth->created_at)->format('D'));
                        $schedule_timeout = null;
                        if($schedule) {
                            $schedule_timeout       = Carbon::parse($schedule->scheduleTemplate->{$tardiness_week_name . '_timeout'})->format('H:i');
                        }

                        // IF LATE THEN, RETURN THE TARDINESS
                        // if(Carbon::parse($tardiness_timeout)->gt(Carbon::parse($schedule_timeout))) {
                        //     $my_attendance['tardiness_in_this_month'] = $tardinessInThisMonth;
                        // }
                    } 
                }

            }
        }
        $weekDayName = strtolower(now()->format('D'));
        $timein     = $schedule ? $schedule->scheduleTemplate->{$weekDayName . '_timein'} : null;
        $timeout    = $schedule ? $schedule->scheduleTemplate->{$weekDayName . '_timeout'} : null;

        $my_attendance['schedule_timein'] = $timein ? Carbon::parse($timein)->format('h:i a') : '-';
        $my_attendance['schedule_timeout'] = $timeout ? Carbon::parse($timeout)->format('h:i a') : '-';
        $my_attendance['schedule_name'] = $schedule ? $schedule->scheduleTemplate->name : '-';

        $my_attendance = (object)$my_attendance;

        $my_attendance = (object)$my_attendance;

        // SYSTEM ATTENDANCE
        $currentDate     =  Carbon::now()->toDateString();
        $mySystemAttendance =  SystemAttendance::where('user_id', backpack_auth()->user()->id)
                                    ->where('created_at', '>=', $currentDate)
                                    ->where('created_at', '<=', $currentDate . ' 23:59:59')
                                    ->first();
        $system_attendance = [
            'tap_in_today'    => null,
            'tap_out_today'   => null,
            'tap_bg'          => 'bg-red',
            'tap_color'       => 'text-danger'
        ];
        if($mySystemAttendance)
        {
            if($mySystemAttendance->time_in)
            {
                $system_attendance['tap_in_today']  = date("h:i:s A", strtotime($mySystemAttendance->time_in));
            }
            if($mySystemAttendance->time_out)
            {
                $system_attendance['tap_out_today'] = date("h:i:s A", strtotime($mySystemAttendance->time_out));
            } 
        }
        $system_attendance = (object)$system_attendance;

        if(backpack_auth()->user()->employee)
        {
            $nearestMeeting = Meeting::getEmployeeNearestMeeting(backpack_auth()->user()->employee->id);
        }
        else
        {
            $nearestMeeting = null;
        }

        $upcoming_calendar  =   SchoolCalendar::where('start_at', '>=', Carbon::now()->toDateString())
                                    ->where('end_at', '>=', Carbon::now()->toDateString())
                                    ->orderBy('start_at', 'ASC')
                                    ->orderBy('end_at', 'ASC')
                                    ->first();

        // $payment_dues  =   $enrolment_data_total->where('remaining_balance', '>', 0)
        //                         ->sortBy([
        //                             ['level_id', 'asc']
        //                         ]);

        $data = compact(
            'enrolment_data',
            'my_attendance',
            // 'enrolment_by_level',
            // 'salesChart',
            // 'posSales',
            // 'rawChartDS',
            // 'rawChartLabel',
            // 'dataMaleGender',
            // 'dataFemaleGender',
            'school_year',
            // 'total_blast_sms',
            // 'entered_student',
            // 'entered_employee',
            // 'total_sms',
            // 'lastlogins',
            // 'enrollment_gender_male',
            // 'enrollment_gender_female',
            'announcements',
            'system_attendance',
            'nearestMeeting',
            // 'payment_dues',
            'upcoming_calendar'
        );

        return view('vendor.backpack.base.dashboard', $data);

        // }
        // else {
        //     return redirect('admin/student/create');
        // }
       
    }

    private function announcements ()
    {
        $announcements  =   Announcement::whereDate('start', '<=', Carbon::today())
                                ->whereDate('end', '>=', Carbon::today())
                                ->orderBy('start', 'ASC')
                                ->take(5)
                                ->get();
        return $announcements;
    }

    /**
     * [SalesChart]
     */
    private function SalesChart ()
    {
        $now      = Carbon::now();
        $thisYear = Carbon::createFromFormat('Y-m-d H:i:s', $now)->year;
        $lastYear = $thisYear - 1;

        $PaymentHistoryThisYear  = PaymentHistory::whereYear('created_at', $thisYear)->get()
                                                ->groupBy(function($date) {
                                                    return Carbon::parse($date->created_at)->format('m'); 
                                                })->sortBy('month');

        $PaymentHistoryLastYear = PaymentHistory::whereYear('created_at', $lastYear)->get()
                                                ->groupBy(function($date) {
                                                    return Carbon::parse($date->created_at)->format('m');
                                                })->sortBy('month');
        
        $totalMonths = 12;

        /**
         * [$monthlyTotalThisYear Monthly Total Of This year]
         * @var array
         */
        $monthlyTotalThisYear = [];
        $lastKeyThisYear      = $PaymentHistoryThisYear->keys()->last();

        for($i = 1; $i <= $lastKeyThisYear; $i++) {
            foreach ($PaymentHistoryThisYear as $key => $value) {
                $k = (int)$key;
                if($k === $i) {
                    $monthlyTotalThisYear[$k] = $value->pluck('amount')->sum();
                    break;
                } else if ($i < $k) {
                    $monthlyTotalThisYear[$i] = 0;
                    break;
                }
            }
            $monthlyTotalThisYear[$i + 1] = 0;
        }
        array_pop($monthlyTotalThisYear);

        $totalAnnualSalesThisYear = collect($monthlyTotalThisYear)->sum();




        /**
         * [$monthlyTotalLastYear Monthly Total Of last year]
         * @var array
         */
        $monthlyTotalLastYear = [];
        $lastKeyThisYear      = $PaymentHistoryLastYear->keys()->last();

        for($i = 1; $i <= $lastKeyThisYear; $i++) {
            foreach ($PaymentHistoryLastYear as $key => $value) {
                $k = (int)$key;
                if($k === $i) {
                    $monthlyTotalLastYear[$k] = $value->pluck('amount')->sum();
                    break;
                } else if ($i < $k) {
                    $monthlyTotalLastYear[$i] = 0;
                    break;
                }
            }
            $monthlyTotalLastYear[$i + 1] = 0;
        }
        array_pop($monthlyTotalLastYear);

        $totalAnnualSalesLastYear = collect($monthlyTotalLastYear)->sum();




        /**
         * [$otherProgramThisYear Get All Total Amount Of Other Programs This Current Year]
         * @var [type]
         */
        $selectedOtherProgramThisYear    = SelectedOtherProgram::whereYear('created_at', $thisYear)->with('otherProgram')->get();
        $totalOtherProgramAmountThisYear = $selectedOtherProgramThisYear->pluck('otherProgram')->sum("amount");

        /**
         * [$otherProgramThisYear Get All Total Amount Of Other Programs This Last Year]
         * @var [type]
         */
        $selectedOtherProgramLastYear    = SelectedOtherProgram::whereYear('created_at', $lastYear)->with('otherProgram')->get();
        $totalOtherProgramAmountLastYear = $selectedOtherProgramLastYear->pluck('otherProgram')->sum("amount");





        /**
         * [$specialDiscountThisYear Get All Total Amount Of Special Discount This Current Year]
         * @var [type]
         */
        $specialDiscountThisYear         = SpecialDiscount::whereYear('created_at', $thisYear);
        $totalSpecialDiscountThisYear    = $specialDiscountThisYear->sum("amount");

        /**
         * [$specialDiscountThisYear Get All Total Amount Of Special Discount This Last Year]
         * @var [type]
         */
        $specialDiscountLastYear         = SpecialDiscount::whereYear('created_at', $lastYear);
        $totalSpecialDiscountLastYear    = $specialDiscountLastYear->sum("amount");




        /**
         * [$topUpThisYear Get All Total Amount of Student Top-up Current Year]
         * @var [type]
         */
        $topUpThisYear      = Fund::whereYear('created_at', $thisYear)->get();
        $totalTopUpThisYear = $topUpThisYear->sum("amount_tendered");

        /**
         * [$topUpThisYear Get All Total Amount of Student Top-up Last Year]
         * @var [type]
         */
        $topUpLastYear      = Fund::whereYear('created_at', $lastYear)->get();
        $totalTopUpLastYear = $topUpLastYear->sum("amount_tendered");


        $thisYear = collect([
            "monthly_total_in_array" => $monthlyTotalThisYear,
            "total_annual"           => $totalAnnualSalesThisYear,
            "total_other_programs"   => $totalOtherProgramAmountThisYear,
            "total_special_discount" => $totalSpecialDiscountThisYear,
            "total_student_top_up"   => $totalTopUpThisYear,
            'year'                   => $thisYear,
        ]);

        $lastYear = collect([
            "monthly_total_in_array" => $monthlyTotalLastYear,
            "total_annual"           => $totalAnnualSalesLastYear,
            "total_other_programs"   => $totalOtherProgramAmountLastYear,
            "total_special_discount" => $totalSpecialDiscountLastYear,
            "total_student_top_up"   => $totalTopUpLastYear,
            'year'                   => $lastYear,
        ]);

        $todaySales = PaymentHistory::whereDate('created_at', Carbon::today())->get()->sum('amount');

        $result = collect([
            "last_year"             => $lastYear,
            "this_year"             => $thisYear,
            "today_sales"           => $todaySales,
        ]);

        return $result;
    }

    /**
     * POS SALES CHART
     */
    // public function POSSalesChart()
    // {
    //     $iT = InventoryTransaction::all();

    //     $totalPOSStudentToday  = InventoryTransaction::where('client_type', 'Student/Teacher')
    //                                                 ->where('user_type', 'student')
    //                                                 ->whereDate('created_at', Carbon::today())->get();

    //     $totalPOSEmployeeToday = InventoryTransaction::where('client_type', 'Student/Teacher')
    //                                                 ->where('user_type', 'employee')
    //                                                 ->whereDate('created_at', Carbon::today())->get();

    //     $totalPOSWalkInToday   = InventoryTransaction::where('client_type', 'Walk-In/Cash')
    //                                                 ->where('user_type', null)
    //                                                 ->whereDate('created_at', Carbon::today())->get();

    //     $inventoryTransactions = InventoryTransaction::where('created_at', '>=', Carbon::now()->startOfMonth()->subMonth()->toDateTimeString())->get()->toArray();

    //     $top10 = [];

    //     foreach ($inventoryTransactions as $key => $value) {
    //         foreach (json_decode($value["items"]) as $key2 => $val2) {
    //             $top10[] = $val2;             
    //         }
    //     }

    //     $top10 = collect($top10)->groupBy('item_id');
    //     $top10Array = [];

    //     foreach($top10 as $key => $value) {            
    //         $top10Array[] = [
    //             "item_id"        => $key, 
    //             "count"          => $value->sum('quantity'),
    //         ];
    //     }

    //     foreach ($top10Array as $key => $value) {
    //         $item = ItemInventory::where('id', $value["item_id"])->first()->toArray();
    //         if($item !== null) {
    //             $top10Array[$key]["item_inventory"] = $item;
    //         }
    //     }

    //     // ORDER BY TOP 10 
    //     $top10 = collect($top10Array)->sortBy('count')->reverse();

    //     // MERGE SAME COUNT
    //     $filteredTop10 = [];
    //     $oldItemCount = 0;
    //     $index        = 0;
    //         // dd($top10);
    //     foreach ($top10 as $key => $value) {
    //         if($oldItemCount != $value["count"]) {
    //             $filteredTop10[] = $value;
    //             $oldItemCount = $value["count"];
    //             $index++;
    //         } else {
    //             // dd($filteredTop10, $index - 1);
    //             $idx = $index - 1;
    //             $filteredTop10[$idx]['item_inventory']['name'] = $filteredTop10[$idx]['item_inventory']['name'] . ', <br> ' . $value["item_inventory"]['name'];
    //         }
    //     }

    //     // dd($filteredTop10);

    //     // dd($top10);
    //     $result = collect([
    //         "total_student_today" => count($totalPOSStudentToday),
    //         "total_teacher_today" => count($totalPOSEmployeeToday),
    //         "total_walk_in_today" => count($totalPOSWalkInToday),
    //         "top_ten_bough_items" => collect($filteredTop10)->take(10),
    //     ]);

    //    return $result;
    // }


}