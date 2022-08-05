<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

trait GlobeMessaging
{

    public function sendSMSIn($rfid){

          
        $success =  null;

        $msg = null;

        $student = Rfid::where('rfid', $rfid)->first();

        $studentname = Student::where('studentnumber',$student->studentnumber)->first();
        
        $firstname = explode(' ',trim($studentname->firstname));



        $now = \Carbon\Carbon::parse(now());
        $date = $now->format('M d y');
        $time = $now->format('h:i a');

        $msg = self::greetings() . " Your child, " . $firstname[0]. ", has entered ". \Config::get('settings.schoolabbr')." premises today " . $date . " at exactly " . $time;
        // greeting(morning or afternnoon)! {firstname} entered wis premises at {time} & {date}.


        $sms = SMS::where('studentnumber',$student->studentnumber)->first();
        
        $smslog = SmsLog::where('studentnumber',$student->studentnumber)
             ->whereDate('created_at', Carbon::today())
             ->first();

        if($smslog == null){

            if($sms != null){
                
                if($sms){

                        if($sms->access_token != ""){

                            $ch = curl_init();

                            curl_setopt_array($ch, array(
                                CURLOPT_RETURNTRANSFER => 1,
                                CURLOPT_URL => 'https://devapi.globelabs.com.ph/smsmessaging/v1/outbound/'.env('SMS_SC_ACCESS').'/requests?access_token='.$sms->access_token,
                                CURLOPT_POST => 1,
                                CURLOPT_POSTFIELDS => array(
                                    'address' => $sms->subscriber_number,
                                    'message' => $msg,
                                )
                            ));
                        }else{
                            // dd("Bypassed");
                            $ch = curl_init();
                            curl_setopt_array($ch, array(
                                CURLOPT_RETURNTRANSFER => 1,
                                CURLOPT_URL => 'https://devapi.globelabs.com.ph/smsmessaging/v1/outbound/'.env('SMS_SC_ACCESS').'/requests/',
                                CURLOPT_POST => 1,
                                CURLOPT_POSTFIELDS => array(
                                    'app_id' => env('SMS_APP_ID'),
                                    'app_secret' => env('SMS_APP_SECRET'),
                                    'passphrase' => env('PASSPHRASE'),
                                    'address' => $sms->subscriber_number,
                                    'message' => $msg,
                                )
                            ));
                        }

                         

                        $resp = curl_exec($ch);

                        curl_close($ch);


                        $resp = json_encode($resp);

                        if($resp){
                            $smslog = new SmsLog;

                            $smslog->studentnumber = $student->studentnumber;
                            $smslog->is_sent_entrance = 1;
                            $smslog->delivery_report_entrance = $resp;
                            $smslog->save();

                            $success = true;

                        } else {
                            $success = false;
                        }

                }
            }
        }
        return $success;

    }

    public function sendSMSOut($rfid){
        $success =  null;

        $msg = null;

        $now = \Carbon\Carbon::parse(now());
        $date = $now->format('M d y');
        $time = $now->format('h:i a');

        $student = Rfid::where('rfid', $rfid)->first();
        $studentname = Student::where('studentnumber',$student->studentnumber)->first();
        $firstname = explode(' ',trim($studentname->firstname));

        $msg = self::greetings() . " Your child, " . $firstname[0]. ", has left ". \Config::get('settings.schoolabbr')." premises today " . $date . " at exactly " . $time;

        $sms = SMS::where('studentnumber',$student->studentnumber)->first();

        $smslog = SmsLog::where('studentnumber',$student->studentnumber)
            ->where('is_sent_entrance',1)
            ->where('is_sent_exit','=',null)
            ->whereDate('created_at', Carbon::today())
            ->first();
    
        if($smslog != null){

            if($sms != null){

                // if($sms->is_registered == 1){

                    if($sms->access_token != ""){
                            $ch = curl_init();

                            curl_setopt_array($ch, array(
                                CURLOPT_RETURNTRANSFER => 1,
                                CURLOPT_URL => 'https://devapi.globelabs.com.ph/smsmessaging/v1/outbound/'.env('SMS_SC_ACCESS').'/requests?access_token='.$sms->access_token,
                                CURLOPT_POST => 1,
                                CURLOPT_POSTFIELDS => array(
                                    'address' => $sms->subscriber_number,
                                    'message' => $msg
                                )
                            ));
                        }else{

                            $ch = curl_init();
                            curl_setopt_array($ch, array(
                                CURLOPT_RETURNTRANSFER => 1,
                                CURLOPT_URL => 'https://devapi.globelabs.com.ph/smsmessaging/v1/outbound/'.env('SMS_SC_ACCESS').'/requests/',
                                CURLOPT_POST => 1,
                                CURLOPT_POSTFIELDS => array(
                                    'app_id' => env('SMS_APP_ID'),
                                    'app_secret' => env('SMS_APP_SECRET'),
                                    'passphrase' => env('PASSPHRASE'),
                                    'address' => $sms->subscriber_number,
                                    'message' => $msg
                                )
                            ));
                        }


                    $resp = curl_exec($ch);

                    curl_close($ch);

                    $resp = json_encode($resp);

                    if($resp){
                           
                            $smslog->is_sent_exit = 1;
                            $smslog->delivery_report_exit = $resp;
                            $smslog->save();

                            $success = true;

                        }
                    // }
                }
            }
            return $success;
        }
        
}
