<?php

namespace App\Http\Controllers\API\Student\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Laravel\Passport\Client;

use Carbon\Carbon;

use App\Http\Controllers\API\Student\Auth\StudentController;

use App\StudentCredential;

class LoginController extends Controller
{

    private $client;

    public function __construct ()
    {
        $this->client = Client::where('password_client', 1)->first();
    }

    public function login (Request $request)
    {
        $this->validate($request, [
            'studentnumber'  => 'required',
            'password'       => 'required',
        ]);
        $params = [
            'grant_type'    => 'password',
            'client_id'     => $this->client->id,
            'client_secret' => $this->client->secret,
            'username'      => request('studentnumber'),
            'password'      => request('password'),
            'scope'         => '*',
        ];

        $request->request->add($params);

        $studentCredential  = StudentCredential::where('studentnumber', $request->studentnumber)->first();
        $isFirstTimeLogin   = $studentCredential->is_first_time_login ?? true;

        $proxy = Request::create('oauth/token', 'POST');
        $token = Route::dispatch($proxy);
        $token = json_decode($token->getContent());

        if(isset($token->error)) {
            return response()->json($token);
        }

        $expiration = Carbon::now()->addSeconds($token->expires_in)->format('Y-m-d h:i:s');

        $array = [
            'id'         => $studentCredential->id, 
            'token_type' => $token->token_type, 
            'expires_in' => $expiration, 
            'access_token' => $token->access_token, 
            'refresh_token' => $token->refresh_token,
            'is_first_time_login' => $isFirstTimeLogin ? true : false
        ];

        return response()->json($array);
    }

    

    public function refresh (Request $request)
    {
        $this->validate($request, [
            'refresh_token' => 'required'
        ]);

        $params = [
            'grant_type'    => 'refresh_token',
            'refresh_token' => request('refresh_token'),
            'client_id'     => $this->client->id,
            'client_secret' => $this->client->secret,
            'scope'         => '*',
            // 'username'      => request('username'),
            // 'password'      => request('password'),
        ];

        $request->request->add($params);

        $proxy = Request::create('oauth/token', 'POST');

        return Route::dispatch($proxy);
    }

    public function logout (Request $request)
    {
        $accessToken = Auth::user()->token();
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update(['revoked' => true]);

        $accessToken->revoke();

        return response()->json([], 204);
    }
}