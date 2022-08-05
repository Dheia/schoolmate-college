<?php

namespace App\Http\Controllers\API\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\AdditionalFee;
use App\SelectedOtherFee;
use App\SelectedPaymentType;
use App\SelectedOtherProgram;
use App\SelectedOtherService;
use App\Models\Discrepancy;
use App\Models\OtherService;

use App\Http\Controllers\QuickBooks\QuickBooksOnline;

use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;
use QuickBooksOnline\API\Facades\CreditMemo;
use QuickBooksOnline\API\Data\IPPPaymentLineDetail;
use QuickBooksOnline\API\Data\IPPLine;
use QuickBooksOnline\API\Data\IPPSalesItemLineDetail;
use QuickBooksOnline\API\Data\IPPDiscountLineDetail;
use QuickBooksOnline\API\Data\IPPDiscountOverride;

// Models
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\PaymentHistory;
use App\Models\SpecialDiscount;
use App\Models\OtherProgram;

class StudentV2Controller extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STUDENT PROFILE
    |--------------------------------------------------------------------------
    */
    public function profile ()
    {
    	$studentnumber 	= request()->user()->studentnumber;
    	$student 		= Student::where('studentnumber', $studentnumber)->first();
        return $student;
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT CSRF
    |--------------------------------------------------------------------------
    */
    public function getCSRFToken(Request $request)
    {
        $token = csrf_field();
        dd($token);
    	dd(csrf_token());
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT ENROLLMENT LIST
    |--------------------------------------------------------------------------
    */
    public function enrollmentList()
    {
        $studentnumber 	=   request()->user()->studentnumber;
    	$student        =   request()->user()->student;

        $enrollments    =   Enrollment::where('studentnumber', $studentnumber)
                                ->where('is_applicant', 0)
                                ->orderBy('created_at', 'ASC')
                                ->get();

        return response()->json(['enrollments' => $enrollments]);
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT ENROLLMENT INFO
    |--------------------------------------------------------------------------
    */
    public function enrollment($enrollment_id)
    {
        $studentnumber 	=   request()->user()->studentnumber;
    	$student        =   request()->user()->student;

        $enrollment     =   Enrollment::where('id', $enrollment_id)
                                ->where('studentnumber', $studentnumber)
                                ->first();

        if(! $enrollment) {
            $response   = [
                'status'    => 'error',
                'message'   => 'Enrollment Not Found.'
            ];
            return response()->json($response, 400);
        }

        $response['status']  = 'success';
        $response['message'] = 'Enrollment has been fetched successfully.';
        $response['data']    = $enrollment;

        return response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT ENROLLMENT TUITION
    |--------------------------------------------------------------------------
    */
    public function enrollmentTuition($enrollment_id)
    {
        $studentnumber 	=   request()->user()->studentnumber;
    	$student        =   request()->user()->student;

        $enrollment     =   Enrollment::where('id', $enrollment_id)
                                ->where('studentnumber', $studentnumber)
                                ->first();

        if(! $enrollment) {
            $response   = [
                'status'    => 'error',
                'message'   => 'Enrollment Not Found.'
            ];
            return response()->json($response, 400);
        }

        $tuition = $enrollment->tuition;

        $selected_other_programs      = SelectedOtherProgram::where('enrollment_id', $enrollment_id)->get();
        $total_selected_other_program = $selected_other_programs->sum('program_amount');

        $selected_other_services      = SelectedOtherService::where('enrollment_id', $enrollment_id)->get();
        $total_selected_other_service = $selected_other_services->sum('service_amount');

        $additional_fees        = AdditionalFee::where('enrollment_id', $enrollment_id)->get();
        $total_additional_fee   = $additional_fees->sum('amount');
        
        $discrepancies          = Discrepancy::where('enrollment_id', $enrollment_id)->get();
        $total_discrepancy      = $discrepancies->sum('amount');

        // $other_program_lists     = OtherProgram::where('qbo_map', '!=', null)->where('school_year_id', $enrollment->school_year_id)->get();
        // $other_service_lists     = OtherService::where('qbo_map', '!=', null)->where('school_year_id', $enrollment->school_year_id)->get();
        $special_discounts_lists = SpecialDiscount::where('enrollment_id', $enrollment_id)->get();
        $payment_histories       = PaymentHistory::where('enrollment_id', $enrollment_id)->get();

        $total_special_discount = $special_discounts_lists->sum('amount');
        $total_payment_history  = $payment_histories->sum('amount');

        // $qbo_discount_items = $this->getQBDiscount();
        // if(method_exists($qbo_discount_items, 'getData')) {
        //     $qbo_discount_items = [];
        // }

        // $qbo_items = $this->getQBItems();
        // if(method_exists($qbo_items, 'getData')) {
        //     $qbo_items = [];
        // }

        $tuition_list = [
            'tuition'                       => $tuition,
            'commitment_payment'            => $enrollment->commitmentPayment,
            'remaining_balance'             => $enrollment->remaining_balance,
            'selected_other_programs'       => $selected_other_programs,
            'selected_other_services'       => $selected_other_services,
            'additional_fees'               => $additional_fees,
            'discrepancies'                 => $discrepancies,
            'total_selected_other_program'  => $total_selected_other_program,
            'total_selected_other_service'  => $total_selected_other_service,
            'total_additional_fee'          => $total_additional_fee,
            'total_discrepancy'             => $total_discrepancy,
            // 'other_program_lists'           => $other_program_lists,
            // 'other_service_lists'           => $other_service_lists,
            // 'special_discount_lists'        => $special_discounts_lists,
            'total_special_discount'        => $total_special_discount,
            'total_payment_history'         => $total_payment_history,
            // 'qbo_discount_items'            => $qbo_discount_items,
            // 'qbo_items'                     => $qbo_items,
        ];

        $response['status']  = 'success';
        $response['message'] = 'Tuition data has been fetched successfully.';
        $response['data']    = $tuition_list;

        return response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | ENROLLMENT'S PAYMENT HISTORIES
    |--------------------------------------------------------------------------
    */
    public function paymentHistories($enrollment_id)
    {
        $studentnumber 	=   request()->user()->studentnumber;
    	$student        =   request()->user()->student;

        $enrollment     =   Enrollment::where('id', $enrollment_id)
                                ->where('studentnumber', $studentnumber)
                                ->first();

        if(! $enrollment) {
            $response   = [
                'status'    => 'error',
                'message'   => 'Enrollment Not Found.'
            ];
            return response()->json($response, 400);
        }

        $payment_histories = PaymentHistory::where('enrollment_id', $enrollment->id)->get();
        $total_payment     = $payment_histories->sum('amount');

        $data = [
            'payment_histories' => $payment_histories,
            'total_tuition'     => $enrollment->total_tuition,
            'total_discounts'   => $enrollment->total_discounts_discrepancies,
            'total_payment'     => $total_payment,
            'remaining_balance' => $enrollment->remaining_balance,
        ];

        $response   = [
            'status'    => 'success',
            'message'   => 'Payment Histories has been fetch successfully',
            'data'      => $data
        ];
        return response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | QBO DISCOUNT
    |--------------------------------------------------------------------------
    */
    public function getQBDiscount() 
    {
        $qbo =  new QuickBooksOnline;
        $qbo->initialize();
        if($qbo->dataService() === null)
        {
            return [];
        }

        $name = "Mandatory Fee " .  request()->school_year_id;
      
        $discounts = $qbo->dataService->Query("SELECT * FROM Item WHERE Name LIKE '%discount%' MAXRESULTS 1000");
        $discounts = $discounts == null ? [] : collect($discounts);

        $error = $qbo->dataService->getLastError();
        if ($error) {
            return [];
        }

        if(count($discounts) > 1) {
            return $discounts->map(function ($item) { return ['Id' => $item->Id, 'Name' => $item->Name]; });
        }
        return $discounts;
    }

    /*
    |--------------------------------------------------------------------------
    | QBO DISCOUNT
    |--------------------------------------------------------------------------
    */
    public function getQBItems() 
    {
        $qbo =  new QuickBooksOnline;
        $qbo->initialize();
        if($qbo->dataService() === null)
        {
            return [];
        }

        $name = "Mandatory Fee " .  request()->school_year_id;
      
        $discounts = $qbo->dataService->Query("SELECT * FROM Item MAXRESULTS 1000");
        $discounts = $discounts == null ? [] : collect($discounts);

        $error = $qbo->dataService->getLastError();
        if ($error) {
            return [];
        }

        return $discounts->map(function ($item) { return ['Id' => $item->Id, 'Name' => $item->Name]; });
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT NOTIFICATIONS
    |--------------------------------------------------------------------------
    */
    public function notifications()
    {
        $studentnumber 	= request()->user()->studentnumber;
    	$student        = request()->user()->student;

        $user           = request()->user();
        $notifications  = $user->notifications()->paginate(10);

        return response()->json($notifications);
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT UNREAD NOTIFICATIONS
    |--------------------------------------------------------------------------
    */
    public function unreadNotifications()
    {
        $studentnumber 	= request()->user()->studentnumber;
    	$student        = request()->user()->student;

        $user           = request()->user();
        $notifications  = $user->unreadNotifications()->paginate(10);

        return response()->json($notifications);
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT MARKED NOTIFICATION AS READ
    |--------------------------------------------------------------------------
    */
    public function readNotification($notification_id)
    {
        $studentnumber 	= request()->user()->studentnumber;
    	$student        = request()->user()->student;

        $user           = request()->user();

        /* Mark User Notification As Read */
        if($notification_id) {

            $notification = $user->notifications()->where('id', $notification_id)->first();

            if($notification) {
                $notification->read_at = now();
                $notification->save();
            }
        }

        return response()->json($notification);
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT MARKED NOTIFICATION AS UNREAD
    |--------------------------------------------------------------------------
    */
    public function unreadNotification($notification_id)
    {
        $studentnumber 	= request()->user()->studentnumber;
    	$student        = request()->user()->student;

        $user           = request()->user();

        /* Mark User Notification As Read */
        if($notification_id) {

            $notification = $user->notifications()->where('id', $notification_id)->first();

            if($notification) {
                $notification->read_at = null;
                $notification->save();
            }
        }

        return response()->json($notification);
    }
}
