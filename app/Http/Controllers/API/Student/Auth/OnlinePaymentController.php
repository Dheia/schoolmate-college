<?php

namespace App\Http\Controllers\API\Student\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Enrollment;
use App\Models\OnlinePayment;
use App\Models\PaymentMethod;

class OnlinePaymentController extends Controller
{
    public function submitPayment (Request $request)
    {
        $response = [];
        $studentnumber = request()->user()->studentnumber;
        $validator = \Validator::make(request()->all(), [
            'school_year_id' => 'required|numeric|exists:school_years,id',
            'amount'         => 'required|numeric',
            'pay_id'         => 'required',
            'description'    => 'required',
            'email'          => 'required|email',
        ]);

        if($validator->fails()) {
            $response = ['error' => true, 'message' => 'Error or Missing Required Parameters', 'data' => $validator->errors()];
            return response()->json($response);
        }

        $enrollment = Enrollment::where('studentnumber', $studentnumber)
                                ->where('school_year_id', $request->school_year_id)
                                ->with('tuition')->latest()->first();

        if(!$enrollment) {
            $response = ['error' => true, 'message' => 'No Enrollment Found', 'data' => null];
            return response()->json($response);
        }

        $fee = ( (float)$request->amount * ($this->getFee()/100) ) + $this->getFixedAmount();

        // SAVE ONLINE-PAYMENT FORM 
        $onlinePayment                  = new OnlinePayment;
        $onlinePayment->studentnumber   = $studentnumber;
        $onlinePayment->amount          = $request->amount;
        $onlinePayment->fee             = $fee;
        $onlinePayment->description     = $request->description;
        $onlinePayment->email           = $request->email;
        $onlinePayment->pay_id          = $request->pay_id;
        $onlinePayment->status          = 'PENDING';
        $onlinePayment->ip_address      = request()->ip();
        $onlinePayment->payment_channel = 'mobile';
        $onlinePayment->payment_gateway = 'paypal';
        
        if($onlinePayment->save()) {
            $response = ['error' => false, 'message' => 'Successfully Created', 'data' => $onlinePayment];
        } else {
            $response = ['error' => true, 'message' => 'Error Saving, Something Went Wrong...', 'data' => null];
        }
        return response()->json($response);
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
}