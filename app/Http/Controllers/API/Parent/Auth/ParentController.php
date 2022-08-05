<?php

namespace App\Http\Controllers\API\Parent\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// MODELS
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Tuition;
use App\Models\SchoolYear;
use App\Models\OtherProgram;
use App\Models\OtherService;
use App\Models\Rfid;
use App\Models\CommitmentPayment;
use App\Models\TurnstileLog;
use App\SelectedOtherProgram;
use App\SelectedOtherService;
use App\Models\SpecialDiscount;
use App\Models\PaymentMethod;
use App\PaymentHistory;

use Carbon\Carbon;

class ParentController extends Controller
{
    public function profile ()
    {
    	$user = request()->user();
    	return $user;
    }

    public function account ($studentnumber)
    {
    	// $studentnumber 	= request()->user()->studentnumber;
        $sy_active = SchoolYear::active()->first();

        if($sy_active == null) {
            return response()->json(['message' => 'No School Year Active.'], 401);
        }

    	$_enrollment = Enrollment::where([
                                        'studentnumber' => $studentnumber,
                                        'school_year_id' => $sy_active->id
                                    ])->first();
 
        if($_enrollment == null) {
            return "Student Number " . $studentnumber . " Not Found";
        }
        
        $tuition = Tuition::where('id', $_enrollment->tuition_id)
                            ->where('schoolyear_id', $_enrollment->school_year_id)
                            ->where('grade_level_id', $_enrollment->level_id)
                            ->with('school_year')
                            ->with('year_management')
                            ->first();

        $selected_other_programs = SelectedOtherProgram::where('student_no', $studentnumber)
                                                        ->where('commitment_payment_id', $_enrollment->commitment_payment_id)
                                                        ->where('school_year_id', $_enrollment->school_year_id)
                                                        ->where('grade_level_id', $_enrollment->level_id)
                                                        ->where('tuition_id', $_enrollment->tuition_id)
                                                        ->with('user')
                                                        ->with('otherProgram')
                                                        ->get();

        $total_selected_other_program = 0;
        foreach ($selected_other_programs as $selected_other_program) {
            $total_selected_other_program += $selected_other_program->otherProgram->amount;
        }

        $selected_other_services = SelectedOtherService::where('student_no', $studentnumber)
                                                        ->where('commitment_payment_id', $_enrollment->commitment_payment_id)
                                                        ->where('school_year_id', $_enrollment->school_year_id)
                                                        ->where('grade_level_id', $_enrollment->level_id)
                                                        ->where('tuition_id', $_enrollment->tuition_id)
                                                        ->with('user')
                                                        ->with('otherServices')
                                                        ->get();

        $total_selected_other_service = 0;
        foreach ($selected_other_services as $selected_other_service) {
            $total_selected_other_service += $selected_other_service->otherService->amount;
        }

        $other_program_lists = OtherProgram::where('qbo_id', '!=', null)->get();
        $other_service_lists = OtherService::where('qbo_id', '!=', null)->get();


        $special_discounts_lists = SpecialDiscount::where('student_no', $studentnumber)
                                                    ->where('commitment_payment_id', $_enrollment->commitment_payment_id)
                                                    ->where('school_year_id', $_enrollment->school_year_id)
                                                    ->where('grade_level_id', $_enrollment->level_id)
                                                    ->where('tuition_id', $_enrollment->tuition_id)
                                                    ->with('user')
                                                    ->get();
        $total_special_discount = 0;
        foreach ($special_discounts_lists as $special_discounts_list) {
            $total_special_discount += $special_discounts_list->amount;
        }

        $payment_histories =  PaymentHistory::where('student_no', $studentnumber)
                                            ->where('commitment_payment_id', $_enrollment->commitment_payment_id)
                                            ->where('school_year_id', $_enrollment->school_year_id)
                                            ->where('grade_level_id', $_enrollment->level_id)
                                            ->where('tuition_id', $_enrollment->tuition_id)
                                            ->with('user')
                                            ->with('paymentMethod')
                                            ->get();

        $total_payment_history = 0;
        foreach ($payment_histories as $payment) {
            $total_payment_history += $payment->amount;
        }


        $tuition_list = [
            'enrollment_id'                 => $_enrollment->id,
            'enrollment_type'               => $_enrollment->enrollment_type,
            'commitment_payment'            => CommitmentPayment::where('id', $_enrollment->commitment_payment_id)->first(['id', 'name', 'additional_fee']),
            'tuition'                       => $tuition,
            'selected_other_programs'       => $selected_other_programs,
            'selected_other_services'       => $selected_other_services,
            'total_selected_other_program'  => $total_selected_other_program,
            'total_selected_other_service'  => $total_selected_other_service,
            'other_program_lists'           => $other_program_lists,
            'other_service_lists'           => $other_service_lists,
            'special_discount_lists'        => $special_discounts_lists,
            'total_special_discount'        => $total_special_discount,
            'payment_histories'             => $payment_histories,
            'total_payment_history'         => $total_payment_history
        ];

        return response()->json($tuition_list);   
    }

    public function attendanceLogs ()
    {
        $attendanceLogs = Rfid::where('studentnumber', request()->user()->studentnumber)->with('turnstilelogs')->first();
        return $attendanceLogs !== null ? $attendanceLogs->turnstilelogs : null; 
    }

    public function attendanceLogsRange($start_date, $end_date)
    {
        $rfid = Rfid::where('studentnumber', request()->user()->studentnumber)->first();

        if($rfid !== null)
        {
            $logs = TurnstileLog::where('rfid', $rfid->rfid)->whereDate('created_at', '>=', Carbon::parse($start_date))->whereDate('created_at', '<=', Carbon::parse($end_date))->paginate(10);
            return response()->json($logs);
        }

        return response()->json(null);
    }


    public function searchStudent ($searchTerm) 
    {
        $students = Student::leftJoin('rfids', 'rfids.studentnumber', '=', 'students.studentnumber')
                            ->join('enrollments', function ($join) {
                                $join->on('students.studentnumber', 'enrollments.studentnumber')
                                    ->where('enrollments.school_year_id', SchoolYear::active()->first()->id);
                            })
                            ->with(['level', 'schoolYear', 'track', 'department'])
                            ->where('students.studentnumber', '!=', null)
                            ->where('students.studentnumber', 'like', '%' . $searchTerm . '%')
                            ->orWhere('students.firstname', 'like', '%' . $searchTerm . '%')                           
                            ->orWhere('students.middlename', 'like', '%' . $searchTerm . '%')
                            ->orWhere('students.lastname', 'like', '%' . $searchTerm . '%')
                            ->orWhere('rfid', 'like', '%' . $searchTerm . '%')
                            ->select('students.*', 'rfids.*')
                            ->paginate(10);

        return response()->json($students);
    }

    public function getEnrollmentsList ($studentnumber) 
    {
        $enrollments = Enrollment::where('studentnumber', $studentnumber)
                                // ->with('commitmentPayment:id,name')
                                // ->with('curriculum:id,curriculum_name,description')
                                // ->with('tuition:id,form_name,tuition_fees,miscellaneous,activities_fee,other_fees,payment_scheme,active')
                                // ->with('schoolYear:id,schoolYear')
                                // ->with('department:id,name')
                                // ->with('level:id,year')
                                // ->with('track:id,code')
                                ->get();
        return response()->json($enrollments);
    }

    public function getEnrollment ($enrollment_id)
    {
        $_enrollment = Enrollment::where('id',$enrollment_id)
                                ->with(['tuition' => function ($q) {
                                    $q->with('school_year');
                                    $q->with('year_management');
                                }])
                                ->with('commitmentPayment')
                                // ->with('student:id,studentnumber,schoolyear,level_id,firstname,lastname,middlename,gender')
                                ->first(); 

        if($_enrollment == null) {
            return response()->json(['message' => 'No Enrollments Found']);
        }
        
        // $student = $_enrollment->student;
        $tuition = $_enrollment->tuition;
        $payment_method = PaymentMethod::select('name', 'id')->get();

        $selected_other_programs = SelectedOtherProgram::where('enrollment_id', $enrollment_id)
                                                        ->with('user')
                                                        ->with('otherProgram')
                                                        ->get();
                                                        // dd($selected_other_programs->sum('otherProgram->amount
                                                        //     '));
        $total_selected_other_program = $selected_other_programs->sum('otherProgram.amount');
        // foreach ($selected_other_programs as $selected_other_program) {
        //     $total_selected_other_program += $selected_other_program->otherProgram->amount;
        // }

        $selected_other_services = SelectedOtherService::where('enrollment_id', $enrollment_id)
                                                        ->with('user')
                                                        ->with('otherService')
                                                        ->get();

        $total_selected_other_service = $selected_other_services->sum('otherService.amount');

        $other_program_lists     = OtherProgram::where('qbo_id', '!=', null)->where('school_year_id', $_enrollment->school_year_id)->get();
        $other_service_lists     = OtherService::where('qbo_id', '!=', null)->where('school_year_id', $_enrollment->school_year_id)->get();
        $special_discounts_lists = SpecialDiscount::where('enrollment_id', $enrollment_id)->with('user')->get();
        $payment_histories       = PaymentHistory::where('enrollment_id', $enrollment_id)->with('user')->with('paymentMethod')->get();

        $total_special_discount = $special_discounts_lists->sum('amount');

        $total_payment_history = $payment_histories->sum('amount');

        $tuition_list = [
            'enrollment_id'                 => $_enrollment->id,
            'enrollment_type'               => $_enrollment->enrollment_type,
            'commitment_payment'            => $_enrollment->commitmentPayment,
            'remaining_balance'             => $_enrollment->remaining_balance,
            // 'student'                       => $student,
            'tuition'                       => $tuition,
            'selected_other_programs'       => $selected_other_programs,
            'selected_other_services'       => $selected_other_services,
            'total_selected_other_program'  => $total_selected_other_program,
            'total_selected_other_service'  => $total_selected_other_service,
            'other_program_lists'           => $other_program_lists,
            'other_service_lists'           => $other_service_lists,
            'special_discount_lists'        => $special_discounts_lists,
            'total_special_discount'        => $total_special_discount,
            'payment_histories'             => $payment_histories,
            'total_payment_history'         => $total_payment_history,
            'payment_method'                => $payment_method
        ];

        return response()->json($tuition_list);   
    }


    // PAYMENT PROCESS
    public function savePayment (Request $request)
    {
        $enrollment = Enrollment::where('id', $request->enrollment_id)->first();

        // CHECK IF STUDENT IS ALREADY HAS INVOICE NO.
        if($enrollment == null)
        {
            return response()->json(['message' => 'Error: This Student Is Not Enrolled', 'status' => 'ERROR'], 404);
        }

        $todayDate = Carbon::today();
        $data = [];

        // IF HAS OTHER PROGRAMS DATA
        if(count($request->other_programs) > 0)
        {
            // dd($request);
            // $other_programs = [];
            $other_programs = collect($request->other_programs)->map(function ($value, $key) use ($request, $todayDate) {
                return collect($value)
                        ->put('user_id', request()->user()->id)
                        ->put('enrollment_id', $request->enrollment_id)
                        ->put('payment_method_id', $request->payment_method_id)
                        ->put('payment_historable_type', "App\SelectedOtherProgram")
                        ->put('created_at', $todayDate)
                        ->put('updated_at', $todayDate)->toArray();
            });
            // $other_programs['user'] = 1;
            $data[] = $other_programs;
        }

        // IF HAS OTHER SERVICES DATA
        if(count($request->other_services) > 0)
        {
            // $other_services = [];
            $other_services = collect($request->other_services)->map(function ($value, $key) use ($request, $todayDate) {
                return collect($value)
                        ->put('user_id', request()->user()->id)
                        ->put('enrollment_id', $request->enrollment_id)
                        ->put('payment_method_id', $request->payment_method_id)
                        ->put('payment_historable_type', "App\SelectedOtherService")
                        ->put('created_at', $todayDate)
                        ->put('updated_at', $todayDate)->toArray();
            });
            $data[] = $other_services;
        }
        $data = collect($data)->collapse()->toArray();

        if(request()->has('enrollment_fee_amount_paid') && request()->enrollment_fee_amount_paid > 0) {

            $data[] = [
                "payment_historable_id" => null,
                "amount" => request()->enrollment_fee_amount_paid,
                "user_id" => request()->user()->id,
                "enrollment_id" => $request->enrollment_id,
                "payment_method_id" => $request->payment_method_id,
                "payment_historable_type" => null,
                "created_at" => $todayDate,
                "updated_at" => $todayDate
            ];

        }

        if(count($data) < 1) {
            return response()->json(['message' => 'No Payment Found', 'status' => 'ERROR'], 204);
        }

        $save = PaymentHistory::insert($data);

        if($save) {
            $lastPayment = PaymentHistory::where('enrollment_id', $request->enrollment_id)->latest()->first();
            return response()->json(['message' => 'Payment Successfully Made', 'data' => $lastPayment,  'status' => 'OK'], 200);
        }
        
        return response()->json(['message' => 'Payment Error', 'status' => 'ERROR'], 500);
    }


}
