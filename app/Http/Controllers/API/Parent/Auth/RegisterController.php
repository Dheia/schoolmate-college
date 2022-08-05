<?php

namespace App\Http\Controllers\API\Parent\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

use App\ParentCredential;
use Laravel\Passport\Client;

class RegisterController extends Controller
{

    private $client;

    public function __construct ()
    {
        $this->client = Client::where('password_client', 1)->first();
    }

    public function register (Request $request)
    {
        $this->validate($request, [
            'fullname'  => 'required|string|min:5|max:50',
            'username'  => 'required|string|min:5|max:50|unique:parent_credentials,username',
            'email'     => 'required|email|unique:parent_credentials,email',
            'password'  => 'required|min:6|confirmed',
        ]);

        $user = ParentCredential::create([
            'fullname'  => $request->fullname,
            'username'  => $request->username,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
        ]);

        $accessToken = $user->createToken('Token Parent')->accessToken;

        return response()->json(['error' => false, 'access_token' => $accessToken, 'message' => null]);
    }
}
