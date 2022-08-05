<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;

use App\Http\Controllers\PaynamicV2;

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

// MODELS
use App\Models\OnlinePayment;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\PaymentMethod;
use App\Models\PaynamicsPayment;
use App\Models\PaymentMethodCategory;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailablePaynamicsPaymentReceipt as MailReceipt;

use App\Http\Controllers\OnlinePaymentController as PaymentController;

class OnlinePaymentController extends Controller
{
    public $user;
    public $student;

    public function __construct()
    {
        $this->middleware(function ($request, $next) 
        {
            $this->user 	= Auth::user();
            $this->student 	= $this->user->student;

            return $next($request);
        });

        if( (env('PAYMENT_GATEWAY') !== null || env('PAYMENT_GATEWAY') !== "") && strtolower(env('PAYMENT_GATEWAY')) == "paynamics") {} 
    	else {

	    	$this->schoolYear = SchoolYear::active()->first();
	    	$status = strtolower(env('PAYPAL_STATUS')) === 'production' ? 'LIVE' : 'SANDBOX';

	    	// SET PAYPAL CREDENTIALS (Development or Production)
			$oauthTokenCredential 	= new OAuthTokenCredential(env('PAYPAL_' . $status . '_CLIENT_ID'), env('PAYPAL_' . $status . '_CLIENT_SECRET'));
			$this->apiContext 		= new ApiContext($oauthTokenCredential);

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

    public function index()
    {
        $this->student  =   $student    =   auth()->user()->student;
        $enrollments    =   config('settings.viewstudentaccount') 
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
                                    ->where('is_applicant', 0)
                                    ->orderBy('created_at', 'ASC')
                                    ->get()

                                : collect([]);

        $paymentController  = new PaymentController();
        $not_split_pay      = PaymentMethodCategory::where('name', 'Paynamics')->first();
        $paymentMethods     = PaymentMethod::orderBy('name', 'ASC')
                                ->where('payment_method_category_id', '!=', $not_split_pay ? $not_split_pay->id : 'null')
                                ->where('code', '!=', null)
                                ->active()
                                ->get();
        
        $data = [
            'student'        => $student,
            'enrollments'    => $enrollments,
            'fee'            => $paymentController->getFee(),
            'fixedAmount'    => $paymentController->getFixedAmount(),
            'paymentMethods' => $paymentMethods,
            'paynamics_payments' => PaynamicsPayment::where('studentnumber', $student->studentnumber)->where('response_code', 'GR033')->get()
        ];

    	// dd($data);
    	return view('studentPortal.online_payment_dashboard')->with($data);
    }

    /*
    |--------------------------------------------------------------------------
    | ENROLLMENT ONLINE PAYMENT
    |--------------------------------------------------------------------------
    */
    public function enrollmentPayment($enrollment_id)
    {
        $this->student  =   $student    =   auth()->user()->student;
        $enrollment     =   config('settings.viewstudentaccount') 
                                ? Enrollment::where('studentnumber', $this->student->studentnumber)
                                    ->where('is_applicant', 0)
                                    ->where('id', $enrollment_id)
                                    ->first()
                                : null;

        abort_if(! $enrollment, 404);

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
        return view('studentPortal.enrollment_online_payment')->with($data);
    }

    /*
    |--------------------------------------------------------------------------
    | ENROLLMENT LIST OF ONLINE PAYMENT
    |--------------------------------------------------------------------------
    */
    public function enrollmentPaymentList($enrollment_id)
    {
        $this->student  =   $student    =   auth()->user()->student;
        $enrollment     =   config('settings.viewstudentaccount') 
                                ? Enrollment::where('studentnumber', $this->student->studentnumber)
                                    ->where('is_applicant', 0)
                                    ->where('id', $enrollment_id)
                                    ->first()
                                : null;

        abort_if(! $enrollment, 404);

        $data = [
            'student'        => $student,
            'enrollment'     => $enrollment,
            'paynamicsPayments' => $enrollment->paynamicsPayments
        ];

        return view('studentPortal.enrollment_online_payment_list')->with($data);
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW PAYMENT INFORMATION
    |--------------------------------------------------------------------------
    */
    public function showInformation($id)
    {
        $this->student = $student = auth()->user()->student;
        $payment       = PaynamicsPayment::where('studentnumber', $student->studentnumber)
                            ->where('id', $id)
                            ->first();
        if(! $payment) {
            \Alert::warning('Payment Not Found.')->flash();
            return redirect('student/online-payment');
        }
        $direct_otc_info = json_decode($payment->direct_otc_info);

        if(is_array($direct_otc_info)) {
            $payment_instructions = $direct_otc_info[0];

            $data = [
                'amount'         => $payment->amount + $payment->fee,
                'payment_method' => $payment->paymentMethod,
                'paynamics_payment'    => $payment,
                'payment_instructions' => $payment_instructions
            ];

            return view('studentPortal.payment_instructions', $data);
        }

        $url = $payment->direct_otc_info ? $payment->direct_otc_info : $payment->payment_action_info;

        if($url) {
            return redirect()->away($url);
        }

        return redirect('student/online-payment');
    }

    public function getStudentBalance(Request $request)
    {
    	$school_year_id = $request->school_year_id;
    	dd($this->student);
    }

    /*
    |--------------------------------------------------------------------------
    | GET PAYMENT METHOD
    |--------------------------------------------------------------------------
    */
    public function getPaymentMethod($id)
    {
        $paymentMethod = PaymentMethod::active()->findOrFail($id);
        return $paymentMethod;
    }

    /*
    |--------------------------------------------------------------------------
    | SUBMIT PAYMENT
    |--------------------------------------------------------------------------
    */
    public function submitForm (Request $request)
    {
        $this->student = $student = auth()->user()->student;

        // Validate Input Data
        $validator  =   Validator::make($request->all(), [
            'enrollment_id'     => 'required|exists:enrollments,id,deleted_at,NULL',
            'school_year_id'    => 'required|exists:school_years,id,deleted_at,NULL',
            'studentnumber'     => 'required|exists:students,studentnumber,deleted_at,NULL',
            'amount'            => 'required|numeric|min:1',
            'email'             => 'required|email',
            'description'       => 'nullable|string|max:225',
            'payment_method_id' => 'required|exists:payment_methods,id,active,1,deleted_at,NULL',
        ]);
        // Error Inputs
        if ($validator->fails()) {
            return redirect(url()->previous())
                ->withErrors($validator)
                ->withInput();
        }
        $enrollment =   config('settings.viewstudentaccount') 
                            ? Enrollment::where('studentnumber', $this->student->studentnumber)
                                ->where('id', $request->enrollment_id)
                                ->where('is_applicant', 0)
                                ->first()
                            : null;

        abort_if(! $enrollment, 404);

        ini_set('max_execution_time', 300); // 5 minutes
        if( (env('PAYMENT_GATEWAY') !== null || env('PAYMENT_GATEWAY') !== "") && strtolower(env('PAYMENT_GATEWAY')) == "paynamics") {
            // dd($request->input());
            
            $paynamics      = new PaynamicV2();
            $payment_data   = $paynamics->initialize($request->input(), 'student');

            if($payment_data['status'] != 'success') {
                if(! $payment_data['message'] ) {
                    \Alert::error('<h4>Payment Error</h4>Something went wrong, please reload the page.')->flash();
                    return redirect('student/my-account');
                }
                \Alert::warning($payment_data['message'])->flash();
                return redirect('student/my-account');
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
                    return redirect('student/my-account');
                }
                \Alert::warning($create_payment_row['message'])->flash();
                return redirect('student/my-account');
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

                    return view('studentPortal.payment_instructions', $data);
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
                        'paynamics_payment'    => $decoded_data,
                        'payment_instructions' => $payment_instructions
                    ];

                    return view('studentPortal.payment_instructions', $data);
                }
            }
            
            \Alert::success('Payment has been processed')->flash();
            return redirect()->to($decoded_data->direct_otc_info ?? $decoded_data->payment_action_info);

            // return $paynamics;
        } else {
            \Alert::error('Payment Gateway NOT set')->flash();
            return redirect('student/online-payment');
        }
    }

}
