<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Rfid;
use App\Models\TurnstileLog;
use App\Models\StudentAccount;
use App\Models\Tuition;
use App\Models\Enrollment;
use App\Models\SpecialDiscount;
use App\Models\OtherProgram;
use App\Models\PaymentMethod;
use App\Models\Fund;
use Auth;
use Carbon\Carbon;

use App\Models\SchoolYear;
use App\Models\SectionManagement;
use App\Models\StudentSectionAssignment;
use App\Models\Requirement;

use App\Models\Assignment;
use App\Models\StudentSubmittedAssignment;
use App\Models\OnlineClassStudentProgress;

use App\AdditionalFee;
use App\PaymentHistory;
use App\SelectedOtherFee;
use App\SelectedPaymentType;
use App\SelectedOtherProgram;
use App\SelectedOtherService;
use App\Models\Discrepancy;
use App\Models\OtherService;
use App\Models\OnlineClassQuiz;
use App\Models\QuipperStudentAccount;

use App\Models\Goal;

// PAYNAMIC
use App\Http\Controllers\Paynamic;

// PAYPAL
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Authorization;
use PayPal\Api\Capture;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Sale;
use PayPal\Api\Transaction;
use PayPal\Api\Webhook;
use PayPal\Api\WebhookEventType;

use App\Models\OnlinePayment;
use App\Http\Controllers\OnlinePaymentController as PaymentController;
use App\Http\Controllers\Student\OnlineClassController as OnlineClassController;

use App\Http\Controllers\BBB;

class StudentController extends Controller
{

    private $student;
    private $user;
    private $student_classes;

    public function __construct ()
    {
        $this->middleware(function ($request, $next) 
        {
            $this->user     = Auth::user();
            $this->student  = $this->user->student;

            return $next($request);
        });

        if( (env('PAYMENT_GATEWAY') !== null || env('PAYMENT_GATEWAY') !== "") && strtolower(env('PAYMENT_GATEWAY')) == "paynamics") {} 
        else {

            $this->schoolYear = SchoolYear::active()->first();
            $status = strtolower(env('PAYPAL_STATUS')) === 'production' ? 'LIVE' : 'SANDBOX';

            // SET PAYPAL CREDENTIALS (Development or Production)
            $oauthTokenCredential   = new OAuthTokenCredential(env('PAYPAL_' . $status . '_CLIENT_ID'), env('PAYPAL_' . $status . '_CLIENT_SECRET'));
            $this->apiContext       = new ApiContext($oauthTokenCredential);

            if($status === 'LIVE') $this->apiContext->setConfig(['mode' => 'live']);

            $webhook = new Webhook;
            $webhook->setUrl(env('APP_URL') . 'online-payment/webhooks');

            // Create Event Webhook Type
            $webhookEventTypes = array();
            $webhookEventTypes[] = new WebhookEventType(
                '{
                    "name":"PAYMENT.AUTHORIZATION.CREATED"
                }'
            );
            $webhookEventTypes[] = new WebhookEventType(
                '{
                    "name":"PAYMENT.CAPUTRE.COMPLETED"
                }'
            );
            $webhook->setEventTypes($webhookEventTypes);
        }
    }

    public function index2()
    {
        $this->student          =   $student    =   auth()->user()->student;
        $my_classes             =   Student::getOnlineClasses($student->id);
        $my_classes             =   $my_classes ? $my_classes : collect([]);

        $submittedAssignments   =   StudentSubmittedAssignment::where('student_id', $student->id)->get();
        $assignments            =   Assignment::whereIn('online_class_id', $my_classes->pluck('id'))
                                            ->whereNotIn('id', $submittedAssignments->pluck('assignment_id'))
                                            ->orderBy('due_date', 'DESC')
                                            ->get();

        $studentClassProgresses =   OnlineClassStudentProgress::where('student_id', $student->id)
                                        ->whereIn('online_class_id', $my_classes->pluck('id'))
                                        ->get();

        $goals                  =   Goal::where('user_id', $student->id)
                                        ->where('user_type', 'App\Models\Student')
                                        ->limit(8)
                                        ->get();

        $onlineClassController  =   new OnlineClassController();
   
        $student                =   auth()->user()->student;
        $student_section        =   $onlineClassController->studentSectionAssignment();
        $my_classes             =   $onlineClassController->getOnlineClasses();
        $quipperAccount         =   QuipperStudentAccount::where('student_id', $student->id)->first();

        $classQuizzes           =   OnlineClassQuiz::with('onlineClass', 'quiz')
        ->whereIn('online_class_id', $my_classes ? $my_classes->pluck('id') : [])
        ->orderBy('start_at', 'DESC')
        ->get();

        $data = [
            'title'         => 'Dashboard',
            'goals'         => $goals,
            'student'       => $student,
            'assignments'   => $assignments,
            'my_classes'    => $my_classes ? $my_classes : collect([]),
            'studentClassProgresses' => $studentClassProgresses,
            'classQuizzes' => $classQuizzes
        ];

        return view('studentPortal.dashboardv2', $data);
    }

    public function index()
    {
        $this->student          =   $student    =   auth()->user()->student;
        $studentRequirements    = Requirement::where('student_id',  $this->student->id)->first();
        if(!$studentRequirements)
        {
            $requirement = new Requirement;
            $requirement->student_id = auth()->user()->student->id;
            $requirement->save();
        }
        $enrollments            =   config('settings.viewstudentaccount') 
                                        ? Enrollment::where('studentnumber', $this->student->studentnumber)
                                            ->with('schoolyear')
                                            ->with('department')
                                            ->with('level')
                                            ->with('track')
                                            ->with('tuition')
                                            ->with('commitmentPayment')
                                            ->with(['studentSectionAssignment' => function ($query) {
                                                $query->where('students', 'like', '%' . $this->student->studentnumber . '%');
                                                $query->with(['section' => function ($q) {
                                                    $q->with('level');
                                                }]);
                                            }])
                                            ->orderBy('created_at', 'ASC')
                                            ->get()

                                        : collect([]);
        $title = "Dashboard";

        $paymentController = new PaymentController();
        
        $data = [
            'fee'            => $paymentController->getFee(),
            'fixedAmount'    => $paymentController->getFixedAmount(),
            'paymentMethods' => PaymentMethod::orderBy('name', 'ASC')->where('code', '!=', null)->get()
        ];

        return view('studentPortal.my_account_new',compact(['student', 'title', 'studentRequirements', 'enrollments']))->with($data);
    }

    public function attendance()
    {
        $title = "Attendance";
    	$student = Auth::user()->with('student')->first()->student;

        if($student !== null)
        {
    	    $attendance = TurnstileLog::where('rfid',$student->rfid)->get();
    	    return view('student.attendance',compact(['student','attendance', 'title']));
        } 
        else 
        {
            $attendance = null;
            return view('student.attendance',compact(['student','attendance', 'title']));

        }
    }

    public function attendanceLogs (Request $request)
    {
        // $period = $request->
        if($request->input('period') == null) {
            return ["status" => "ERROR", "message" => "No Selected Period"];
        }

        $student  = auth()->user()->student;

        if(!$student->rfid_number) {
            return ["status" => "ERROR", "message" => "This RFID is not tagged."];
        }
        
        $rfid = $student->rfid_number;
        $data = [];

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


        return response()->json($data);

        // $id = Auth::id();
        // $student = Student::where('id',$id)->first();
        // $rfid = $student->rfid->rfid;

        // $attendance = TurnstileLog::where('rfid',$student->rfid->rfid)->get();

        // return view('student.attendance',compact(['student','attendance']));
    }

    public function attendanceLogsRange($start_date, $end_date)
    {

        $rfid = Rfid::where('studentNumber', request()->user()->studentnumber)->firstOrFail()->rfid;

        if($rfid){
             $logs = TurnstileLog::where('rfid',$rfid->rfid)
                        ->whereDate('created_at', '>=' , $start_date)
                        ->whereDate('created_at', '<=' , $end_date)
                        ->get();

            return response()->json($logs);
        }else {
            return "No Student Found";
        }

       
    }



    private function ValidateDate($date, $format = 'Y-m-d')
    {
        $d = new \DateTime();
        $d = $d->createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
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

    // public function account(Request $request){

    //     $user           = auth()->user();
    //     $enrollments    = Enrollment::where('studentnumber', $user->studentnumber)
    //                                 ->with('schoolyear')
    //                                 ->with('department')
    //                                 ->with('level')
    //                                 ->with('track')
    //                                 ->with('tuition')
    //                                 ->with('commitmentPayment')
    //                                 ->orderBy('created_at', 'ASC')
    //                                 ->get();


    //     return view('student.account', compact('enrollments'));
    // }


    public function enrollments ()
    {
        $title       = "Enrollments";
        $enrollments = Enrollment::where('studentnumber', auth()->user()->studentnumber)
                                ->with('schoolyear')
                                ->with('department')
                                ->with('level')
                                ->with('track')
                                ->with('tuition')
                                ->with('commitmentPayment')
                                ->with(['studentSectionAssignment' => function ($query) {
                                    $query->where('students', 'like', '%' . auth()->user()->studentnumber . '%');
                                    $query->with(['section' => function ($q) {
                                        $q->with('level');
                                    }]);
                                }])
                                ->orderBy('created_at', 'ASC')
                                ->get();

        return view('student.enrolled_list', compact('enrollments', 'title'));
    }

    public function viewTuition ($enrollment_id)
    {
        $title       = "Tuitions";
        $studentnumber = auth()->user()->studentnumber;

        $enrollment = Enrollment::where('id', $enrollment_id)
                                ->where('studentnumber', $studentnumber)
                                ->with('tuition', 'paymentHistories')
                                ->first();

        return view('studentPortal.view_account', compact('enrollment', 'title'));
    }

    public function allTuitionFeeData($enrollment_id)
    {
        $checkEnrollmentStudent = Enrollment::where('id', $enrollment_id)->first();
        
        $_enrollment = Enrollment::where('id',$enrollment_id)
                                ->with(['tuition' => function ($q) {
                                    $q->with('school_year');
                                    $q->with('year_management');
                                }])
                                ->with('commitmentPayment');
                                // ->with('student:id,studentnumber,schoolyear,level_id,firstname,lastname,middlename,gender')
                                // ->first(); 

        if($checkEnrollmentStudent) {
            if($checkEnrollmentStudent->studentnumber === null) {
                $_enrollment = $_enrollment->with('student:id,studentnumber,schoolyear,level_id,firstname,lastname,middlename,gender')->first();
            } else {
                $_enrollment = $_enrollment->with('studentById:id,studentnumber,schoolyear,level_id,firstname,lastname,middlename,gender')->first();
            }
        }

        if($_enrollment == null) {
            return "Student Number " . $studentnumber . " Not Found";
        }

        $student = $_enrollment->student;
        $tuition = $_enrollment->tuition;

        $selected_other_programs      = SelectedOtherProgram::where('enrollment_id', $enrollment_id)->with('user')->with('otherProgram')->get();
        $total_selected_other_program = $selected_other_programs->sum('otherProgram.amount');

        $selected_other_services      = SelectedOtherService::where('enrollment_id', $enrollment_id)->with('user')->with('otherServices')->get();
        $total_selected_other_service = $selected_other_services->sum('otherService.amount');

        $additional_fees        = AdditionalFee::where('enrollment_id', $enrollment_id)->with('user')->get();
        $total_additional_fee   = $additional_fees->sum('amount');
        
        $discrepancies          = Discrepancy::where('enrollment_id', $enrollment_id)->with('user')->get();
        $total_discrepancy      = $discrepancies->sum('amount');

        $other_program_lists     = OtherProgram::where('qbo_map', '!=', null)->where('school_year_id', $_enrollment->school_year_id)->get();
        $other_service_lists     = OtherService::where('qbo_map', '!=', null)->where('school_year_id', $_enrollment->school_year_id)->get();
        $special_discounts_lists = SpecialDiscount::where('enrollment_id', $enrollment_id)->with('user')->get();
        $payment_histories       = PaymentHistory::where('enrollment_id', $enrollment_id)->with('user')->with('paymentMethod')->get();

        $total_special_discount = $special_discounts_lists->sum('amount');
        $total_payment_history  = $payment_histories->sum('amount');

        $qbo_discount_items = $this->getQBDiscount();
        if(method_exists($qbo_discount_items, 'getData')) {
            $qbo_discount_items = [];
        }

        $qbo_items = $this->getQBItems();
        if(method_exists($qbo_items, 'getData')) {
            $qbo_items = [];
        }


        $tuition_list = [
            'enrollment_id'                 => $_enrollment->id,
            'enrollment'                    => $_enrollment,
            'commitment_payment'            => $_enrollment->commitmentPayment,
            'remaining_balance'             => $_enrollment->remaining_balance,
            'student'                       => $student,
            'tuition'                       => $tuition,
            'selected_other_programs'       => $selected_other_programs,
            'selected_other_services'       => $selected_other_services,
            'additional_fees'               => $additional_fees,
            'discrepancies'                 => $discrepancies,
            'total_selected_other_program'  => $total_selected_other_program,
            'total_selected_other_service'  => $total_selected_other_service,
            'total_additional_fee'          => $total_additional_fee,
            'total_discrepancy'             => $total_discrepancy,
            'other_program_lists'           => $other_program_lists,
            'other_service_lists'           => $other_service_lists,
            'special_discount_lists'        => $special_discounts_lists,
            'total_special_discount'        => $total_special_discount,
            'payment_histories'             => $payment_histories,
            'total_payment_history'         => $total_payment_history,
            'qbo_discount_items'            => $qbo_discount_items,
            'qbo_items'                     => $qbo_items,
        ];

        return response()->json($tuition_list);   
    }

    public function getPaymentMethodList()
    {
        $payment_methods = PaymentMethod::all();
        return response()->json($payment_methods);
    } 

    public function receiptLayouts ()
    {
        return response()->json([
            "header"     => view('paymentHistory.receipt.partials.header')->render(),
            "style"      => view('paymentHistory.receipt.partials.style')->render(),
        ]);

    }

    public function getQBDiscount() 
    {
        $qbo =  new QuickBooksOnline;
        $qbo->initialize();
        if($qbo->dataService() === null)
        {
            $status  = "ERROR";
            $message = "Unauthorized QuickBooks";

            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
        }

        $name = "Mandatory Fee " .  request()->school_year_id;
        // dd( $name);
      
        $discounts = $qbo->dataService->Query("SELECT * FROM Item WHERE Name LIKE '%discount%' MAXRESULTS 1000");
        $discounts = $discounts == null ? [] : collect($discounts);

        $error = $qbo->dataService->getLastError();
        if ($error) {
            $status  = "ERROR";
            $message = $error->getResponseBody();
            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));

        }

        if(count($discounts) > 1) {
            return $discounts->map(function ($item) { return ['Id' => $item->Id, 'Name' => $item->Name]; });
        }
        return $discounts;
    }

    public function getQBItems() 
    {
        $qbo =  new QuickBooksOnline;
        $qbo->initialize();
        if($qbo->dataService() === null)
        {
            $status  = "ERROR";
            $message = "Unauthorized QuickBooks";

            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
        }

        $name = "Mandatory Fee " .  request()->school_year_id;
        // dd( $name);
      
        $discounts = $qbo->dataService->Query("SELECT * FROM Item MAXRESULTS 1000");
        $discounts = $discounts == null ? [] : collect($discounts);

        $error = $qbo->dataService->getLastError();
        if ($error) {
            $status  = "ERROR";
            $message = $error->getResponseBody();
            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));

        }
        return $discounts->map(function ($item) { return ['Id' => $item->Id, 'Name' => $item->Name]; });
    }

    public function viewGrades ()
    {

    }
    public function studentInsurance(){

        return view('studentPortal.student_pa');
    }

}
