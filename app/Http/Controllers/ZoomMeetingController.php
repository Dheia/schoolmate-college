<?php

namespace App\Http\Controllers;

use App\Traits\ZoomMeetingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use GuzzleHttp\Client;

use App\Models\ZoomUser;
use App\Models\ZoomMeeting;
use App\Models\ZoomRecording;

// Jobs
use App\Jobs\StartOnlineClassJob;
use App\Jobs\EndOnlineClassJob;

class ZoomMeetingController extends Controller
{

    public $client;
    public $jwt;
    public $headers;

    public function __construct()
    {
        $this->client = new Client();
        $this->jwt = $this->generateZoomToken();
        $this->headers = [
            'Authorization' => 'Bearer '.$this->jwt,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ];
    }

    public function generateZoomToken()
    {
        $key = env('ZOOM_API_KEY', '');
        $secret = env('ZOOM_API_SECRET', '');
        $payload = [
            'iss' => $key,
            'exp' => strtotime('+1 minute'),
        ];

        return \Firebase\JWT\JWT::encode($payload, $secret, 'HS256');
    }

    private function retrieveZoomUrl()
    {
        return env('ZOOM_API_URL', '');
    }

    public function toZoomTimeFormat(string $dateTime)
    {
        try {
            $date = new \DateTime($dateTime);

            return $date->format('Y-m-d\TH:i:s');
        } catch (\Exception $e) {
            Log::error('ZoomJWT->toZoomTimeFormat : '.$e->getMessage());

            return '';
        }
    }

    public function createMeeting($data)
    {
        $url    = $this->retrieveZoomUrl();

        $code   = $data['code'];
        $topic  = $data['topic'];
        $agenda = $data['agenda'];
        $pass   = $data['password'];
        $host   = $data['host_email'] ?? 'me';

        $path   = 'users/' . $host . '/meetings';

        // $update_user = self::updateUserDetails($host, $data['firstname'], $data['lastname']);

        $body   = [
            'headers' => $this->headers,
            'body'    => json_encode([
                'topic'      => $topic,
                'type'       => 1,
                'start_time' => $this->toZoomTimeFormat(now()),
                'duration'   => 1,
                "password"   => $pass, //ClassCode
                'agenda'     => $agenda, //Subject | Class
                'timezone'     => 'Asia/Manila',
                'settings'   => [
                    'host_video'        => true,
                    'participant_video' => false,
                    'waiting_room'      => true,
                    'meeting_authentication' => true
                ],
            ]),
        ];

        $response =  $this->client->post($url.$path, $body);

        return [
            'success' => $response->getStatusCode() === 201,
            'data'    => json_decode($response->getBody(), true),
        ];
    }


    public function createUser()
    {
        $url = $this->retrieveZoomUrl();
        $path = 'users/';


        $body = [
            'headers' => $this->headers,
            'body'  => json_encode([
                "action" => "create",
                "user_info" => [
                  "email" => "mnicdao01@gmail.com",
                  "type" => 1,
                  "first_name" => "Mark",
                  "last_name" => "Nicdao"
                ]
            ])
        ];

        $response =  $this->client->post($url.$path, $body);

        return [
            'success' => $response->getStatusCode() === 201,
            'data'    => json_decode($response->getBody(), true),
        ];
    }

    public function listUsers()
    {
        $url = $this->retrieveZoomUrl();
        $path = 'users/';


        $body = [
            'headers' => $this->headers,
        ];

        $response =  $this->client->get($url.$path, $body);

        return [
            'success' => $response->getStatusCode() === 201,
            'data'    => json_decode($response->getBody(), true),
        ];
    }

    public function updateUserDetails($userid, $firstname, $lastname)
    {
        $url    = $this->retrieveZoomUrl();

        $path   = 'users/' . $userid;

        $body = [
            'headers' => $this->headers,
            'body'    => json_encode([
                'firstname'  => 'Macky',
                'lastname'   => 'Tira',
            ]),
        ];

        $response =  $this->client->patch($url.$path, $body);

        return [
            'success' => $response->getStatusCode() === 204,
            'data'    => json_decode($response->getBody(), true),
        ];
    }

    public function getCloudRecordings($meeting_id)
    {
        $url = $this->retrieveZoomUrl();
        $path = 'meetings/' . $meeting_id . '/recordings';

        $body = [
            'headers' => $this->headers,
        ];

        $response =  $this->client->get($url.$path, $body);

        return [
            'success' => $response->getStatusCode() === 200,
            'data'    => json_decode($response->getBody(), true),
        ];
    }

    public function webhooks(Request $request)
    {
        $data = $request->all();
        try {
            $event   = $data['event'];
            $payload = $data['payload'];
            $uuid    = $payload['object']['uuid'];
            $host_id = $payload['object']['host_id'];
            $zoom_id = $payload['object']['id'];

            $zoom_meeting = ZoomMeeting::where('zoom_uuid', $uuid)
                                ->where('zoom_host_id', $host_id)
                                ->where('zoom_id', $zoom_id)
                                ->first();

            if($zoom_meeting) {
                $status    = null;
                $zoom_user = $zoom_meeting->zoomUser;

                switch($event) {

                    /* Meeting Created */
                    case 'meeting.created':
                        $status = 'created';
                        $zoom_meeting->update([
                            'status'  => 'created'
                        ]);

                        /* Update Meeting Host ID & Status */
                        $meeting          = $zoom_meeting->meetingable;
                        $meeting->zoom_id = $zoom_id;
                        $meeting->status  = $status;
                        $meeting->save();

                        \Log::info([
                            'TITLE'     => 'Zoom Meeting Created Webhook', 
                            'ZOOM ID '  => isset($data['payload']['object']['id']) ? $data['payload']['object']['id'] : $data,
                            'REQUEST'   => $data
                        ]);

                        break;

                    /* Meeting Started */
                    case 'meeting.started':
                        $status = 'started';
                        $zoom_meeting->update([
                            'status'  => 'started',
                            'start_time' => $payload['object']['start_time']
                        ]);
                        $zoom_user->update([
                            'active'  => $zoom_user->active + 1,
                        ]);

                        /* Update Meeting Host ID & Status */
                        $meeting          = $zoom_meeting->meetingable;
                        $meeting->zoom_id = $zoom_id;
                        $meeting->status  = $status;
                        $meeting->save();

                        /* Zoom Meeting Is Online Class */
                        if($zoom_meeting->meetingable_type === "App\Models\OnlineClass") {
                            StartOnlineClassJob::dispatch($zoom_meeting);
                        }

                        \Log::info([
                            'TITLE'     => 'Zoom Meeting Started Webhook', 
                            'ZOOM ID '  => isset($data['payload']['object']['id']) ? $data['payload']['object']['id'] : $data,
                            'REQUEST'   => $data
                        ]);

                        break;
                    
                    /* Meeting Ended */
                    case 'meeting.ended':
                        $status  = 'ended';
                        $zoom_id = null;
                        $zoom_meeting->update([
                            'status'  => 'ended',
                            'start_time' => $payload['object']['start_time'],
                            'end_time' => $payload['object']['end_time']
                        ]);
                        $zoom_user->update([
                            'active'  => $zoom_user->active > 0 ? $zoom_user->active - 1 : 0,
                        ]);
                        
                        /* Update Meeting Host ID & Status */
                        $meeting          = $zoom_meeting->meetingable;
                        $meeting->zoom_id = $zoom_id;
                        $meeting->status  = $status;
                        $meeting->save();

                        /* Zoom Meeting Is Online Class */
                        if($zoom_meeting->meetingable_type === "App\Models\OnlineClass") {
                            EndOnlineClassJob::dispatch($zoom_meeting);
                        }

                        \Log::info([
                            'TITLE'     => 'Zoom Meeting Ended Webhook', 
                            'ZOOM ID '  => isset($data['payload']['object']['id']) ? $data['payload']['object']['id'] : $data,
                            'REQUEST'   => $data
                        ]);

                        break;

                     /* Recording Completed */
                     case 'recording.completed':
                        $recording_status = 'completed';

                        /* Save Zoom Recording */
                        $zoom_recording =   ZoomRecording::create([
                                                'meetingable_id'    => $zoom_meeting->meetingable_id,
                                                'meetingable_type'  => $zoom_meeting->meetingable_type,
                                                'zoom_id'      => $zoom_id,
                                                'zoom_uuid'    => $uuid,
                                                'zoom_host_id' => $host_id,
                                                'duration'  => $payload['object']['duration'],
                                                'share_url' => $payload['object']['share_url'],
                                                'recording_files' => json_encode($payload['object']['recording_files']),
                                                'status' => $recording_status
                                            ]);
                        
                        /* Get Cloud Recordings and Save Recording Password */
                        $cloud_recordings = self::getCloudRecordings($zoom_id);
                        if($cloud_recordings['success']) {
                            $zoom_recording =   ZoomRecording::where('zoom_id', $zoom_id)
                                                    ->where('zoom_host_id', $host_id)
                                                    ->where('zoom_id', $zoom_id)
                                                    ->where('zoom_uuid', $uuid)
                                                    ->first();
                            $zoom_recording->password = $cloud_recordings['data']['password'];
                            $zoom_recording->save();
                        }

                        \Log::info([
                            'TITLE'     => 'Zoom Recording Completed Webhook', 
                            'ZOOM ID '  => isset($data['payload']['object']['id']) ? $data['payload']['object']['id'] : $data,
                            'REQUEST'   => $data
                        ]);

                        break;

                    /* Recording Deleted */
                    case 'recording.deleted':
                        $recording_status = 'deleted';

                        /* Update Zoom Recording Status Then Delete */
                        $zoom_recording =   ZoomRecording::where('meetingable_type', $zoom_meeting->meetingable_type)
                                                ->where('meetingable_id', $zoom_meeting->meetingable_id)
                                                ->where('zoom_host_id', $host_id)
                                                ->where('zoom_id', $zoom_id)
                                                ->where('zoom_uuid', $uuid)
                                                ->first();
                        $zoom_recording->status = $recording_status;
                        $zoom_recording->save();
                        $zoom_recording->delete();

                        \Log::info([
                            'TITLE'     => 'Zoom Recording Deleted Webhook', 
                            'ZOOM ID '  => isset($data['payload']['object']['id']) ? $data['payload']['object']['id'] : $data,
                            'REQUEST'   => $data
                        ]);

                        break;
                    
                    default:
                        break;
                }

                // \Log::info([
                //     'TITLE'     => 'Zoom Webhook', 
                //     'ZOOM ID '  => isset($data['payload']['object']['id']) ? $data['payload']['object']['id'] : $data,
                //     'EVENT'     => $event
                // ]);

            }
            // \Log::info([
            //     'TITLE'     => 'Zoom Webhook', 
            //     'ZOOM ID '  => isset($data['payload']['object']['id']) ? $data['payload']['object']['id'] : $data,
            //     'REQUEST'   => $data
            // ]);
        }
        catch (\Exception $e) {
            \Log::info([
                'TITLE'     => 'Zoom Webhook Error', 
                'ERROR: '   => $e,
                'ZOOM ID '  => isset($data['payload']['object']['id']) ? $data['payload']['object']['id'] : null,
                'REQUEST'   => $data
            ]);
        }
    }
}