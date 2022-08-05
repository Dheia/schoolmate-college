<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\SmartJwtCredential;
use Carbon\Carbon;
use App\Http\Controllers\RestCurl;
class RefreshSmartToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:refreshsmarttoken';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh Smart Token';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $smartJwt = SmartJwtCredential::first();
        //  If NULL Run Authorize
        if($smartJwt == null) {
            $resp = self::authorize();
            return $resp ? true : $resp;
        }
        $accessToken                = $smartJwt->access_token;
        $refreshToken               = $smartJwt->refresh_token;
        $smartAccessTokenExpiresIn  = $smartJwt->updated_at;
        $smartRefreshTokenExpiresIn = $smartJwt->created_at;
        $accessTokenMinutesPass     = $smartAccessTokenExpiresIn->diffInMinutes(Carbon::now());
        $refreshTokenHoursPass      = $smartRefreshTokenExpiresIn->diffInHours(Carbon::now());
        //Check If $accessTokenMinutesPass Is ALready 30mins. Then, Renew JWT Access Token.
        if($accessTokenMinutesPass > 29) {
            
            // Check if $refreshTokenHoursPass Is Already Pass 8 hours If Not Expired Then, Update The Access Token Value
            if($refreshTokenHoursPass < 8) {
                SmartJwtCredential::truncate();
                $resp = self::authorize();
            }
            else { 
                // If $refreshTokenHoursPass Exceeded To The Time Given. Then, Recreate New Access Token. 
                SmartJwtCredential::where('refresh_token', $refreshToken)->delete();
                $resp = self::authorize();
                return $resp ? true : $resp;
            }
        }
    }
    private function authorize ()
    {
        $url    = 'https://messagingsuite.smart.com.ph/rest/auth/login';
        $header = array('Content-Type: application/json');
        $fields = array('username' => env('SMART_MESSAGING_USERNAME'), 'password' => env('SMART_MESSAGING_PASSWORD'));
        $fields = json_encode($fields);
        $resp   = RestCurl::post($url, $header, $fields);
        $data   = $resp->data;
        // Save Response To Database
        $credentials = SmartJwtCredential::create([
            'access_token'  => $data->accessToken,
            'expires_in'    => $data->expiresIn,
            'token_type'    => $data->tokenType,
            'refresh_token' => $data->refreshToken,
            'grant_type'    => $data->grantType,
        ]);
        return $credentials ? true : $resp;
    }
    /**
     * [refreshToken Renew The Access Token.]
     * @param  string $accessToken [Access Token Must Provide Of The Current Token.]
     * @return type]              [description]
     */
    private function refreshToken ($accessToken, $refreshToken)
    {
        $url    = 'https://messagingsuite.smart.com.ph/rest/auth/refresh';
        $header = array('Authorization: Bearer ' . $accessToken, 'Content-Type: application/json');
        $fields = array('refreshToken' => $refreshToken);
        $fields = json_encode($fields);
        $resp   = RestCurl::put($url, $header, $fields);
        $data   = $resp->data;
        if(isset($data->statusCode) && $data->statusCode === 401) { 
            SmartJwtCredential::where('refresh_token', $refreshToken)->delete();
            $resp = self::authorize();
            return $resp ? true : $resp;
        }
        $smartJwt = SmartJwtCredential::where('access_token', $accessToken)
                    ->update([
                        'access_token'  => $data->accessToken,
                        'expires_in'    => $data->expiresIn,
                        'token_type'    => $data->tokenType,
                        'updated_at'    => \Carbon\Carbon::now()
                    ]);
        return $smartJwt ? true : false;
    }
    /**
     * [curl Requesting Endpoints For Smart JWT]
     * @param  string $url    [ set the URL endpoint  ]
     * @param  array  $header [ set the header ]
     * @param  array  $fields [ set value for posts ]
     * @param  method $method [ the default value of method is posts, you can change it if you want to custom method ]
     * @return string         [ it will return depends on the request URL endpoint will be given. ]
     */
    private function curl ($url = null, $header = [], $fields = [], $method = 'POSTS')
    {
        $ch = curl_init();
        $curl_options = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL            => $url,
            CURLOPT_HTTPHEADER     => $header,
            CURLOPT_HEADER         => TRUE,
            CURLOPT_SSL_VERIFYPEER => TRUE,
        );
        $method = strtoupper($method);
        if($method == "GET") {
            $url .= '?' . http_build_query($obj);
        }
        else if ($method == 'POSTS') { 
            $curl_options[CURLOPT_POST]          = 1; 
            $curl_options[CURLOPT_POSTFIELDS]    = $fields; 
        } 
        else { 
            $curl_options[CURLOPT_CUSTOMREQUEST] = $method; 
            $curl_options[CURLOPT_POSTFIELDS]    = $fields; 
        }
        // dd($curl_options);
        curl_setopt_array($ch, $curl_options);
        $resp = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        
        // Data
        $header = trim(substr($resp, 0, $info['header_size']));
        $body = substr($resp, $info['header_size']);
        return (object)array('status' => $info['http_code'], 'header' => $header, 'data' => json_decode($body));
    }
}