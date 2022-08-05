<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\SmartJwtCredentialCrudController;

use App\Models\Rfid;
use App\Models\Student;
use App\Models\Employee;
use App\Models\SmsLog;
use App\Models\AssignTurnstileSmsReceiver;
use App\Models\StudentSmsTagging as SMS;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Arr;

trait SmartMessaging
{
    public function sendSMSInSmart($rfid, $schoolaabr){


        $success = False;

        $msg = null;

        $student = Rfid::where('rfid', $rfid)->first();

        $studentname = Student::where('studentnumber',$student->studentnumber)
                        ->where('deleted_at', null)
                        ->first();
        
        if($studentname != null){
            if(str_word_count($studentname->firstname) > 1){
                $firstname = explode(' ',trim($studentname->firstname));
                
                $firstname = $firstname[0] ?? "";
            } else{
                $firstname = $studentname->firstname;
            }

            $now = Carbon::parse(now());
            $date = $now->format('M d y');
            $time = $now->format('h:i a');

            $msg = "TAP IN: " . self::greetings() . " Your child, " . $firstname. ", has entered the school premises today " . $date . " at exactly " . $time;
            // greeting(morning or afternnoon)! {firstname} entered wis premises at {time} & {date}.


            $sms = SMS::where('studentnumber',$student->studentnumber)
                    ->where('deleted_at', null)
                    ->get();

            $smslog = SmsLog::where('studentnumber',$student->studentnumber)
                 ->whereDate('created_at', Carbon::today())
                 ->first();

            $smslogId = SmsLog::latest()->first();

            if($smslog == null){

                if($sms != null){
                    
                    if($sms){
                           
                        $smartJWT = new SmartJwtCredentialCrudController;

                        $request = new Request;
                        $request->request->add([
                            'subscriber_id' => $sms->pluck('subscriber_id')->toArray(),
                            'subscriber_number' => $sms->pluck('subscriber_number')->toArray(),
                            'message' => $msg,
                            'log_id' => $smslogId == null ? 1 : $smslogId->id + 1
                        ]);

                        $resp = $smartJWT->sendSms($request);

                        $resp = json_encode($resp);
                            if($resp){
                                $smslog = new SmsLog;

                                $smslog->studentnumber = $student->studentnumber;
                                $smslog->is_sent_entrance = 1;
                                $smslog->message_in = $msg;
                                $smslog->delivery_report_entrance = $resp;
                                $smslog->save();

                                $success = true;
                                $updateCountSmsSent = SMS::whereIn('subscriber_number', $sms->pluck('subscriber_number')->toArray())
                                                        ->where('studentnumber', $student->studentnumber)
                                                        ->increment('total_sms');
                                                        // ->update([
                                                        //     'total_sms' => 1
                                                        // ]);
                                \Log::info(config('seeting.schoolabbr'). " TAP IN SUCCESS -> " .$msg);

                            } else {
                                $success = false;
                            }

                    }
                }
            }
            return $success;


        } else {
            Log::critical("RFID not registered. RFID Number: " . $student->rfid);
            return false;
        }

    }

    public function sendSMSOutSmart($rfid,$schoolaabr){
        $success =  null;
        $msg = null;

        $now = \Carbon\Carbon::parse(now());
        $date = $now->format('M d y');
        $time = $now->format('h:i a');

        $student = Rfid::where('rfid', $rfid)->first();
        $studentname = Student::where('studentnumber',$student->studentnumber)
                    ->where('deleted_at', null)
                    ->first();

        if($studentname != null){
            if(str_word_count($studentname->firstname) > 1){
                $firstname = explode(' ',trim($studentname->firstname));
                
                $firstname = $firstname[0] ?? "";
            } else{
                $firstname = $studentname->firstname;
            }

            $msg = "TAP OUT: " .self::greetings() . " Your child, " . $firstname. ", has left the school premises today " . $date . " at exactly " . $time;

            $sms = SMS::where('studentnumber',$student->studentnumber)
                        ->where('deleted_at', null)
                        ->get();

            $smslog = SmsLog::where('studentnumber',$student->studentnumber)
                ->where('is_sent_entrance',1)
                ->where('is_sent_exit',null)
                ->whereDate('created_at', Carbon::today())
                ->first();

            $smslogId = SmsLog::latest()->first();

                if($smslog !== null){
                        if($sms !== null) {
                            $smartJWT = new SmartJwtCredentialCrudController;

                            $request = new Request;
                            $request->request->add([
                                'subscriber_id' => $sms->pluck('subscriber_id')->toArray(),
                                'subscriber_number' => $sms->pluck('subscriber_number')->toArray(),
                                'message' => $msg,
                                'log_id' => $smslogId == null ? 1 : $smslogId->id + 1
                            ]);

                            $resp = $smartJWT->sendSms($request);

                            $resp = json_encode($resp);
                                if($resp){

                                    $smslog->studentnumber = $student->studentnumber;
                                    $smslog->is_sent_exit = 1;
                                    $smslog->message_out = $msg;
                                    $smslog->delivery_report_exit = $resp;
                                    $smslog->save();

                                    $success = true;

                                    $updateCountSmsSent = SMS::whereIn('subscriber_number', $sms->pluck('subscriber_number')->toArray())
                                                            ->where('studentnumber', $student->studentnumber)
                                                            ->increment('total_sms');
                                                            // ->update([
                                                            //     'total_sms' => 1
                                                            // ]);
                                    \Log::info(config('seeting.schoolabbr'). " TAP OUT SUCCESS -> " .$msg);

                                } else {
                                    $success = false;
                                }

                        }
                    
                }
                return $success;
        } else {
            Log::critical("Student RFID not registered. RFID Number: " . $student->rfid);
            return false;
        }
}

    public function sendSMSBlast($request){

        $message = request()->get('message');

        $subscriber_numbers = $request->subscribers;
        $smslogId = SmsLog::latest()->first();

        $log_id = $smslogId == null ? 1 : $smslogId->id + 1;

        $subscriber_numbers[] = [
            "type" => 2,
            "id" => $log_id
        ];

        $smartJWT = new SmartJwtCredentialCrudController;

        $request = new Request;
        $request->request->add([
            'message' => $message,
            'endpoints' => $subscriber_numbers
        ]);
        $resp = $smartJWT->sendSmsBlast($request);

        return $resp;

    }

    public function greetings()
    {
        $greetings = ''; 
         /* This sets the $time variable to the current hour in the 24 hour clock format */
        $time = date("H");
        /* Set the $timezone variable to become the current timezone */
        $timezone = date("e");
        /* If the time is less than 1200 hours, show good morning */
        if ($time < "12") {
            $greetings = "Good morning!";
        } else
        /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
        if ($time >= "12" && $time < "17") {
            $greetings = "Good afternoon!";
        } else
        /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
        if ($time >= "17" && $time < "19") {
            $greetings = "Good evening!";
        } else
        /* Finally, show good night if the time is greater than or equal to 1900 hours */
        if ($time >= "19") {
            $greetings = "Good evening!";
        }

        return $greetings;
    }




    // FOR EMPLOYEE
    public function sendEmployeeSMSInSmart($rfid, $schoolaabr)
    {
        $success = False;

        $msg = null;

        
        $employee = Rfid::where('rfid', $rfid)->first();

        $employeeName = Employee::where('employee_id',$employee->studentnumber)
                        ->where('deleted_at', null)
                        ->first();
        
        if($employeeName != null){
            if(str_word_count($employeeName->firstname) > 1){
                $firstname = explode(' ',trim($employeeName->firstname));
                
                $firstname = $firstname[0] ?? "";
            } else{
                $firstname = $employeeName->firstname;
            }
            $now = Carbon::parse(now());
            $date = $now->format('M d y');
            $time = $now->format('h:i a');

            $msg = "EMPLOYEE TAP IN: " . self::greetings() . "!  " . $firstname . " " . $employeeName->lastname . ", has entered the school premises today " . $date . " at exactly " . $time;
            // greeting(morning or afternnoon)! {firstname} entered wis premises at {time} & {date}.
            $receiver = AssignTurnstileSmsReceiver::with('studentSmsTagging')->first();

            if($receiver){
                
            $sms = SMS::where('id',$receiver->student_sms_tagging_id)
                    ->where('deleted_at', null)
                    ->get();

            

            $smslog = SmsLog::where('studentnumber',$employee->studentnumber)
                 ->whereDate('created_at', Carbon::today())
                 ->first();

            $smslogId = SmsLog::latest()->first();

            
                if($smslog == null){

                    if($sms != null){
                        
                        if($sms){
                            
                            $smartJWT = new SmartJwtCredentialCrudController;

                            $request = new Request;

                            $request->request->add([
                                'subscriber_id' => $sms->pluck('subscriber_id')->toArray(),
                                'subscriber_number' => $sms->pluck('subscriber_number')->toArray(),
                                'message' => $msg,
                                'log_id' => $smslogId == null ? 1 : $smslogId->id + 1
                            ]);



                            $resp = $smartJWT->sendSms($request);
                            
                            $resp = json_encode($resp);
                                if($resp){
                                    $smslog = new SmsLog;

                                    $smslog->studentnumber = $employeeName->employee_id;
                                    $smslog->is_sent_entrance = 1;
                                    $smslog->message_in = $msg;
                                    $smslog->delivery_report_entrance = $resp;
                                    $smslog->save();

                                    $success = true;
                                    $updateCountSmsSent = SMS::whereIn('subscriber_number', $sms->pluck('subscriber_number')->toArray())
                                                            ->where('studentnumber', $employeeName->employee_id)
                                                            ->increment('total_sms');
                                                            // ->update([
                                                            //     'total_sms' => 1
                                                            // ]);
                                    \Log::info(config('seeting.schoolabbr'). " TAP IN SUCCESS -> " .$msg);

                                } else {
                                    $success = false;
                                }

                        }
                    }
                }
            }
            return $success;

        } else {
            Log::critical("Employee RFID not registered. RFID Number: " . $employee->rfid);
            return false;
        }

        
    }

    public function sendEmployeeSMSOutSmart($rfid,$schoolaabr)
    {

        $success =  null;
        $msg = null;

        $now = \Carbon\Carbon::parse(now());
        $date = $now->format('M d y');
        $time = $now->format('h:i a');

        $employee = Rfid::where('rfid', $rfid)->first();

        $employeeName = Employee::where('employee_id',$employee->studentnumber)
                        ->where('deleted_at', null)
                        ->first();


        if($employeeName != null){

            if(str_word_count($employeeName->firstname) > 1){
                $firstname = explode(' ',trim($employeeName->firstname));
                
                $firstname = $firstname[0] ?? "";
            } else{
                $firstname = $employeeName->firstname;
            }
            $msg = "EMPLOYEE TAP OUT: " . self::greetings() . "!  " . $firstname . " " . $employeeName->lastname . ", has left the school premises today " . $date . " at exactly " . $time;
       

            $receiver = AssignTurnstileSmsReceiver::with('studentSmsTagging')->first();

            if($receiver){

            $sms = SMS::where('id',$receiver->student_sms_tagging_id)
                    ->where('deleted_at', null)
                    ->get();

            $smslog = SmsLog::where('studentnumber',$employeeName->employee_id)
                ->where('is_sent_entrance',1)
                ->where('is_sent_exit',null)
                ->whereDate('created_at', Carbon::today())
                ->first();

            $smslogId = SmsLog::latest()->first();

            
                if($smslog !== null){
                        if($sms !== null) {
                            $smartJWT = new SmartJwtCredentialCrudController;

                            $request = new Request;
                            $request->request->add([
                                'subscriber_id' => $sms->pluck('subscriber_id')->toArray(),
                                'subscriber_number' => $sms->pluck('subscriber_number')->toArray(),
                                'message' => $msg,
                                'log_id' => $smslogId == null ? 1 : $smslogId->id + 1
                            ]);

                            $resp = $smartJWT->sendSms($request);

                            $resp = json_encode($resp);
                                if($resp){

                                    $smslog->studentnumber = $employee->employee_id;
                                    $smslog->is_sent_exit = 1;
                                    $smslog->message_out = $msg;
                                    $smslog->delivery_report_exit = $resp;
                                    $smslog->save();

                                    $success = true;

                                    $updateCountSmsSent = SMS::whereIn('subscriber_number', $sms->pluck('subscriber_number')->toArray())
                                                            ->where('studentnumber', $employee->employee_id)
                                                            ->increment('total_sms');
                                                            // ->update([
                                                            //     'total_sms' => 1
                                                            // ]);
                                    \Log::info(config('seeting.schoolabbr'). " TAP OUT SUCCESS -> " .$msg);

                                } else {
                                    $success = false;
                                }

                        }
                    
                }
            }
            return $success;
        } else {
            Log::critical("Employee RFID not registered. RFID Number: " . $employee->rfid);
            return false;
        }

         
    }

    public function smsDirect ($number, $message)
    {
        $smartJWT = new SmartJwtCredentialCrudController;

        $request = new Request;
        $request->request->add([
            'subscriber_id' => $sms->pluck('subscriber_id')->toArray(),
            'subscriber_number' => [$number],
            'message' => $message,
            'log_id' => uniqid()
        ]);

        $resp = $smartJWT->sendSms($request);
        $resp = json_encode($resp);
        dd($resp);
    }

}
