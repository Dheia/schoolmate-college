<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentSmsTagging;
use App\Models\SmsLog;

class SmsApiController extends Controller
{
    //
    public function smstag(Request $request) 
    {
    	$msg = "no input";
    	if($request->input('code')){
    		$code = $request->input('code');

	        $app_id = env('SMS_APP_ID');
	        $app_secret = env('SMS_APP_SECRET');

	        $ch = curl_init();

	        curl_setopt_array($ch, array(
	            CURLOPT_RETURNTRANSFER => 1,
	            CURLOPT_URL => 'https://developer.globelabs.com.ph/oauth/access_token',
	            CURLOPT_POST => 1,
	            CURLOPT_POSTFIELDS => array(
	                'code' => $code,
	                'app_id' => $app_id,
	                'app_secret' => $app_secret
	            )
	        ));

	        $resp = curl_exec($ch);

	        curl_close($ch);

	        $resp = json_decode($resp);

	  		if(isset($resp->error) == false){
	  			$check = StudentSmsTagging::where("subscriber_number",$resp->subscriber_number)->get()->count();
	  				if($check <= 0){
	  					$smstag = new StudentSmsTagging;
				        $smstag->access_token = $resp->access_token;
				        $smstag->subscriber_number = $resp->subscriber_number;
				        $smstag->is_registered = 1;

				        if($smstag->save()) {
				            $msg = "Successfully Subscribe";
				        }
	  				}else
	  				{
	  					$msg = "Already Subscribe";
	  				}	
		  			
	  		}
	  		else{
	  			$msg = $resp->error;
	  		}
    	}elseif($request->input('access_token') && $request->input('subscriber_number')) {
    		$check = StudentSmsTagging::where("subscriber_number",$request->input('subscriber_number'))->get()->count();
	  				if($check <= 0){
	  					$smstag = new StudentSmsTagging;
				        $smstag->access_token = $request->input('access_token');
				        $smstag->subscriber_number = $request->input('subscriber_number');
				        $smstag->is_registered = 1;

				        if($smstag->save()) {
				            $msg = "Successfully Subscribe";
				        }
	  				}else
	  				{
	  					$msg = "Already Subscribe";
	  				}	
    	}
        
  	
  		return $msg;

    }  

    public function smspost(Request $request) 
    {
		$resp = json_decode($request->getContent());        
		// dd($resp->unsubscribed->subscriber_number);
        // if($resp->unsubscribed){
        $smstag = StudentSmsTagging::where('subscriber_number', $resp->unsubscribed->subscriber_number)->first();
        // dd($smstag);
        if($smstag) {
        	$smstag->delete();
        }
        // $smstag->is_registered = 0;
        // $smstag->save();
        // }
    }
}
