<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\PaynamicV2;

// Models
use App\Models\Student;
use App\Models\ParentStudent;
use App\Models\Enrollment;

use App\Models\PaynamicsPayment;
use App\Models\PaymentMethod;
use App\Models\PaymentMethodCategory;

class OnlinePaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ENROLLMENT ONLINE PAYMENT
    |--------------------------------------------------------------------------
    */
    public function enrollmentPayment($enrollment_id)
    {
        $parent         =   auth()->user()->parent;
        $enrollment     =   config('settings.viewstudentaccount') 
                                ? Enrollment::where('id', $enrollment_id)
                                    ->where('is_applicant', 0)
                                    ->first()
                                : null;

        abort_if(! $enrollment, 404, 'Enrollment not found.');

        $student        =   $enrollment->student;
        abort_if(! $student, 404, 'Student not found.');

        $parentStudent  =   ParentStudent::where('parent_user_id', $parent->id)->where('student_id', $student->id)->first();
        abort_if(! $parentStudent, 401);

        if(! $enrollment->invoice_no) {
            \Alert::warning('Enrollment Invoice is NOT YET set.')->flash();
            return redirect()->back();
        }

        $paymentCategories  =   PaymentMethodCategory::where('method', '!=', NULL)
                                    ->where('method', '!=', '')
                                    ->where('action', '!=', NULL)
                                    ->where('action', '!=', '')
                                    ->get();
        $paymentMethods     =   count($paymentCategories) > 0 
                                ?   PaymentMethod::orderBy('name', 'ASC')
                                        ->whereIn('payment_method_category_id', $paymentCategories->pluck('id'))
                                        ->where('code', '!=', null)
                                        ->active()
                                        ->get()
                                :   collect([]);

        $data = [
            'student'        => $student,
            'enrollment'     => $enrollment,
            'paymentMethods' => $paymentMethods,
            'paymentCategories'  => $paymentCategories,
        ];

        return view('parentPortal.enrollment_online_payment')->with($data);
    }

    /*
    |--------------------------------------------------------------------------
    | ENROLLMENT LIST OF ONLINE PAYMENT
    |--------------------------------------------------------------------------
    */
    public function enrollmentPaymentList($enrollment_id)
    {
        $parent         =   auth()->user()->parent;
        $enrollment     =   config('settings.viewstudentaccount') 
                                ? Enrollment::where('id', $enrollment_id)
                                    ->where('is_applicant', 0)
                                    ->first()
                                : null;

        abort_if(! $enrollment, 404, 'Enrollment not found.');

        $student        =   $enrollment->student;
        abort_if(! $student, 404, 'Student not found.');

        $parentStudent  =   ParentStudent::where('parent_user_id', $parent->id)->where('student_id', $student->id)->first();
        abort_if(! $parentStudent, 401);

        $data = [
            'student'        => $student,
            'enrollment'     => $enrollment,
            'paynamicsPayments' => $enrollment->paynamicsPayments
        ];

        return view('parentPortal.enrollment_online_payment_list')->with($data);
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW PAYMENT INFORMATION
    |--------------------------------------------------------------------------
    */
    public function showInformation($paynamics_payment_id)
    {
        $paynamicsPayment = PaynamicsPayment::where('id', $paynamics_payment_id)->first();

        if(! $paynamicsPayment) {
            \Alert::warning('Payment Not Found.')->flash();
            return redirect('student/online-payment');
        }

        $direct_otc_info = json_decode($paynamicsPayment->direct_otc_info);

        if(is_array($direct_otc_info)) {
            $payment_instructions = $direct_otc_info[0];

            $data = [
                'amount'         => $paynamicsPayment->amount + $paynamicsPayment->fee,
                'payment_method' => $paynamicsPayment->paymentMethod,
                'paynamics_payment'    => $paynamicsPayment,
                'payment_instructions' => $payment_instructions
            ];

            return view('parentPortal.payment_instructions', $data);
        }

        $url = $paynamicsPayment->direct_otc_info ? $paynamicsPayment->direct_otc_info : $paynamicsPayment->payment_action_info;

        if($url) {
            return redirect()->away($url);
        }

        return redirect()->back();
    }

    /*
    |--------------------------------------------------------------------------
    | SUBMIT PAYMENT
    |--------------------------------------------------------------------------
    */
    public function submitPayment(Request $request)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'school_year_id' => 'required|exists:school_years,id',
            'studentnumber' => 'required|exists:students,studentnumber',
            'amount' => 'required|numeric|min:1',
            'email' => 'required|email',
            'description' => 'required|string|max:225',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);
        ini_set('max_execution_time', 300); // 5 minutes
        
        if( (env('PAYMENT_GATEWAY') !== null || env('PAYMENT_GATEWAY') !== "") && strtolower(env('PAYMENT_GATEWAY')) == "paynamics") {

            $paynamics      = new PaynamicV2();
            $payment_data   = $paynamics->initialize($request->input(), 'parent');

            if($payment_data['status'] != 'success') {
                if(! $payment_data['message'] ) {
                    \Alert::error('<h4>Payment Error</h4>Something went wrong, please reload the page.')->flash();
                    return redirect('parent/student-enrollments/' . $request->studentnumber);
                }
                \Alert::warning($payment_data['message'])->flash();
                return redirect('parent/student-enrollments/' . $request->studentnumber);
            }

            /*
            |--------------------------------------------------------------------------
            | SAVE PAYNAMICS PAYMENT
            |--------------------------------------------------------------------------
            */
            $create_payment_row = $paynamics->createPaymentRow($payment_data['data']);

            if($create_payment_row['status'] != 'success') {
                if(! $create_payment_row['message'] ) {
                    \Alert::error('<h4>Payment Error</h4>Something went wrong, please reload the page.')->flash();
                    return redirect('parent/student-enrollments/' . $request->studentnumber);
                }
                \Alert::warning($create_payment_row['message'])->flash();
                return redirect('parent/student-enrollments/' . $request->studentnumber);
            }

            $decoded_data = json_decode($create_payment_row['data']);

            /*
            |--------------------------------------------------------------------------
            | PAYMENT INSTRUCTION // Check If Direct OTC Info is Array
            |--------------------------------------------------------------------------
            */
            if(isset($decoded_data->direct_otc_info)) {
                if(is_array($decoded_data->direct_otc_info)) {
                    $payment_instructions = $decoded_data->direct_otc_info[0];

                    $data = [
                        'amount'         => $payment_data['amount'],
                        'payment_method' => $payment_data['payment_method'],
                        'paynamics_payment'    => $decoded_data,
                        'payment_instructions' => $payment_instructions
                    ];

                    return view('parentPortal.payment_instructions', $data);
                }
                \Alert::success('Payment has been processed')->flash();
                return redirect()->to($decoded_data->direct_otc_info);
            }

            if(isset($decoded_data->payment_action_info)) {
                if(is_array($decoded_data->payment_action_info)) {
                    $payment_instructions = $decoded_data->payment_action_info[0];

                    $data = [
                        'amount'         => $payment_data['amount'],
                        'payment_method' => $payment_data['payment_method'],
                        'payment_instructions' => $payment_instructions
                    ];

                    return view('parentPortal.payment_instructions', $data);
                }
            }
            
            \Alert::success('Payment has been processed')->flash();
            return redirect()->to($decoded_data->direct_otc_info ?? $decoded_data->payment_action_info);
        } else {
            \Alert::error('Payment Gateway NOT set')->flash();
            return redirect('parent/student-enrollments/' . $request->studentnumber);
        }
    }
}
