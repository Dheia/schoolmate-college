<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PaynamicsPaymentRequest as StoreRequest;
use App\Http\Requests\PaynamicsPaymentRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailablePaynamicsPaymentReceipt as MailReceipt;

use Carbon\Carbon;

use App\Models\PaymentHistory;
use App\Models\PaynamicsPayment;

use App\SelectedOtherProgram;
use App\SelectedOtherService;
use App\SelectedOtherFee;
use App\AdditionalFee;

/**
 * Class PaynamicsPaymentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PaynamicsPaymentCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PaynamicsPayment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/online-payments');
        $this->crud->setEntityNameStrings('Online Payment', 'Online Payments');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in PaynamicsPaymentRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        
        // $this->crud->addClause('where', 'status', 'APPROVED');
        $this->crud->addClause('whereIn', 'response_code', ['GR001', 'GR002', 'GR033']);
        // $this->crud->addClause('where', 'expiry_limit', '<', Carbon::now());

        $this->crud->denyAccess(['create', 'update', 'edit', 'delete', 'clone']);
        $this->crud->removeButtons(['create', 'update', 'edit', 'delete', 'clone']);
        
        // Approve Button
        $this->crud->allowAccess('publish');
        $this->crud->addButtonFromView('line', 'Publish', 'paynamicsPayment.publish', 'end');

        // Custom List View
        $this->crud->setListView('paynamics.list');
        
        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */
        // $this->crud->addColumn([
        //     'label' => 'ID',
        //     'type' => 'text',
        //     'name' => 'id',
        // ]);

        $this->crud->addColumn([
            'label' => 'School Year',
            'type' => 'select',
            'name' => 'school_year_id',
            'entity' => 'schoolYear',
            'attribute' => 'schoolYear',
            'model' => 'App\Models\SchoolYear'
        ]);

        $this->crud->addColumn([
            'label' => 'Term',
            'type' => 'text',
            'name' => 'enrollment.term_type'
        ]);

        $this->crud->addColumn([
            'label' => 'Student No.',
            'type' => 'text',
            'name' => 'studentnumber'
        ]);

        $this->crud->addColumn([
            'label' => 'Payment Method',
            'type' => 'select',
            'name' => 'payment_method_id',
            'entity' => 'paymentMethod',
            'attribute' => 'name',
            'model' => 'App\Models\PaymentMethod'
        ]);

        $this->crud->addColumn([
            'label' => 'Ref No.',
            'type' => 'text',
            'name' => 'request_id'
        ]);

        $this->crud->addColumn([
            'label' => 'Amount',
            'type' => 'number',
            'name' => 'amount',
            'decimals' => 2,
            'thousands_sep' => ', '
        ]);

        $this->crud->addColumn([
            'label' => 'Fee',
            'type' => 'number',
            'name' => 'fee',
            'decimals' => 2,
            'thousands_sep' => ', '
        ]);

        $this->crud->addColumn([
            'label' => 'Total',
            'type' => 'text',
            'name' => 'total_payment',
            'decimals' => 2,
            'thousands_sep' => ', '
        ]);

        $this->crud->addColumn([
            'label' => 'Status',
            'type' => 'text',
            'name' => 'response_message'
        ]);
        
        $this->crud->addColumn([
            'label' => 'Published Amount',
            'type' => 'text',
            'name' => 'total_published_amount'
        ]);

        $this->crud->addColumn([
            'label' => 'Amount to Publish',
            'type' => 'text',
            'name' => 'total_unpublished_amount'
        ]);
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    private function createQuery ($orgRequestId, $orgResponseId)
    {
        $requestId  = strtoupper(substr(uniqid(), 0, 13));
        $mId        = env("PAYNAMIC_MERCHANT_ID");
        $mKey       = env("PAYNAMIC_MERCHANT_KEY");
        $orgTrxId   = $orgResponseId;
        $orgTrxId2  = $orgRequestId;
        $sign       = $mId . $requestId . $orgTrxId .$orgTrxId2 .$mKey;
        $signature  = hash("sha512", $sign);

        $xml = '<?xml version="1.0" encoding="utf-8"?>
                <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Body>
                        <query xmlns="https://testpti.payserv.net/pnxquery">
                            <merchantid>' . $mId . '</merchantid>
                            <request_id>' . $requestId . '</request_id>
                            <org_trxid>' . $orgTrxId . '</org_trxid>
                            <org_trxid2>' . $orgTrxId2 . '</org_trxid2>
                            <signature>' . $signature . '</signature>
                        </query>
                    </soap:Body>
                </soap:Envelope>';
        
        $headers = array("Content-type: text/xml", "Content-length: " . strlen($xml));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://testpti.payserv.net/pnxquery/queryservice.asmx");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        $clean_xml      = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $response);
        $xml            = simplexml_load_string($clean_xml);
        $jsonEncoded    = json_encode($xml);
        $jsonDecoded    = json_decode($jsonEncoded, true);

        return $jsonDecoded;
    }

    public function notification ()
    {
        request()->validate([
            'paymentresponse' => 'required'
        ]);

        $paymentResponse    = preg_replace('/\s+/', '+', request()->paymentresponse);
        $decodedXML         = base64_decode($paymentResponse);
        $xml                = simplexml_load_string($decodedXML);
        $jsonEncoded        = json_encode($xml);
        $jsonDecoded        = json_decode($jsonEncoded, true);

        $query = $this->createQuery($jsonDecoded['application']['request_id'], $jsonDecoded['application']['response_id']);
        $model = $this->crud->model::where('request_id', $jsonDecoded['application']['request_id'])
                                    ->with('student', 'paymentMethod', 'schoolYear');

        if($model->first() == null) {
            return view('paynamics.paymentResponse', compact('data'));
        }                            
       
        try {
            $data['data'] = $model->first();
            $query = $query['Body']['queryResponse']['queryResult'];

            // Check If Query Is Successful By Checking Status Code of Paynamics Match By QM001
            if($query["responseStatus"]['response_code'] === "QM001") {

                $transaction = $query['txns']['ServiceResponse'];
                $response_code = $transaction["responseStatus"]["response_code"];
                $data['title'] = $transaction["responseStatus"]["response_message"];

                // APPROVED
                if($response_code === "GR001" || $response_code === "GR002") {

                    if(!$data['data']->mail_sent) {

                        Mail::to($data['data']->email)->send(new MailReceipt($data['data']));
                        $model->update([
                            'response_code' => $response_code,
                            'response_message' => $transaction['responseStatus']['response_message'],
                            'response_advise' => $transaction['responseStatus']['response_advise'],
                            'mail_sent' => 1,
                            'status' => 'APPROVED'
                        ]);

                    }
                }

                // FAILED
                else if($response_code === "GR003") {

                    if(!$data['data']->mail_sent) {

                        Mail::to($data['data']->email)->send(new MailReceipt($data['data']));
                        $model->update([
                            'response_code' => $response_code,
                            'response_message' => $transaction['responseStatus']['response_message'],
                            'response_advise' => $transaction['responseStatus']['response_advise'],
                            'mail_sent' => 1,
                            'status' => 'FAILED'
                        ]);

                    }
                }

                // PENDING
                else if($response_code === "GR033") {

                    Mail::to($data['data']->email)->send(new MailReceipt($data['data']));
                    $model->update([
                        'response_code' => $response_code,
                        'response_message' => $transaction['responseStatus']['response_message'],
                        'response_advise' => $transaction['responseStatus']['response_advise'],
                        'mail_sent' => 1,
                        'status' => 'PENDING'
                    ]);
                }

                else {
                    $model->update([
                        'response_code' => $response_code,
                        'response_message' => $transaction['responseStatus']['response_message'],
                        'response_advise' => $transaction['responseStatus']['response_advise'],
                        'mail_sent' => 1,
                        'status' => 'APPROVED'
                    ]);
                }

            } 
            else {
                $transaction = $query['txns']['ServiceResponse'];
                $response_code = $transaction["responseStatus"]["response_code"];
                $data['title'] = $transaction["responseStatus"]["response_message"];

                $model->update([
                    'response_code' => $response_code,
                    'response_message' => $transaction['responseStatus']['response_message'],
                    'response_advise' => $transaction['responseStatus']['response_advise'],
                    'mail_sent' => 0,
                    'status' => 'DECLINED'
                ]);
            }
        }
        catch (\Exception $e) { abort(404, $e); }
    }

    // public function cancel (Request $request)
    // {
    //     // dd($request);
    //     $request->validate([
    //         'requestid' => 'required',
    //         'responseid' => 'required',
    //     ]);

    //     $data           = ['message' => 'No Transaction Found', 'data' => null];
    //     $request_id     = base64_decode($request->requestid);
    //     $response_id    = base64_decode($request->responseid);
    //     $model          = $this->crud->model::where('request_id', $request_id)->with('student', 'paymentMethod', 'schoolYear');

    //     if($model->first() == null) {
    //         return view('paynamics.paymentResponse', compact('data'));
    //     }

    //     $query = $this->createQuery($request_id, $response_id);

    //     try {
    //         $data['data'] = $model->first();
    //         $query = $query['Body']['queryResponse']['queryResult'];

    //         // Check If Query Is Successful By Checking Status Code of Paynamics Match By QM001
    //         if($query["responseStatus"]['response_code'] === "QM001") {

    //             $transaction = $query['txns']['ServiceResponse'];
    //             $response_code = $transaction["responseStatus"]["response_code"];
    //             $data['title'] = $transaction["responseStatus"]["response_message"];

    //             // APPROVED
    //             if($response_code === "GR001" || $response_code === "GR002") {

    //                 if(!$data['data']->mail_sent) {

    //                     Mail::to($data['data']->email)->send(new MailReceipt($data['data']));
    //                     $model->update([
    //                         'response_code' => $response_code,
    //                         'response_message' => $transaction['responseStatus']['response_message'],
    //                         'response_advise' => $transaction['responseStatus']['response_advise'],
    //                         'mail_sent' => 1,
    //                         'status' => 'APPROVED'
    //                     ]);

    //                 }

    //             }

    //             // FAILED
    //             else if($response_code === "GR003") {

    //                 if(!$data['data']->mail_sent) {

    //                     Mail::to($data['data']->email)->send(new MailReceipt($data['data']));
    //                     $model->update([
    //                         'response_code' => $response_code,
    //                         'response_message' => $transaction['responseStatus']['response_message'],
    //                         'response_advise' => $transaction['responseStatus']['response_advise'],
    //                         'mail_sent' => 1,
    //                         'status' => 'FAILED'
    //                     ]);

    //                 }

    //             }

    //             // PENDING
    //             else if($response_code === "GR033") {

    //                 Mail::to($data['data']->email)->send(new MailReceipt($data['data']));
    //                 $model->update([
    //                     'response_code' => $response_code,
    //                     'response_message' => $transaction['responseStatus']['response_message'],
    //                     'response_advise' => $transaction['responseStatus']['response_advise'],
    //                     'mail_sent' => 1,
    //                     'status' => 'PENDING'
    //                 ]);

    //             }

    //             else {
    //                 $model->update([
    //                     'response_code' => $response_code,
    //                     'response_message' => $transaction['responseStatus']['response_message'],
    //                     'response_advise' => $transaction['responseStatus']['response_advise'],
    //                     'mail_sent' => 1,
    //                     'status' => 'APPROVED'
    //                 ]);
    //             }

    //         } 
    //         else {
    //             $transaction = $query['txns']['ServiceResponse'];
    //             $response_code = $transaction["responseStatus"]["response_code"];
    //             $data['title'] = $transaction["responseStatus"]["response_message"];

    //             $model->update([
    //                 'response_code' => $response_code,
    //                 'response_message' => $transaction['responseStatus']['response_message'],
    //                 'response_advise' => $transaction['responseStatus']['response_advise'],
    //                 'mail_sent' => 0,
    //                 'status' => 'DECLINED'
    //             ]);
    //         }
    //     }
    //     catch (\Exception $e) { abort(404, $e); }
    //     return $query;
    //     $payment = $model->first();
    //     return view('paynamics.paymentCancelled', compact('data', 'payment'));
    // }

    public function response (Request $request)
    {
        $request->validate([
            'requestid' => 'required',
            'responseid' => 'required',
        ]);

        \Alert::success('Payment is now in process!')->flash();
        return redirect('online-payment');

        $data           = ['message' => 'No Transaction Found', 'data' => null];
        $request_id     = base64_decode($request->requestid);
        $response_id    = base64_decode($request->responseid);
        $model          = $this->crud->model::where('request_id', $request_id)->with('student', 'paymentMethod', 'schoolYear');

        if($model->first() == null) {
            return view('paynamics.paymentResponse', compact('data'));
        }

        $query = $this->createQuery($request_id, $response_id);
        
        try {
            $data['data'] = $model->first();
            $query = $query['Body']['queryResponse']['queryResult'];

            // Check If Query Is Successful By Checking Status Code of Paynamics Match By QM001
            if($query["responseStatus"]['response_code'] === "QM001") {

                $transaction = $query['txns']['ServiceResponse'];
                $response_code = $transaction["responseStatus"]["response_code"];
                $data['title'] = $transaction["responseStatus"]["response_message"];

                // APPROVED
                if($response_code === "GR001" || $response_code === "GR002") {

                    if(!$data['data']->mail_sent) {

                        Mail::to($data['data']->email)->send(new MailReceipt($data['data']));
                        $model->update([
                            'response_code' => $response_code,
                            'response_message' => $transaction['responseStatus']['response_message'],
                            'response_advise' => $transaction['responseStatus']['response_advise'],
                            'mail_sent' => 1,
                            'status' => 'APPROVED'
                        ]);

                    }

                }

                // FAILED
                else if($response_code === "GR003") {

                    if(!$data['data']->mail_sent) {

                        Mail::to($data['data']->email)->send(new MailReceipt($data['data']));
                        $model->update([
                            'response_code' => $response_code,
                            'response_message' => $transaction['responseStatus']['response_message'],
                            'response_advise' => $transaction['responseStatus']['response_advise'],
                            'mail_sent' => 1,
                            'status' => 'FAILED'
                        ]);

                    }

                }

                // PENDING
                else if($response_code === "GR033") {

                    Mail::to($data['data']->email)->send(new MailReceipt($data['data']));
                    $model->update([
                        'response_code' => $response_code,
                        'response_message' => $transaction['responseStatus']['response_message'],
                        'response_advise' => $transaction['responseStatus']['response_advise'],
                        'mail_sent' => 1,
                        'status' => 'PENDING'
                    ]);

                }

                else {
                    $model->update([
                        'response_code' => $response_code,
                        'response_message' => $transaction['responseStatus']['response_message'],
                        'response_advise' => $transaction['responseStatus']['response_advise'],
                        'mail_sent' => 1,
                        'status' => 'APPROVED'
                    ]);
                }

            } 
            else {
                $transaction = $query['txns']['ServiceResponse'];
                $response_code = $transaction["responseStatus"]["response_code"];
                $data['title'] = $transaction["responseStatus"]["response_message"];

                $model->update([
                    'response_code' => $response_code,
                    'response_message' => $transaction['responseStatus']['response_message'],
                    'response_advise' => $transaction['responseStatus']['response_advise'],
                    'mail_sent' => 0,
                    'status' => 'DECLINED'
                ]);
            }
        }
        catch (\Exception $e) { abort(404, $e); }

        $payment = $model->first();

        \Alert::success("Payment has been Successful!")->flash();
        return redirect('/online-payment');

        // return view('paynamics.paymentResponse', compact('data', 'payment'));
    }

    /*
    |--------------------------------------------------------------------------
    | PUBLISH PAYMENT
    | FOR
    | ONLINE PAYMENTS / PAYNAMICS PAYMENT
    |--------------------------------------------------------------------------
    */
    public function publishPayment(Request $request)
    {
        $paynamics_payment = PaynamicsPayment::where('id', $request->paynamics_payment_id)->first();
        // Paynamics Payment Not Found.
        if(! $paynamics_payment) {
            \Alert::warning("Payment Not Found!")->flash();
            return redirect()->back();
        }
        // Enrollment Not Found.
        if(! $paynamics_payment->enrollment) {
            \Alert::warning("Payment's Enrollment Not Found!")->flash();
            return redirect()->back();
        }
        // Student Not Found.
        if(! $paynamics_payment->student) {
            \Alert::warning("Payment's Student Not Found!")->flash();
            return redirect()->back();
        }

        $payable = ['id' => null, 'type' => null];
        if($request->payment_for !== null)
        {
            $array           = explode("|",$request->payment_for);
            $payable['id']   = (int)$array[0];
            $payable['type'] = $array[1];
        }

        if($payable['type'] === null) {
            $payment_historable_type = null;
        } else if ($payable['type'] === 'OtherProgram' || $payable['type'] === 'OtherService') {
            $payment_historable_type = 'App\\Selected' . $payable["type"];
        } else {
            $payment_historable_type = 'App\\' . $payable["type"];
        }

        $payment_history = PaymentHistory::create([
            'amount'            => $request->amount,
            'description'       => $request->description,
            'user_id'           => backpack_auth()->user()->id,
            'enrollment_id'     => $paynamics_payment->enrollment_id,
            'payment_method_id' => $paynamics_payment->payment_method_id,
            'paynamics_payment_id'    => $paynamics_payment->id,
            'payment_historable_id'   => $payable['id'],
            'payment_historable_type' => $payment_historable_type,
            'date_received'     => Carbon::now()
        ]);

        /**********************
        * Invoice The Payment *
        **********************/
        if($payment_history) {
            $payment_invoice = PaymentHistory::addInvoicePayment($payment_history);
            
            if($payment_invoice['status'] == 'success') {
                \Alert::success("Payment has been successfully published and set invoiced!")->flash();
                return redirect()->back();
            } else {
                \Alert::success("Payment has been successfully published but Invoice is NOT set!")->flash();
                return redirect()->back();
            }
        }

        \Alert::warning("Payment Not Publish!")->flash();
        return redirect()->back();
    }

    /*
    |--------------------------------------------------------------------------
    | GET PAYMENT TYPES
    | API For Online Payments / Paynamics Payment Publish Button
    |--------------------------------------------------------------------------
    */
    public function getPaymentTypes($id)
    {  
        $response = [
            'status'  => null,
            'data'    => null,
            'message' => null,
        ];
        $paynamics_payment = PaynamicsPayment::where('id', $id)->first();

        if(! $paynamics_payment) {
            $response['status']  = 'error';
            $response['message'] = 'Online Payment Not Found.';
        }

        $enrollment = $paynamics_payment->enrollment;

        if(! $enrollment) {
            $response['status']  = 'error';
            $response['message'] = 'Enrollment Not Found.';
        }

        $tuition = $enrollment->tuition;

        if(! $tuition) {
            $response['status']  = 'error';
            $response['message'] = 'Enrollment Tuition Not Found.';
        }

        $selected_other_programs =  SelectedOtherProgram::where('enrollment_id', $enrollment->id)
                                        ->where('approved', 1)
                                        ->with('otherProgram')
                                        ->with('user')
                                        ->get();
        $selected_other_services =  SelectedOtherService::where('enrollment_id', $enrollment->id)
                                        ->where('approved', 1)
                                        ->with('otherService')
                                        ->with('user')
                                        ->get();
        $additional_fees         =  AdditionalFee::where('enrollment_id', $enrollment->id)->with('user')->get();

        $data = [
            'selected_other_programs' => $selected_other_programs,
            'selected_other_services' => $selected_other_services,
            'additional_fees'         => $additional_fees,
        ];

        $response['status']  = 'success';
        $response['data']    = $data;
        $response['message'] = 'Payment For data has been fetched successfully!';

        return response()->json($response);
    }
}
