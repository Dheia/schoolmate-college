<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

// MODELS
use App\Models\OnlinePayment;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\PaymentMethod;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailableOnlinePaymentReceipt as MailReceipt;

class OnlinePaymentController extends Controller
{
    
    private $schoolYear;
    private $apiContext;

    public function __construct ()
    {
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

	public function index ()
	{
		$fee = $this->getFee();
		$fixedAmount = $this->getFixedAmount();
		$schoolYears = SchoolYear::orderBy('schoolYear', 'asc')->get();
		$paymentMethods = PaymentMethod::orderBy('name', 'ASC')->where('code', '!=', null)->get();

		$sy = [];
		$flag = false;
		foreach ($schoolYears as $key => $schoolYear) {
			if($schoolYear->isActive) {
				$sy[] = $schoolYear;
				if($key > 0) {
					$sy[] = $schoolYears[$key - 1];
					$flag = true;
				}
			}

			if($flag) {
				if($sy)
				$sy[] = $schoolYear;
			}
		}
		$schoolYears = collect($sy)->unique('id')->sortBy('schoolYear');
		return view('onlinePayment.newIndex', compact('fee', 'fixedAmount', 'schoolYears', 'paymentMethods'));
	}

	public function submitForm (Request $request)
	{

		$request->validate([
			'school_year_id' => 'required|exists:school_years,id',
			'studentnumber' => 'required|exists:students,studentnumber',
			'amount' => 'required|numeric|min:100',
			'email' => 'required|email',
			'description' => 'required|string|max:225',
			'payment_method_id' => 'required|exists:payment_methods,id',
	    ]);

		if( (env('PAYMENT_GATEWAY') !== null || env('PAYMENT_GATEWAY') !== "") && strtolower(env('PAYMENT_GATEWAY')) == "paynamics") {
			// dd($request->input());
			$paynamics = new Paynamic($request->input());
			// return $paynamics;
		}

		$student = Student::where('studentnumber', $request->studentnumber)->first();
		$schoolYear = SchoolYear::where('id', $request->school_year_id)->first();

		if($student == null) { return redirect()->back()->withInput(); }
		if($schoolYear == null) { return redirect()->back()->withInput(); }

		$enrollment = Enrollment::where('studentnumber', $request->studentnumber)
								->where('school_year_id', $request->school_year_id)
								->with('tuition')
								->latest()
								->first();

		if($enrollment == null) { abort(404, 'Enrolment Not Found'); }

		$payer = new Payer;
		$payer->setPaymentMethod('paypal');

		$amount = new Amount;


		$fee = ( (float)$request->amount * ($this->getFee()/100) ) + $this->getFixedAmount();

		$amount->setTotal((float)$request->amount + $fee);
		$amount->setCurrency('PHP');

		$transaction = new Transaction;
		$transaction->setAmount($amount)->setDescription($request->description);

		$redirectUrls = new RedirectUrls;
		$redirectUrls->setReturnUrl(env('APP_URL') . '/online-payment/execute-payment?success=true')
					->setCancelUrl(env('APP_URL') . '/online-payment/execute-payment?success=false');

		$payment = new Payment;
		$payment->setIntent('sale')->setPayer($payer)->setTransactions(array($transaction))->setRedirectUrls($redirectUrls);

		try {
			$payment->create($this->apiContext);


			// SAVE ONLINE-PAYMENT FORM 
			$onlinePayment 					= new OnlinePayment;
			$onlinePayment->studentnumber	= $request->studentnumber;
			$onlinePayment->amount 			= $request->amount;
			$onlinePayment->fee 			= $fee;
			$onlinePayment->description 	= $request->description;
			$onlinePayment->email 			= $request->email;
			$onlinePayment->pay_id 			= $payment->id;
			$onlinePayment->status 			= 'PENDING';
			$onlinePayment->ip_address 		= $request->ip();
			$onlinePayment->payment_channel = 'web';
			$onlinePayment->payment_gateway = 'paypal';
			$onlinePayment->save();

			return redirect()->to($payment->getApprovalLink());

		} catch (\Paypal\Exception\PayPalConnectionException $ex) {
			abort(500, $ex->getData());
		}
	}

	public function getTuition ($studentnumber, Request $request)
	{
		$student = Student::where('studentnumber', $studentnumber)->first();
		$schoolYear = SchoolYear::where('id', $request->school_year_id)->first();

		if(!$schoolYear) { return response()->json(['error' => true, 'message' => 'No School Year Found', 'data' => null]); }
		if(!$student) { return response()->json(['error' => true, 'message' => 'Student Not Found', 'data' => null]); }

		$enrollment = Enrollment::where('studentnumber', $studentnumber)
								->where('school_year_id', $request->school_year_id)
								->with('tuition')
								->latest()
								->get();
								
		if(!$enrollment) { return response()->json(['error' => true, 'message' => 'Student Is Not Enrolled Yet', 'data' => null]); }

		$data = [
			"full_name" => $student->full_name,
			"remaining_balance" =>  collect($enrollment)->sum('remaining_balance')
		];
		
		return response()->json(['error' => false, 'message' => '', 'data' => $data]);

	}

	public function executePayment (Request $request)
	{
		// If payment was cancel
		if($request->success == "false") {
	    	return view('onlinePayment.payment_cancel');
		}

		
	    $onlinePayment = OnlinePayment::where('pay_id', $request->paymentId)->with('student')->first();

	    if($onlinePayment) {
	    	if(strtoupper($onlinePayment->status) == "CREATED") {
	    		return view('onlinePayment.payment_success', compact('onlinePayment'));
	    	}
			try {
			    $payment 	= Payment::get($request->paymentId, $this->apiContext);
			    $execution 	= new PaymentExecution;
	        	$execution->setPayerId($request->PayerID);

			    $result 	= $payment->execute($execution, $this->apiContext);

			    if(strtoupper($result->state) == 'APPROVED') {
			    	
    				Mail::to($onlinePayment->email)->send(new MailReceipt($onlinePayment));
			    	OnlinePayment::where('pay_id', $result->id)->where('status', '!=', 'CREATED')->update(['status' => 'CREATED']);

			    	return view('onlinePayment.payment_success', compact('result', 'onlinePayment'));
			    }
			} catch (Exception $ex) {
				abort(500, $ex);
			}
	    } else {
	    	abort(400, 'Missing Required Parameters');
	    }

	}

    public function getFee ()
    {
		$paymentMethod = PaymentMethod::where('name', 'Paypal')->first();
		$percentage = 0;

		if($paymentMethod) {
			$percentage = (float)$paymentMethod->fee;
		}

		return $percentage;
    }

    public function getFixedAmount ()
    {
    	$paymentMethod = PaymentMethod::where('name', 'Paypal')->first();
    	return $paymentMethod ? $paymentMethod->fixed_amount : 0;
    }

    public function webhooks (Request $request)
    {
    	try {
	    	$sale = Sale::get($request->resource['id'], $this->apiContext);
	    	\Log::info('Sale ', $sale);
			OnlinePayment::where('pay_id', $sale->parent_payment)->update(['status' => $sale->state, 'json_response' => $request->json()]);    		
    	} catch (Exception $e) {
    		abort(500, $e);
    	}
    }
}
