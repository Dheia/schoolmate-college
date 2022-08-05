<?php

namespace App\Http\Controllers\API\Parent\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Laravel\Passport\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

use App\ParentCredential;

class LoginController extends Controller
{

    private $client;

    public function __construct ()
    {
        $this->client = Client::find(1);
    }

    public function refresh (Request $request)
    {
        $this->validate($request, [
            'refresh_token' => 'required'
        ]);

        $params = [
            'grant_type'    => 'refresh_token',
            'client_id'     => $this->client->id,
            'client_secret' => $this->client->secret,
            'username'      => request('username'),
            'password'      => request('password'),
            'scope'         => '*',
        ];

        $request->request->add($params);

        $proxy = Request::create('oauth/token', 'POST');

        return Route::dispatch($proxy);
    }

    public function logout (Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'error' => false,
            'message' => 'Successfully logged out'
        ]);

        return response()->json([], 204);
    }


    public function login(Request $request)
    { 
        $user = ParentCredential::where('username', $request->username)->orWhere('email', $request->email)->first();

        $response = [
            'error' => true,
            'access_token' => null,
            'messsage' => null,
        ];

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;

                $response['error'] = false;
                $response['access_token'] = $token;

                return response($response, 200);
            } else {
                $response['message'] = "User or Password Mismatch";
                return response($response, 422);
            }

        } else {
            $reponse['message'] = 'User does not exist';
            return response($response, 422);
        }
    }
}