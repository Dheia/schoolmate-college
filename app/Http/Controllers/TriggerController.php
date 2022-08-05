<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;


use App\Events\DisplayLastLogin;
use App\Events\EmployeeDisplayLastLogin;
use App\Events\StudentDisplayLastLogin;
use App\Events\SingleDisplay;

use App\Jobs\ProcessSMSIN;
use App\Jobs\ProcessSMSOUT;
use App\Jobs\ProcessEmployeeSMSIn;
use App\Jobs\ProcessEmployeeSMSOut;

// MODELS
use App\Models\TurnstileLog;
use App\Models\Student;
use App\Models\Rfid;
use App\Models\StudentSmsTagging as SMS;
use App\Models\SmsLog;
use App\Models\Employee;


use Illuminate\Support\Facades\Redis;


class TriggerController extends Controller
{
    //
    use SmartMessaging;
  
    public function trigger(Request $request){
    	$rfid      = request('rfid');
        $turnstile = request('turnstile');
        $in        = request('in');

        if($rfid && $turnstile && $in)
        {
            // Call Event
            if($in == "in") {
                $this->login($rfid,$turnstile);	
            }
            if($in == "out") {
                $this->logout($rfid,$turnstile);	
            }

            // Get Rfid Type(Student, Employee)
            $rfidEntity = Rfid::where('rfid', '=', $rfid)->first();
            $rfidType = $rfidEntity ? $rfidEntity->user_type : null;

            if ($rfidType === 'student') { 
                event(new StudentDisplayLastLogin($rfid, $turnstile, $in));
                event(new DisplayLastLogin($rfid, $turnstile, $in));
            } else if ($rfidType === 'employee') { 
                event(new EmployeeDisplayLastLogin($rfid, $turnstile, $in));
            } else {
                abort(404, "RFID User Not Found.");
            }
        }

        
    }

    public function tapEmployee ()
    {

    }

    public function login($rfid,$turnstile){

        $sms_success = null;
        $is_logged_in = TurnstileLog::where('rfid', $rfid)->where('is_logged_in',1)->orderBy('id','DESC')->first() ?? null;
        $msg = null;

        $student = Rfid::where('rfid', $rfid)->where('deleted_at', null)->first();

        if($student){

            if($is_logged_in == null){

                if($student->user_type == "student"){

                    if(env('ENABLE_SMS_TURNSTILE_IN') == "1"){
                        
                        // if(env('SMS_PROVIDER') === "GLOBE") {
                        //     $sms_success = $this->sendSMSIn($rfid);

                        // }
                        if(env('SMS_PROVIDER') === "SMART") {

                            ProcessSMSIN::dispatch($rfid)
                							->delay(now()->addSeconds(10));
                            
                        }
                    }
                            
                } else if  ($student->user_type === 'employee') {
                    if(env('ENABLE_SMS_TURNSTILE_IN') == "1"){
                        if(env('SMS_PROVIDER') === "SMART") {
                            ProcessEmployeeSMSIn::dispatch($rfid)->delay(now()->addSeconds(10));
                        }
                    }
                }



                $timelog                = new TurnstileLog;
                $timelog->rfid          = $student->rfid;
                $timelog->timein        = Carbon::now();
                $timelog->location_in   = $turnstile;
                $timelog->is_logged_in  = True;



                if($timelog->save()){

                    $msg = [
                        "sms_success" => $sms_success,
                        "error" => False,
                        "user_type" => $student->user_type,
                        "msg" => "You're now login!"
                    ];
                }else{
                    $msg = [
                        "sms_success" => $sms_success,
                        "error" => True,
                        "user_type" => $student->user_type,
                        "msg" => "Save not successful."
                    ];
                }

                

                // SMS API End Here
            }
            $error_msg = "0";
        }
        else {
            $msg = "Not Registered";
        }
        print_r(json_encode($msg));
        
       
    }

    public function logout($rfid,$turnstile) {

            
        // $is_logged_in = TurnstileLog::where('rfid', $rfid)->where('is_logged_in',0)->first();
        $sms_success = null;

        $student = Rfid::where('rfid', $rfid)->first();
        
        if($student){
            $timelog = TurnstileLog::where('rfid',$rfid)->where('is_logged_in',1)->first();
            if($timelog){
                if($timelog->is_logged_in == "1"){
                
                    $timelog->timeout = now();
                    $timelog->is_logged_in = false;
                    $timelog->location_out   = $turnstile;
                    $timelog->update();

                    // Send SMS here.....
                    if($student->user_type == "student"){
                        if(env('ENABLE_SMS_TURNSTILE_OUT') == "1"){
                            // if(env('SMS_PROVIDER') === "GLOBE") {
                            //     $sms_success = $this->sendSMSOut($rfid);
                            // }

                            if(env('SMS_PROVIDER') === "SMART") {
                                ProcessSMSOUT::dispatch($rfid)
                							->delay(now()->addSeconds(10));

                            }
                        }
                    }
                    else if($student->user_type === 'employee') {

                        if(env('ENABLE_SMS_TURNSTILE_OUT') == "1"){
                            if(env('SMS_PROVIDER') === "SMART") {
                                ProcessEmployeeSMSOut::dispatch($rfid)->delay(now()->addSeconds(10));
                            }
                        }
                    }

                    // SMS API End Here
                

                }
            }else {
                $error_msg = "Did not login";
            }

            
                $error_msg = "0";
        }else {
            $error_msg = "Did not login";
        }

        $msg = [
            "sms_success" => $sms_success,
            "error" => $error_msg,
            "msg" => "You're now logout!"
        ];

        print_r(json_encode($msg));
    }

    

}
