<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\SchoolYear;
use App\Models\PaymentMethod;
use App\Models\PaynamicsPayment;

class Paynamic extends Controller
{

	private $data;
	private $merchantId;
	private $merchantKey;

    private $fee;
    private $fixedAmount;
    private $requestId;

    protected $email;
    
    public function __construct ($data)
    {
    	$this->data = $data;
        $this->requestId = $this->generateRequestId();
    	$this->merchantId = env('PAYNAMIC_MERCHANT_ID');
    	$this->merchantKey = env('PAYNAMIC_MERCHANT_KEY');

        $this->initialize();
    }

    private function initialize ()
    {
        $_mid = $this->merchantId; //<-- your merchant id
        $_requestid = $this->requestId;
        $_ipaddress = env("SERVER_IP");
        $_noturl = env("APP_URL") . "/online-payment/paynamics/notification"; // url where response is posted
        $_resurl = env("APP_URL") . "/online-payment/paynamics/response"; //url of merchant landing page
        $_cancelurl = env("APP_URL") . "/online-payment/paynamics/response"; //url of merchant landing page
        $_fname = $this->student()->firstname; // kindly set this to first name of the customer
        $_mname = $this->student()->middlename; // kindly set this to middle name of the cutomer
        $_lname = $this->student()->lastname; // kindly set this to last name of the cutomer
        $_addr1 = $this->student()->street_number;// kindly set this to address2 of the cutomer
        $_addr2 = 'XXX'; // kindly set this to address1 of the cutomer
        $_city = $this->student()->city_municipality; // kindly set this to city of the cutomer
        $_state = $this->student()->province; // kindly set this to state of the cutomer
        $_country = "PH"; // kindly set this to country of the cutomer
        $_zip = "2003"; // kindly set this to zip/postal of the cutomer
        $_sec3d = "try3d"; // 
        $_email = $this->data['email']; // kindly set this to email of the cutomer
        $_phone = $this->student()->contactnumber; // EXAMPLE ONLY kindly set this to phone number of the cutomer
        $_mobile = $this->student()->contactnumber; // kindly set this to mobile number of the cutomer
        $_clientip = $this->getClientIp();
        $_amount = number_format((float)$this->data['amount'] + $this->getFee() + $this->getFixedAmount(), 2, '.', ''); // kindly set this to the total amount of the transaction. Set the amount to 2 decimal point before generating signature.
        $_currency = "PHP"; //PHP or USD
        $forSign = $_mid . $_requestid . $_ipaddress . $_noturl . $_resurl .  $_fname . $_lname . $_mname . $_addr1 . $_addr2 . $_city . $_state . $_country . $_zip . $_email . $_phone . $_clientip . $_amount . $_currency . $_sec3d;
        $cert = $this->merchantKey; //<-- your merchant key

            // echo $_mid . "<hr />";
            // echo $cert . "<hr />";
            // echo $forSign . "<hr />";
        $_sign = hash("sha512", $forSign.$cert);
        $xmlstr = "";

        $strxml = "";

        $strxml = $strxml . "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
        $strxml = $strxml . "<Request>";
        $strxml = $strxml . "<orders>";
        $strxml = $strxml . "<items>";
            $strxml = $strxml . "<Items>";
                $strxml = $strxml . "<itemname>
                                        " . $this->data['description'] . "
                                    </itemname>
                                    <quantity>1</quantity>
                                    <amount>". $this->data['amount'] ."</amount>"; // pls change this value to the preferred item to be seen by customer. (eg. Room Detail (itemname - Beach Villa, 1 Room, 2 Adults       quantity - 0       amount - 10)) NOTE : total amount of item/s should be equal to the amount passed in amount xml node below. 
            $strxml = $strxml . "</Items>";
            $strxml = $strxml . "<Items>";
                $strxml = $strxml . "<itemname>
                                        Fee
                                    </itemname>
                                    <quantity>1</quantity>
                                    <amount>" . number_format($this->getFee() + $this->getFixedAmount(), 2, '.', '') . "</amount>"; // pls change this value to the preferred item to be seen by customer. (eg. Room Detail (itemname - Beach Villa, 1 Room, 2 Adults       quantity - 0       amount - 10)) NOTE : total amount of item/s should be equal to the amount passed in amount xml node below. 
            $strxml = $strxml . "</Items>";
        $strxml = $strxml . "</items>";
        $strxml = $strxml . "</orders>";
        $strxml = $strxml . "<mid>" . $_mid . "</mid>";
        $strxml = $strxml . "<request_id>" . $_requestid . "</request_id>";
        $strxml = $strxml . "<ip_address>" . $_ipaddress . "</ip_address>";
        $strxml = $strxml . "<notification_url>" . $_noturl . "</notification_url>";
        $strxml = $strxml . "<response_url>" . $_resurl . "</response_url>";
        $strxml = $strxml . "<cancel_url>" . $_cancelurl . "</cancel_url>";
        $strxml = $strxml . "<mtac_url>https://tigernethost.com/tnc.php</mtac_url>"; // pls set this to the url where your terms and conditions are hosted
        $strxml = $strxml . "<descriptor_note>" . $this->data['description'] . "</descriptor_note>"; // pls set this to the descriptor of the merchant ""
        $strxml = $strxml . "<fname>" . $_fname . "</fname>";
        $strxml = $strxml . "<lname>" . $_lname . "</lname>";
        $strxml = $strxml . "<mname>" . $_mname . "</mname>";
        $strxml = $strxml . "<address1>" . $_addr1 . "</address1>";
        $strxml = $strxml . "<address2>" . $_addr2 . "</address2>";
        $strxml = $strxml . "<city>" . $_city . "</city>";
        $strxml = $strxml . "<state>" . $_state . "</state>";
        $strxml = $strxml . "<country>" . $_country . "</country>";
        $strxml = $strxml . "<zip>" . $_zip . "</zip>";
        $strxml = $strxml . "<secure3d>" . $_sec3d . "</secure3d>";
        $strxml = $strxml . "<trxtype>sale</trxtype>";
        $strxml = $strxml . "<email>" . $_email . "</email>";
        $strxml = $strxml . "<phone>" . $_phone . "</phone>";
        $strxml = $strxml . "<mobile>" . $_mobile . "</mobile>";
        $strxml = $strxml . "<client_ip>" . $_clientip . "</client_ip>";
        $strxml = $strxml . "<amount>" . $_amount . "</amount>";
        $strxml = $strxml . "<currency>" . $_currency . "</currency>";
        $strxml = $strxml . "<mlogo_url>". asset(config('settings.schoollogo')) . "</mlogo_url>";// pls set this to the url where your logo is hosted
        $strxml = $strxml . "<pmethod>" . $this->getPaymentCode() . "</pmethod>";
        $strxml = $strxml . "<signature>" . $_sign . "</signature>";
        $strxml = $strxml . "</Request>";
        $b64string =  base64_encode($strxml);

        $this->createPaymentRow($_sign, $this->getFee() + $this->getFixedAmount());


        echo    '<form id="paymentForm" name="paymentForm" method="post" action="' . env("PAYNAMIC_URL") . '">
                    <input type="hidden" name="paymentrequest" id="paymentrequest" value="'.$b64string.'">
                </form>';
        echo    "<script>
                    document.forms['paymentForm'].submit();
                </script>";
    }

    private function createPaymentRow ($signature, $fee)
    {
        $payment                    = new PaynamicsPayment;
        $payment->school_year_id    = $this->data['school_year_id'];
        $payment->studentnumber     = $this->data['studentnumber'];
        $payment->amount            = $this->data['amount'];
        $payment->fee               = $fee;
        $payment->email             = $this->data['email'];
        $payment->description       = $this->data['description'];
        $payment->request_id        = $this->requestId;
        $payment->payment_method_id = $this->data['payment_method_id'];
        $payment->signature         = $signature;
        $payment->status            = "CREATED";
        $payment->save();
    }

    private function student ()
    {
        $student = Student::where('studentnumber', $this->data['studentnumber'])->first();

        // If Student Not Found Cancel Action
        if($student == null) {
            abort(404, "Student Not Found");
        }

        return $student;
    }

    private function generateRequestId ()
    {
        return strtoupper(substr(uniqid(), 0, 13));
    }

    private function getPaymentCode ()
    {
    	$paymentMethod = PaymentMethod::where('id', $this->data['payment_method_id'])->first();
    	return $paymentMethod->code;
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

    private function getFee ()
    {
        $paymentMethod = PaymentMethod::where('id', $this->data['payment_method_id'])->first();
        $percentage = 0;

        if($paymentMethod) {
            $percentage = (float)$this->data['amount'] * ((float)$paymentMethod->fee/100);
        }
        return $percentage;
    }

    private function getFixedAmount ()
    {
        $paymentMethod = PaymentMethod::where('id', $this->data['payment_method_id'])->first();
        return $paymentMethod ? $paymentMethod->fixed_amount : 0;
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
