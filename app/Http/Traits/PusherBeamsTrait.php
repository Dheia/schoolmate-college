<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Pusher\PushNotifications\PushNotifications;

use Log;

trait PusherBeamsTrait
{
    public $beamsClient;

    // public function __construct()
    // {
       
    //     $this->beamsClient;
       
    //     $this->beamsClient = new PushNotifications(array(
    //         "instanceId" => env("BEAMS_INSTANCE_ID"),
    //         "secretKey" => env("BEAMS_SECRET_KEY"),
    //     ));
 
    // }

    /**
     * Set Config (Instance Id and Secrtet Key)
     */
    public function setConfig()
    {
        $this->beamsClient = new PushNotifications(array(
            "instanceId" => env("BEAMS_INSTANCE_ID"),
            "secretKey" => env("BEAMS_SECRET_KEY"),
        ));
    }
    
    /**
     * Get Beams Token
     */
    public function getBeamsToken($user_id)
    {
        $this->setConfig();
        
        $response   = $this->beamsClient->generateToken(strval($user_id));
 
        return $response;
    }
    
    /**
     * Publish To Interests
     * $interests
     * [ mob-schoolid-student, mob-schoolid-student-studentnumber ]
     * [ web-schoolid-employee, web-schoolid-student, web-schoolid-parent ]
     * [ web-schoolid-employee-employeeid, web-schoolid-student-studentnumber, web-schoolid-parent-parentid ]
     */
    public function publishToInterests($interests, $notification, $data)
    {
        $this->setConfig();

        /****** Sample Notification Request
         * 
            "notification" => [
                "title"       => $title,
                "body"        => $message,
                "deep_link"   => $url
            ]
         *
         ******/
        $publishFcmRequest = [
            "fcm" => [
                "notification" => $notification,
                "data"         => $data
            ],
            "apns" => [
                "aps" => [
                    "alert" => $notification,
                    "data"  => $data
                ]
            ],
            "web" => [
                "notification" => $notification,
                "data"         => $data
            ],
        ];

        $publishResponse = $this->beamsClient->publishToInterests($interests, $publishFcmRequest);
        return $publishResponse;
    }
    
    /**
     * Publish To Interests
     * $interests
     * [ mob-schoolid-student, mob-schoolid-student-studentnumber ]
     * [ web-schoolid-employee, web-schoolid-student, web-schoolid-parent ]
     * [ web-schoolid-employee-employeeid, web-schoolid-student-studentnumber, web-schoolid-parent-parentid ]
     */
    public function publishToUsers($users, $notification, $data)
    {
        $this->setConfig();

        /****** Sample Notification Request
         * 
            "notification" => [
                "title"       => $title,
                "body"        => $message,
                "deep_link"   => $url
            ]
         *
         ******/
        $publishFcmRequest = [
            "fcm" => [
                "notification" => $notification,
                "data"         => $data
            ],
            "apns" => [
                "aps" => [
                    "alert" => $notification,
                    "data"  => $data
                ]
            ],
            "web" => [
                "notification" => $notification,
                "data"         => $data
            ],
        ];

        $publishResponse = $this->beamsClient->publishToUsers($users, $publishFcmRequest);
        return $publishResponse;
    }
}