<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\PaymentMethod;
use App\Models\PaynamicsPayment;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailablePaynamicsPaymentReceipt as MailReceipt;

use Carbon\Carbon;
use Log;

class PaynamicV2 extends Controller
{

    private $data;
    private $merchantId;
    private $merchantKey;

    private $tnh_fixed_markup;
    private $tnh_percent_markup;
    private $tnh_tax_and_fee;

    private $amount;
    private $total_fee;
    private $total_amount;

    private $tnh_fee;
    private $paynamics_fee;
    private $paynamics_percent;
    private $paynamics_minimum;

    private $fee;
    private $fixedAmount;
    private $requestId;

    protected $email;
    protected $student;
    protected $enrollment;
    protected $school_year;

    protected $prefix;
    protected $paymentMethod;

    private $inclusive;

    public function initialize ($data, $prefix = null)
    {
      $response = [
        'status'  => null,
        'message' => 'Success',
        'data'    => null,
      ];

      /*
       * GET STUDENT INFORMATION
       */
      $this->data         = $data;
      $this->student      = $student    = $this->student();
      $this->prefix       = $prefix ? '/' . $prefix : "";

      $this->enrollment     = $enrollment    = Enrollment::where('id', $this->data['enrollment_id'])->first();
      $this->paymentMethod  = $paymentMethod = PaymentMethod::where('id', $this->data['payment_method_id'])->first();
      
      abort_if(! $enrollment, 404, 'Enrollment Not Found.');
      // abort_if(! $enrollment->invoiced, 404, 'Enrollment Invoice is NOT yet SET.');
      abort_if(! $paymentMethod, 404, 'Payment Method Not Found.');

      /*
       * PAYNAMICS REQUIRED DATA
       */
      $this->requestId    = $this->generateRequestId();
      $this->merchantId   = env('PAYNAMIC_MERCHANT_ID');
      $this->merchantKey  = env('PAYNAMIC_MERCHANT_KEY');

      $this->inclusive        = env('PAYNAMIC_INCLUSIVE');

      $this->bizWalletId      = env('PAYNAMIC_BIZ_WALLET_ID');
      $this->tnhBizWalletId   = env('PAYNAMIC_TNH_BIZ_WALLET_ID');

      $this->tnh_fixed_markup     = env('TNH_MARKUP_FIXED');
      $this->tnh_percent_markup   = env('TNH_MARKUP_PERCENT');

      $this->settlementId = $this->generateSettlementId();
      $this->settlementId2 = $this->generateSettlementId2();

      /** 
       * TOTAL AMOUNT COMPUTATION AND FEES ( EXCLUSIVE )
       */
      if(! $this->inclusive ) {
        $this->amount           = $this->data['amount'];
        $this->total_fee        = number_format($this->getTotalTaxAndFee(), 2, '.', '');
        $this->total_amount     = number_format((double)$this->amount + $this->total_fee, 2, '.', '');
        
        $this->paynamics_fee    = number_format($this->getPaynamicsTaxAndFee(), 2, '.', '');
        $this->tnh_fee          = number_format($this->getTnhTaxAndFee(), 2, '.', '');
        
        $total_fee_non_vat      = $this->paynamics_fee + $this->tnh_fee;
        $this->settlement_amount = (double)$this->data['amount'];
      }
      
      /** 
       * TOTAL AMOUNT COMPUTATION AND FEES ( INCLUSIVE )
       */
      if( $this->inclusive ) {
        $this->amount           = $this->data['amount']; // It Will Save in PaynamicsPayments (1st Settlement)
        $this->total_fee        = number_format($this->getTotalTaxAndFee(), 2, '.', ''); // 2nd Order Unit Price // PTI Fee
        $this->total_amount     = number_format((double)$this->amount + $this->total_fee, 2, '.', ''); // It will be the Amount
        
        $this->paynamics_fee    = number_format($this->getPaynamicsTaxAndFee(), 2, '.', '');
        $this->tnh_fee          = number_format($this->getTnhTaxAndFee(), 2, '.', ''); // It will be the 2nd Settlement
        
        $total_fee_non_vat      = $this->paynamics_fee + $this->tnh_fee;

        $this->settlement_amount = number_format((double)$this->amount - $this->total_fee, 2, '.', '');
      }

      /** 
       * TESTING COMPUTATION
       */
      // $computations = [
      //     'amount'        => $this->amount,
      //     'total_fee'     => $this->total_fee,
      //     'total_amount'  => $this->total_amount,
      //     'paynamics_fee' => $this->paynamics_fee,
      //     'tnh_fee'       => $this->tnh_fee,
      // ];
      // dd($computations);

      /*
       * TRANSACTION INFORMATION
       */
      $_mid       = $this->merchantId; //<-- your merchant id
      $_requestid = $this->requestId;
      $_pchannel  = $this->getPaymentCode();
      $_ipaddress = env("SERVER_IP");
      $_descnote  = env("PAYNAMIC_DESC_NOTE");

      $_noturl    = env("APP_URL") . "/online-payment/paynamics/notification";
      $_resurl    = env("APP_URL") . "/" . $prefix . "/online-payment/paynamics/response/" . $prefix . "/" . $student->studentnumber . "/" . $this->requestId; //url of merchant landing page
      $_cancelurl = env("APP_URL") . "/" . $prefix . "/online-payment/paynamics/cancel/" . $prefix . "/" . $student->studentnumber; //url of merchant landing page

      $_fee       = $this->total_fee;
      $_amount    = $this->total_amount; // kindly set this to the total amount of the transaction. Set the amount to 2 decimal point before generating signature.

      // SPLIT FEE
      $paynamics_fee  = $this->paynamics_fee; // Paynamics Fee
      $tnh_fee        = $this->tnh_fee; // TNH Fee / Markup
      // $this->fee  = $_fee;

      $_currency  = "PHP"; //PHP or USD

      $_mlogo_url = config('settings.schoollogo');
      $_mtac_url  = 'https://tigernethost.com/tnc.php';

      $_pmethod           = $this->paymentMethod->method;
      $_trx_type          = 'sale';
      $_payment_action    = $this->paymentMethod->payment_action; // PLACE IN DATABASE COLUMN PAYMENT ACTION
      $_collection_method = 'single_pay';
      $_payment_notification_status  = '1';
      $_payment_notification_channel = '1';


      /*
       * STUDENT/CUSTOMENT INFORMATION
       */
      $_fname     = $this->student->firstname; // kindly set this to first name of the customer
      $_mname     = $this->student->middlename; // kindly set this to middle name of the cutomer
      $_lname     = $this->student->lastname; // kindly set this to last name of the cutomer

      /*
       * BILLING/SHIPPING INFORMATION
       */
      $_addr1     = $this->student->street_number;// kindly set this to address2 of the cutomer
      $_addr2     = 'XXX'; // kindly set this to address1 of the cutomer
      $_city      = $this->student->city_municipality; // kindly set this to city of the cutomer
      $_state     = $this->student->province; // kindly set this to state of the cutomer
      $_country   = "PH"; // kindly set this to country of the cutomer
      $_zip       = "2003"; // kindly set this to zip/postal of the cutomer
      $_sec3d     = "try3d"; // 
      $_email     = $this->data['email']; // kindly set this to email of the cutomer
      $_phone     = $this->student->contactnumber; // EXAMPLE ONLY kindly set this to phone number of the cutomer
      $_mobile    = $this->student->contactnumber; // kindly set this to mobile number of the cutomer
      $_clientip  = $this->getClientIp();
      $_dob       = "12-01-1986";

      /*
       * SETTLEMENT INFORMATION (Settlement To School)
       */
      $_biz_wallet_id       = $this->bizWalletId;
      $_settlement_id       = $this->settlementId;
      $_settlement_amount   = $this->settlement_amount; // THE GROSS AMOUNT THAT THE PARENT OR STUDENT ENTERED
      $_settlement_currency = 'PHP';
      $_reason              = env('SCHOOL_NAME') . ' Partner settlement';

      /*
       * SETTLEMENT INFORMATION 2 (Settlement To TNH)
       */
      $_biz_wallet_id_2       = $this->tnhBizWalletId;
      $_settlement_id_2       = $this->settlementId2;
      $_settlement_amount_2   = (double)$tnh_fee; // TNH FEE / MARKUP
      $_settlement_currency_2 = 'PHP';
      $_reason_2              = env('SCHOOL_NAME') . ' - TNH Transaction Fee';

      /*
       * STUDENT/CUSTOMENT SIGNATURE
       */
      $forSign    = $_fname . $_lname . $_mname . $_email . $_phone . $_mobile . $_dob . $this->merchantKey;
      $_signature      = hash("sha512", $forSign);

      /*
       * TRANSACTION SIGNATURE
       */
      $_rawTrx = $this->merchantId . $this->requestId . $_noturl . $_resurl . $_cancelurl . $_pmethod . $_payment_action . $_collection_method . $_amount . $_currency . $_descnote . $_payment_notification_status . $_payment_notification_channel . $this->merchantKey;
      $_signatureTrx = hash("sha512", $_rawTrx);
      
      /*
       * SETTLEMENT SIGNATURE
       */
      $_rawSttlmnt         = $_biz_wallet_id . $_settlement_amount . $_settlement_currency . $_reason . $_settlement_id . $this->merchantKey;
      $_signatureStlmnt    = hash("sha512", $_rawSttlmnt);

      /*
       * SETTLEMENT 2 SIGNATURE
       */
      $_rawSttlmnt_2         = $_biz_wallet_id_2 . $_settlement_amount_2 . $_settlement_currency_2 . $_reason_2 . $_settlement_id_2 . $this->merchantKey;
      $_signatureStlmnt_2    = hash("sha512", $_rawSttlmnt_2);

      $data = array(
          'transaction' => 
          array(
            'request_id'       => $_requestid,
            'notification_url' => $_noturl,
            'response_url'     => $_resurl,
            'cancel_url'       => $_cancelurl,
            'pmethod'          => $_pmethod,
            'pchannel'         => $_pchannel, //Check documentation page 156
            'payment_action'   => $_payment_action, //Check documentation page 156
            'collection_method' => $_collection_method,
            'payment_notification_status'  => $_payment_notification_status,
            'payment_notification_channel' => $_payment_notification_channel,
            'mlogo_url' => env("APP_URL") . $_mlogo_url, //School Logo
            'amount'    => strval($_amount), 
            'currency'  => $_currency,
            'descriptor_note'  => $_descnote,
            'trx_type'  => $_trx_type,
            'mtac_url'  => $_mtac_url, // Check tigernet website https://tigernethost.com/tnc.php
            'signature' => $_signatureTrx,
          ),
          'billing_info' => 
          array(
            'billing_address1' => $_addr1 ? $_addr1 : "",
            'billing_address2' => $_addr2 ? $_addr2 : "",
            'billing_city'     => $_city ? $_city : "",
            'billing_state'    => $_state? $_state : "",
            'billing_country'  => $_country ? $_country : "PH",
            'billing_zip'      => $_zip ? $_zip : "",
          ),
          'shipping_info' => 
          array(
            'fname'  => $_fname,
            'lname'  => $_lname,
            'mname'  => $_mname,
            'email'  => $_email,
            'phone'  => $_phone,
            'mobile' => $_mobile,
            'dob'    => '12-01-1986',
          ),
          'customer_info' => 
          array(
            'fname'  => $_fname,
            'lname'  => $_lname,
            'mname'  => $_mname,
            'email'  => $_email,
            'phone'  => $_phone,
            'mobile' => $_mobile,
            'dob'    => '12-01-1986',
            'signature' => $_signature,
          ),
          'settlement_information' => 
          array(
            array(
              'biz_wallet_id'       => $_biz_wallet_id,
              'settlement_amount'   => strval($_settlement_amount),
              'settlement_currency' => $_settlement_currency,
              'reason'              => $_reason,
              'settlement_id'       => $_settlement_id,
              'signature'           => $_signatureStlmnt,
            ),
            array(
              'biz_wallet_id'       => $_biz_wallet_id_2,
              'settlement_amount'   => strval($_settlement_amount_2),
              'settlement_currency' => $_settlement_currency_2,
              'reason'              => $_reason_2,
              'settlement_id'       => $_settlement_id_2,
              'signature'           => $_signatureStlmnt_2,
            )
          ),
          'order_details' => 
          array(
            'orders' => [
              [
                'itemname'   => 'Payment for SY ' . $this->enrollment->school_year_name . ' ' . $this->student->studentnumber, //CHANGE TPO "PAYMENT FOR SY 2019-2020 200001"
                'quantity'   => 1,
                'unitprice'  => strval($this->amount), //TOTAL AMOUNT THE STUDENT/PARENT ENTERED
                'totalprice' => strval($this->amount), //TOTAL AMOUNT THE STUDENT/PARENT ENTERED
                
              ],
              [
                'itemname'   => 'PTI Fee',
                'quantity'   => 1,
                'unitprice'  => strval($_fee), // GET FEE BASED ON THE DOCUMENTATION
                'totalprice' => strval($_fee),
                
              ],
            ],
              
           
            'subtotalprice'    => strval( !$this->inclusive ? $this->total_amount : number_format($this->amount, 2, '.', '')),
            'shippingprice'    => '0.00',
            'discountamount'   => '0.00',
            'totalorderamount' => strval( !$this->inclusive ? $this->total_amount : number_format($this->amount, 2, '.', '')),
          ),
      );

      
      $data = json_encode($data, JSON_UNESCAPED_SLASHES);
      // dd($data);
      $response = [
        'status'  => 'success',
        'message' => 'Payment Data is Created',
        'data'    => $data,
        'amount'  => $_amount,
        'payment_method' => $paymentMethod
      ];

      return $response;
      // $this->createPaymentRow($data);
    }

    public function createPaymentRow ($payment_data)
    {
      $response = [
        'status'  => null,
        'message' => null,
        'data'    => null,
      ];

      $decoded_payment_data = json_decode($payment_data);
      // dd($payment_data);

      try {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, env('PAYNAMIC_URL'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
        ));
        curl_setopt($ch, CURLOPT_USERPWD, env('PAYNAMIC_USERNAME') . ":" . env('PAYNAMIC_PASSWORD'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payment_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $server_output = curl_exec($ch);
        $http_code     = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close ($ch); 

        $response_data = json_decode($server_output);

        $timestamp     = Carbon::now()->format('Y-m-d H:i:s');

        // dd($response_data);
        if($http_code != 200) {
          $response = [
            'status'  => 'error',
            'message' => 'Response Code: ' . $http_code,
            'data'    => null,
          ];

          Log::error('Online Payment Error Response - ' . $timestamp, [
            'Studentnumber'   => $this->student->studentnumber,
            'Payment Method'  => $this->paymentMethod->name,
            'Response'        => $server_output
          ]);
  
          Log::error($payment_data);

          return $response;
        }

      } catch (Exception $ex) {

        $response = [
          'status'  => 'error',
          'message' => $ex,
          'data'    => null,
        ];

        Log::error('Online Payment Error', [
          'Studentnumber'   => $this->student->studentnumber,
          'Payment Method'  => $this->paymentMethod->name,
          'Error'           => $ex
        ]);

        return $response;
      }

      // dd(json_decode($payment_data), $response_data);

      $direct_otc_info          = "";
      $payment_action_info      = "";
      $settlement_info_details  = "";

      $response['message'] = $response_data->response_code . ' - ' . $response_data->response_message;

      if( in_array($response_data->response_code, ['GR001', 'GR002', 'GR033']) ) 
      {
        /**
         * Validate Paynamics Respose
         */
        if(isset($response_data->direct_otc_info)) {
          $direct_otc_info      = is_array($response_data->direct_otc_info) 
                                        ? json_encode($response_data->direct_otc_info) 
                                        : $response_data->direct_otc_info;
        }

        if(isset($response_data->payment_action_info)) {
          $payment_action_info  =   is_array($response_data->payment_action_info) 
                                        ? json_encode($response_data->payment_action_info) 
                                        : $response_data->payment_action_info;
        }

        if(isset($response_data->direct_otc_info)) {
          if($response_data->direct_otc_info != "") {
            $response = [
              'status'  => 'success',
              'message' => 'Payment has been successfully made.',
              'data'    => $server_output,
            ];

          } 
          else {
            $response = [
              'status'  => 'error',
              'message' => 'No DIRECT OTC HAS BEEN MADE',
              'data'    => null,
            ];
          }
        } 
        else if(isset($response_data->payment_action_info)) {
          if($response_data->payment_action_info != "") {
            $response = [
              'status'  => 'success',
              'message' => 'Payment has been successfully made.',
              'data'    => $server_output,
            ];

          } 
          else {
            $response = [
              'status'  => 'error',
              'message' => 'No DIRECT OTC or PAYMENT ACTION HAS BEEN MADEE',
              'data'    => null,
            ];
          }

        } 
        else {
          $response = [
            'status'  => 'error',
            'message' => 'No DIRECT OTC or PAYMENT ACTION HAS BEEN MADE',
            'data'    => null,
          ];
        }

        $settlement_info_details    = json_encode($response_data->settlement_info_details);
        $timestamp                  = Carbon::parse($response_data->timestamp)->format('Y-m-d H:i:s');
      }

      /*
      |--------------------------------------------------------------------------
      | SAVE PAYNAMICS PAYMENT
      |--------------------------------------------------------------------------
      */

      $payment                    = new PaynamicsPayment;
      $payment->payment_method_id = $this->paymentMethod->id;
      $payment->school_year_id    = $this->enrollment->school_year_id;
      $payment->studentnumber     = $this->student->studentnumber;
      $payment->enrollment_id     = $this->enrollment->id;
      $payment->email             = $this->data['email'];
      $payment->description       = $this->data['description'];

      $payment->amount            = $this->amount;
      $payment->fee               = $this->total_fee;

      $payment->raw_data          = $payment_data;
      $payment->initial_response  = $server_output;

      $payment->request_id        = $response_data->request_id;
      $payment->response_id       = $response_data->response_id;
      $payment->merchant_id       = isset($response_data->merchant_id) ? $response_data->merchant_id : '-';
      $payment->expiry_limit      = isset($response_data->expiry_limit) ? $response_data->expiry_limit : 'NULL';
      $payment->direct_otc_info   = $direct_otc_info;
      $payment->payment_action_info   = $payment_action_info;
      $payment->response          = $server_output;

      $payment->timestamp         = $timestamp;
      $payment->signature         = isset($response_data->signature) ? $response_data->signature : '-';
      $payment->response_code     = $response_data->response_code;
      $payment->response_message  = $response_data->response_message;
      $payment->response_advise   = $response_data->response_advise;
      $payment->settlement_info_details   = $settlement_info_details;

      $payment->status            = $response_data->response_code === 'GR033' ? 'PENDING' : 'CREATED';
      $payment->save();

      return $response;  
    }

    /*
    |--------------------------------------------------------------------------
    | WEBHHOOK NOTIFICATION
    |--------------------------------------------------------------------------
    */
    public function notification (Request $request)
    {
      \Log::info('webhook noti');
      \Log::info($request);

      try {
        $alert_message = isset($request->response_message) ? $request->response_message : 'Something went wrong.';
        $response = [
          'TITLE'         => 'Paynamics Payment Notification',
          'REQUEST ID'    => $request->request_id,
          'RESPONSE CODE' => $request->response_code,
          'MESSAGE'       => $request->response_message
        ];

        $paynamics_payment = PaynamicsPayment::where('request_id', $request->request_id)->first();

        if(! $paynamics_payment) {
          $alert_message       = 'Paynamics Payment NOT Found';
          $response['MESSAGE'] = 'Paynamics Payment NOT Found. ' . $request->response_message;
          \Log::info($response);
        } 
        else {

          $response['ENROLLMENT ID'] = $paynamics_payment->enrollment ? $paynamics_payment->enrollment->id : '-';

          /**
           * UPDATE PAYNAMICS PAYMENT
           */
          $paynamics_payment->update([
            // 'response'          => json_encode($request),
            'pay_reference'     => isset($request->pay_reference) ? $request->pay_reference : '',
            'response_code'     => $request->response_code,
            'response_message'  => $request->response_message,
            'response_advise'   => $request->response_advise,
            'settlement_info_details' => json_encode($request->settlement_info_details),
            'timestamp'         => Carbon::parse($request->timestamp)->format('Y-m-d H:i:s'),
            'mail_sent'         => 0
          ]);

          \Log::info($response);

          /**
           * MAIL THE PAMENT NOTIFICATION TO STUDENT
           */
          Mail::to($paynamics_payment->email)->send(new MailReceipt($paynamics_payment));

          // PAYNAMICS SUCCESS CODE IS SUCCESS ||  SUCCESS with 3DS || PENDING
          if($request->response_code == 'GR001' || $request->response_code == 'GR002' || $request->response_code != 'GR033') {
            // UPDATE PAYNAMICS PAYMENT MAIL
            $paynamics_payment->update([
              'mail_sent' => 1
            ]);

            if($request->response_code != 'GR033') {
              $paynamics_payment->update([
                'status' => 'APPROVED'
              ]);
            }
          } 
          // else {
          //   $response['TITLE'] = 'Paynamics Payment Deleted';
          //   $paynamics_payment->delete();
          //   \Log::info($response);
          // }
        }

      }
      catch (\Exception $e) {
        \Log::info([
          'TITLE'         => 'Paynamics Payment Notification Error', 
          'REQUEST ID '   => isset($request->request_id) ? $request->request_id : $request,
          'RESPONSE CODE' => isset($request->response_code) ? $request->response_code : $request,
          'ERROR: '       => $e
        ]);
        $alert_message = $e;
      }

      \Alert::warning($alert_message)->flash();
      return redirect('student/online-payment');
    }

    /*
    |--------------------------------------------------------------------------
    | WEBHHOOK RESPONSE
    |--------------------------------------------------------------------------
    */
    public function response(Request $request, $prefix, $studentnumber, $request_id) 
    {
      \Log::info('webhook response');
      \Log::info($request);

      $paynamics_payment = PaynamicsPayment::where('request_id', $request->request_id)
          ->where('studentnumber', $studentnumber)
          ->first();

      abort_if(! $paynamics_payment, 404, 'Paynamics Payment NOT Found. ' . $request->response_message);

      // Add Minutes To Last Updated
      $updated_at = Carbon::parse($paynamics_payment->updated_at)->addMinutes(15);

      if( $updated_at < Carbon::now() ) {
          return redirect()->back();
      }

      $data = [
        'amount'         => $paynamics_payment->amount + $paynamics_payment->fee,
        'payment_method' => $paynamics_payment->paymentMethod,
        'paynamics_payment' => $paynamics_payment,
      ];

      if($prefix == 'parent') {
        return view('parentPortal.payment_response')->with($data);
      }

      return view('studentPortal.payment_response')->with($data);
    }

    /*
    |--------------------------------------------------------------------------
    | WEBHHOOK CANCEL
    |--------------------------------------------------------------------------
    */
    public function cancel(Request $request, $prefix, $studentnumber) 
    {
      try {
        $alert_message = isset($request->response_message) ? $request->response_message : 'Something went wrong.';
        $response = [
          'TITLE'         => 'Paynamics Payment Cancel',
          'REQUEST ID'    => $request->request_id,
          'RESPONSE CODE' => $request->response_code,
          'MESSAGE'       => $request->response_message
        ];

        $paynamics_payment = PaynamicsPayment::where('request_id', $request->request_id)->first();

        if(! $paynamics_payment) {
          $response['MESSAGE'] = 'Paynamics Payment NOT Found. ' . $request->response_message;
          \Log::info($response);
        } 
        else {
           // UPDATE PAYNAMICS PAYMENT
          $paynamics_payment->update([
            // 'response'          => json_encode($request),
            'response_code'     => $request->response_code,
            'response_message'  => $request->response_message,
            'response_advise'   => $request->response_advise,
            'settlement_info_details' => json_encode($request->settlement_info_details),
            'mail_sent'         => 0
          ]);

          \Log::info($response);

          // MAIL TO USER
          Mail::to($paynamics_payment->email)->send(new MailReceipt($paynamics_payment));

          // PAYNAMICS SUCCESS CODE IS SUCCESS ||  SUCCESS with 3DS || PENDING
          if($request->response_code == 'GR001' || $request->response_code == 'GR002' || $request->response_code != 'GR033') {
            // UPDATE PAYNAMICS PAYMENT MAIL
            $paynamics_payment->update([
              'mail_sent'         => 1
            ]);

            if($request->response_code != 'GR033') {
              $paynamics_payment->update([
                'status' => 'APPROVED'
              ]);
            }
          } 
          else {
            $response['TITLE'] = 'Paynamics Payment Deleted';
            $paynamics_payment->delete();
            \Log::info($response);
          }
        }

      }
      catch (\Exception $e) {
        \Log::info([
          'TITLE'         => 'Paynamics Payment Cancel Error', 
          'REQUEST ID '   => isset($request->request_id) ? $request->request_id : $request,
          'RESPONSE CODE' => isset($request->response_code) ? $request->response_code : $request,
          'ERROR: '       => $e
        ]);
        $alert_message = $e;
      }
      
      $url = 'student/online-payment';

      if($prefix == 'parent') {
        $url = 'parent/student-enrollments/' . $studentnumber;
      }

      \Alert::warning($alert_message)->flash();
      return redirect($url);
    }

    private function student ()
    {
        $student = Student::where('studentnumber', $this->data['studentnumber'])->first();

        // If Student Not Found Cancel Action
        abort_if(!$student, 404, 'Student Not Found.');

        return $student;
    }

    private function generateRequestId ()
    {
        // $enrollment = Enrollment::where('id', $this->data['enrollment_id'])->first();

        // abort_if(!$enrollment, 404, 'Enrollment Not Found.');
        // abort_if(!$enrollment->invoice_no, 404, 'Enrollment Invoice is NOT yet SET.');

       //TNH02I321 - TNH then EnrollmentID|OtherProgranID|ServiceID then Invoice Number
        $req_id = 'TNH' . $this->enrollment->id . 'I' . $this->enrollment->invoice_no . now();
        return $req_id;
    }

    private function generateSettlementId ()
    {
      // $enrollment = Enrollment::where('id', $this->data['enrollment_id'])->first();

      // abort_if(!$enrollment, 404, 'Enrollment Not Found.');
      // abort_if(!$enrollment->invoice_no, 404, 'Enrollment Invoice is NOT yet SET.');

     //TNH02I321 - TNH then EnrollmentID|OtherProgranID|ServiceID then Invoice Number
      $req_id = 'TNHSETTLEMENT-' . $this->enrollment->id . 'I' . $this->enrollment->invoice_no . now();
      return $req_id;
    }

    private function generateSettlementId2 ()
    {
      // $enrollment = Enrollment::where('id', $this->data['enrollment_id'])->first();

      // abort_if(!$enrollment, 404, 'Enrollment Not Found.');
      // abort_if(!$enrollment->invoice_no, 404, 'Enrollment Invoice is NOT yet SET.');

     //TNH02I321 - TNH then EnrollmentID|OtherProgranID|ServiceID then Invoice Number
      $req_id = 'TNHSETTLEMENT2-' . $this->enrollment->id . 'I' . $this->enrollment->invoice_no . now();
      return $req_id;
    }

    private function getPaymentCode ()
    {
        // $paymentMethod = PaymentMethod::where('id', $this->data['payment_method_id'])->first();

        // // If Student Not Found Cancel Action
        // abort_if(!$paymentMethod, 404, 'Payment Method Not Found.');

        return $this->paymentMethod->code;
    }

    private function getClientIp ()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    private function createForSign ()
    {
        return $forSign;
    }

    private function parseStringXML ()
    {
        return $xml;
    }

    private function getBase64Encoded ()
    {
        return $base64;
    }

    /*
    |--------------------------------------------------------------------------
    | Compute Total Tax and Fee
    |--------------------------------------------------------------------------
    */
    private function getTotalTaxAndFee()
    {
        $fee_percent    = (double)$this->paymentMethod->fee * (12/100); //.27
        $total_tax      = (double)$this->paymentMethod->fee + $fee_percent; // 2.25 + .27 = 2.52
        $total_with_tax = -($total_tax - 100);

        $amount  =  ((double)$this->data['amount'] / ($total_with_tax/100)) - (double)$this->data['amount'];
        $min_fee =  (((double)$this->paymentMethod->minimum_fee - $this->tnh_fixed_markup) * 1.12) + $this->tnh_fixed_markup;

        return $amount > (double)$this->paymentMethod->minimum_fee ? $amount : $min_fee;
    }

    /*
    |--------------------------------------------------------------------------
    | Compute Paynamics Tax and Fee
    | 0.0252 Paynamics Percent  
    | Paynamics Fee => Multiply the Total Amount to ( 2.52/100 )
    |--------------------------------------------------------------------------
    */
    private function getPaynamicsTaxAndFee()
    {
        // Without Markup
        // nm = No Markup
        $paynamics_percent  = ((double)$this->paymentMethod->fee - $this->tnh_percent_markup); // No Mark up
        $fee_percent_nm     = $paynamics_percent  * (12/100); //.27
        $total_tax_nm       = ((double)$paynamics_percent + $fee_percent_nm) / 100; // 2.25 + .27 = 2.52


        $minimum_fee    = (double)$this->paymentMethod->minimum_fee * (double)1.12;
        $total_amount   = $this->total_amount;
        $total_fee      = $this->total_fee;

        $paynamics_fee      = (double)$total_amount * (double)$total_tax_nm; // 0.0252 Paynamics Fee Percent
        $paynamics_min_fee  = (((double)$this->paymentMethod->minimum_fee - $this->tnh_fixed_markup) * 1.12);

        return (double)$total_fee > (double)$minimum_fee ? $paynamics_fee : $paynamics_min_fee;
    }

    /*
    |--------------------------------------------------------------------------
    | Compute TNH Transaction Fee ( Markup )
    | 
    |--------------------------------------------------------------------------
    */
    private function getTnhTaxAndFee()
    {
        if((double)$this->total_fee > (double)$this->paymentMethod->minimum_fee) {
            return (double)$this->total_fee - (double)$this->paynamics_fee;
        }
        return env('TNH_MARKUP_FIXED');
    }

    private function getFixedAmount ()
    {
        // $paymentMethod = PaymentMethod::where('id', $this->data['payment_method_id'])->first();
        return $this->paymentMethod ? (float)$this->paymentMethod->fixed_amount : (float)0;
    }

    // private function getIpAddress ()
    // {
    //     $ip_address = null;

    //     if (!empty($_SERVER['HTTP_CLIENT_IP']))  //whether ip is from share internet
    //     {
    //         $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    //     }
    //     elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //whether ip is from proxy
    //     {
    //         $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    //     }
    //     else //whether ip is from remote address
    //     {
    //         $ip_address = $_SERVER['REMOTE_ADDR'];
    //     }

    //     return $ip_address;
    // }

    // SETTER

    private function setEmail ($email)
    {
        return $this->email = $email;
    }

}