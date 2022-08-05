<?php

namespace App\Http\Controllers\API;

class Helper {

    private static function generateBBBChecksum($endpoint, $params) {

        return sha1($endpoint . $params . env('BBB_SECRET'));
    }

    public static function isBBBMeetingRunning($meetingId) { 
         
        $endpoint = 'isMeetingRunning';

        $params = [
            'meetingID' => $meetingId
        ];
        
        return self::processBBBRequest($endpoint, $params);
    }

    public static function joinBBBMeeting($meetingId,$fullname) { 
         
        $endpoint = 'join';

        $params = [
            'fullName' => $fullname,
            'meetingID' => $meetingId,
            'password' => "student-$meetingId"
        ];

        return self::getBBBUrl($endpoint, $params);
    }

    private static function processBBBRequest($endpoint, $params) {
        
        $url = self::getBBBUrl($endpoint, $params);

        $client = new \GuzzleHttp\Client(['verify' => false ]);
        $result = simplexml_load_string($client->get($url, [])->getBody());

        return json_decode(json_encode($result));
    }

    private static function getBBBUrl($endpoint, $params) {

        $paramsBuild = http_build_query($params);

        $checksum = self::generateBBBChecksum($endpoint, $paramsBuild);

        $url = env('BBB_SERVER_BASE_URL') . "api/$endpoint?" . $paramsBuild . "&checksum=$checksum";

        return $url;

    }

}

?>