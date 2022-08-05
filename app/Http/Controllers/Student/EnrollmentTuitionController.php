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
use App\Models\CommitmentPayment;
use App\Models\Fund;
use Auth;
use Carbon\Carbon;

use App\Models\SchoolYear;
use App\Models\SectionManagement;
use App\Models\StudentSectionAssignment;
use App\Models\Requirement;

use App\AdditionalFee;
use App\PaymentHistory;
use App\SelectedOtherFee;
use App\SelectedPaymentType;
use App\SelectedOtherProgram;
use App\SelectedOtherService;
use App\Models\Discrepancy;
use App\Models\OtherService;

use App\Http\Controllers\Student\EnrollmentController;

use App\Http\Controllers\QuickBooks\QuickBooksOnline;

use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;
use QuickBooksOnline\API\Facades\CreditMemo;
use QuickBooksOnline\API\Data\IPPPaymentLineDetail;
use QuickBooksOnline\API\Data\IPPLine;
use QuickBooksOnline\API\Data\IPPSalesItemLineDetail;
use QuickBooksOnline\API\Data\IPPDiscountLineDetail;
use QuickBooksOnline\API\Data\IPPDiscountOverride;

class EnrollmentTuitionController extends Controller
{

    private $student;
    private $user;
    private $student_classes;

    // public function __construct ()
    // {
    //     $this->user = auth()->user();
    //     $this->student = Student::where("studentnumber", $this->user->studentnumber)->first();
    // }

    public function enrollments ()
    {
        $title          =   "Enrollments";
        $student        =   auth()->user()->student;

        $active_sy      =   SchoolYear::active()->first();
        $enrollments    =   Enrollment::where('studentnumber', auth()->user()->studentnumber)
                                ->with(['studentSectionAssignment' => function ($query) {
                                    $query->where('students', 'like', '%' . auth()->user()->studentnumber . '%');
                                    $query->with(['section' => function ($q) {
                                        $q->with('level');
                                    }]);
                                }])
                                ->where('is_applicant', 0)
                                ->orderBy('created_at', 'ASC')
                                ->get();

        $applications   =   Enrollment::where('studentnumber', auth()->user()->studentnumber)
                                ->where('is_applicant', 1)
                                ->orderBy('created_at', 'ASC')
                                ->get();

        $enrollmentController = new EnrollmentController;
        $nextEnrollment       = $enrollmentController->getStudentNextEnrollment($student->id);

        $current_enrollment   = Enrollment::where('studentnumber', $student->studentnumber)
                                    ->where('school_year_id', $active_sy->id)
                                    ->where('term_type', '!=', 'Summer')
                                    ->latest()
                                    ->first();

        $other_program_lists  = [];
        $other_service_lists  = [];
        
        if($current_enrollment && config('settings.allow_program_and_service_enrollment')) {
            $selected_other_programs = SelectedOtherProgram::where('enrollment_id', $current_enrollment->id)->with('user')->with('otherProgram')->get();
            $selected_other_services = SelectedOtherService::where('enrollment_id', $current_enrollment->id)->with('user')->with('otherServices')->get();

            $other_program_lists  = OtherProgram::whereNotIn('id', $selected_other_programs->pluck('other_program_id'))
                                        ->where('school_year_id', $active_sy->id)
                                        ->where('qbo_map', '!=', null)
                                        ->get();
            $other_service_lists  = OtherService::whereNotIn('id', $selected_other_services->pluck('other_service_id'))
                                        ->where('school_year_id', $active_sy->id)
                                        ->where('qbo_map', '!=', null)
                                        ->get();
        }

        $data = [
            'student'            => $student,
            'current_enrollment' => $current_enrollment,
            'nextEnrollment'     => $nextEnrollment ? (object)$nextEnrollment : null,
            'commitmentPayment'  => CommitmentPayment::get(),
            'show_tuition'       => config('settings.viewstudentaccount'),
            'other_program_lists' => $other_program_lists,
            'other_service_lists' => $other_service_lists
        ];

        return view('student.enrolled_list', compact('enrollments', 'applications', 'title'))->with($data);
    }

    public function viewTuition ($enrollment_id)
    {
        $title       = "Tuitions";
        $studentnumber = auth()->user()->studentnumber;

        abort_if(! config('settings.viewstudentaccount'), 401);
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

}
