<?php

namespace App\Http\Controllers\API\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Validator;

use Carbon\Carbon;

// Controller
use App\Http\Controllers\PaynamicV2;

// Models
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\PaymentMethod;
use App\Models\PaynamicsPayment;
use App\Models\PaymentMethodCategory;

class OnlinePaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GET PAYMENT CATEGORIES
    |--------------------------------------------------------------------------
    */
    public function getPaymentCategories()
    {
        $paymentCategories  =   PaymentMethodCategory::with(['paymentMethods' => function ($query) {
                                        $query->active();
                                    }])
                                    ->where('method', '!=', NULL)
                                    ->where('method', '!=', '')
                                    ->where('action', '!=', NULL)
                                    ->where('action', '!=', '')
                                    ->get();

        return response()->json(['paymentCategories' => $paymentCategories]);
    }

    /*
    |--------------------------------------------------------------------------
    | GET PAYMENT METHOD
    |--------------------------------------------------------------------------
    */
    public function getPaymentMethod($id)
    {
        $paymentCategories  =   PaymentMethodCategory::with(['paymentMethods' => function ($query) {
                                        $query->active();
                                    }])
                                    ->where('method', '!=', NULL)
                                    ->where('method', '!=', '')
                                    ->where('action', '!=', NULL)
                                    ->where('action', '!=', '')
                                    ->get();

        $paymentMethod      = PaymentMethod::where('id', $id)->whereIn('payment_method_category_id', $paymentCategories->pluck('id'))
                                ->active()
                                ->first();
        if(! $paymentMethod) {
            return response()->json('Payment Method Not Found.', 422);
        }
        return response()->json($paymentMethod);
    }

    /*
    |--------------------------------------------------------------------------
    | SUBMIT ONLINE PAYMENT (PAYNAMICS / SPLIT PAY)
    |--------------------------------------------------------------------------
    */
    public function submitPayment(Request $request)
    {
        $this->student = request()->user()->student;

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
            $response = [
                'message' => 'The given data was invalid.',
                'errors'  => $validator->errors(),
            ];
            return response($response, 422);
        }

        $enrollment =   config('settings.viewstudentaccount') 
                            ? Enrollment::where('studentnumber', $this->student->studentnumber)
                                ->where('id', $request->enrollment_id)
                                ->where('is_applicant', 0)
                                ->first()
                            : null;

        if(! $enrollment) {
            $response = [
                'message' => 'The given data was invalid.',
                'errors'  => ['enrollment_id' => 'The selected enrollment id is invalid.']
            ];
            return response($response, 422);
        }

        $paynamics      = new PaynamicV2();
        $payment_data   = $paynamics->initialize($request->input(), 'student');

        if($payment_data['status'] != 'success') {

            $response = [
                'status' => 'error',
                'data'   => null,
                'message' => 'Something went wrong, please reload the page.'
            ];

            if( $payment_data['message'] ) {
                $response['message'] = $payment_data['message'];
            }

            return response()->json($response, 422);
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE PAYNAMICS PAYMENT
        |--------------------------------------------------------------------------
        */
        $create_payment_row = $paynamics->createPaymentRow($payment_data['data']);

        if($create_payment_row['status'] != 'success') {

            $response = [
                'status' => 'error',
                'data'   => null,
                'message' => 'Something went wrong, please reload the page.'
            ];

            if( $payment_data['message'] ) {
                $response['message'] = $payment_data['message'];
            }

            return response()->json($response, 422);
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

                $response = [
                    'status'        => 'success',
                    'message'       => $decoded_data->response_message,
                    'amount'        => $payment_data['amount'],
                    'instruction'   => $payment_instructions,
                    'web_url'       => null
                ];
                return response($response, 201);
            }
            $response = [
                'status'        => 'success',
                'message'       => $decoded_data->response_message,
                'amount'        => $payment_data['amount'],
                'instruction'   => null,
                'web_url'       => $decoded_data->direct_otc_info
            ];
            return response($response, 201);
        }

        if(isset($decoded_data->payment_action_info)) {
            if(is_array($decoded_data->payment_action_info)) {
                $payment_instructions = $decoded_data->payment_action_info[0];

                $response = [
                    'status'        => 'success',
                    'message'       => $decoded_data->response_message,
                    'amount'        => $payment_data['amount'],
                    'instruction'   => $payment_instructions,
                    'web_url'       => null
                ];

                return response($response, 201);
            }
            $response = [
                'status'        => 'success',
                'message'       => $decoded_data->response_message,
                'amount'        => $payment_data['amount'],
                'instruction'   => null,
                'web_url'       => $decoded_data->payment_action_info
            ];
            return response($response, 201);
        }

        $response = [
            'status'        => 'success',
            'message'       => $decoded_data->response_message,
            'amount'        => $payment_data['amount'],
            'web_url'       => $decoded_data->direct_otc_info ?? $decoded_data->payment_action_info
        ];
        return response($response, 201);
        // dd($request);
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT INFORMATION
    |--------------------------------------------------------------------------
    */
    public function getInformation($request_id)
    {
        try {
            $paynamicsPayment = PaynamicsPayment::where('request_id', $request_id)
                                    ->where('response_code', 'GR033')
                                    ->first();
    
            if(! $paynamicsPayment) {
                return response()->json('Paynamics Payment Not Found.', 422);
            }
    
            if(Carbon::parse($paynamicsPayment->expiry_limit)->format('F d, Y h:i A') < Carbon::now()->format('F d, Y h:i A')) {
                return response()->json('Paynamics Payment Not Found.', 422);
            }
    
            $paynamicsPayment = json_decode($paynamicsPayment);
    
            /** 
             * PAYMENT INSTRUCTION // Check If Direct OTC Info is Array
             */
            if(isset($paynamicsPayment->direct_otc_info)) {
                if(is_array(json_decode($paynamicsPayment->direct_otc_info))) {
                    $payment_instructions = json_decode($paynamicsPayment->direct_otc_info)[0];
    
                    $response = [
                        'status'        => 'success',
                        'message'       => $paynamicsPayment->response_message,
                        'amount'        => $paynamicsPayment->amount + $paynamicsPayment->fee,
                        'instruction'   => $payment_instructions,
                        'web_url'       => null
                    ];
    
                    return response($response, 201);
                }
                $response = [
                    'status'        => 'success',
                    'message'       => $paynamicsPayment->response_message,
                    'amount'        => $paynamicsPayment->amount + $paynamicsPayment->fee,
                    'instruction'   => null,
                    'web_url'       => $paynamicsPayment->direct_otc_info
                ];
    
                return response($response, 201);
            }
    
            if(isset($paynamicsPayment->payment_action_info)) {
                if(is_array(json_decode($paynamicsPayment->payment_action_info))) {
                    $payment_instructions = json_decode($paynamicsPayment->payment_action_info)[0];
    
                    $response = [
                        'status'        => 'success',
                        'message'       => $paynamicsPayment->response_message,
                        'amount'        => $paynamicsPayment->amount + $paynamicsPayment->fee,
                        'instruction'   => $payment_instructions,
                        'web_url'       => null
                    ];
    
                    return response($response, 201);
                }
                $response = [
                    'status'        => 'success',
                    'message'       => $paynamicsPayment->response_message,
                    'amount'        => $paynamicsPayment->amount + $paynamicsPayment->fee,
                    'instruction'   => null,
                    'web_url'       => $paynamicsPayment->payment_action_info
                ];
    
                return response($response, 201);
            }
    
            $response = [
                'status'        => 'success',
                'message'       => $paynamicsPayment->response_message,
                'amount'        => $paynamicsPayment->amount + $paynamicsPayment->fee,
                'web_url'       => $paynamicsPayment->direct_otc_info ?? $paynamicsPayment->payment_action_info
            ];
            return response($response, 201);
        }
        catch (\Exception $e) {
            $response = [
                'status'        => 'error',
                'message'       => $e->getMessage()
            ];

            return response($response, 500);
        }
    }
}
