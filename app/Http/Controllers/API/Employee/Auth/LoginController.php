<?php

namespace App\Http\Controllers\API\Employee\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Laravel\Passport\Client;
use Carbon\Carbon;

use App\Models\Role;

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
            'message' => 'Successfully logged out'
        ]);

        return response()->json([], 204);
    }


    public function login(Request $request)
    { 

        $this->validate($request, [
            'email'     => 'required|email|exists:users,email',
            'password'  => 'required',
        ]);

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $modelHasRoles = \DB::table('model_has_roles')->where('model_id', $user->id)->get();
            $roles_id = $modelHasRoles->pluck('role_id')->toArray();
            $roles = Role::whereIn('id', $roles_id)->get();

            if( collect($roles)->contains('name', 'Accounting') ) {
                
                $tokenResult =  $user->createToken('Laravel Password Grant Employee');
                $token       = $tokenResult->token;
                $token->save();

                return response()->json([
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => $tokenResult->token->expires_at->diffInSeconds(Carbon::now())
                ]);
            } 
            return response()->json(['error'=>'Unauthorized', 'message' => 'You Are Not Accountant User'], 401); 
        } 
        else { 
            return response()->json(['error'=>'Incorrect email or password.'], 401); 
        } 
    }
}