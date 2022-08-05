<?php

namespace App\Http\Controllers\API\Employee\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

use App\StudentCredential;
use Laravel\Passport\Client;

class RegisterController extends Controller
{

    private $client;

    public function __construct ()
    {
        $this->client = Client::find(1);
    }

    // public function register (Request $request)
    // {

    //     $this->validate($request, [
    //         'studentnumber' => 'required|unique:student_credentials,studentnumber|numeric',
    //         'password'      => 'required|min:6|confirmed',
    //     ]);

    //     $user = StudentCredential::create([
    //         'studentnumber' => request('studentnumber'),
    //         'password'      => bcrypt(request('password')),
    //     ]);

    //     $params = [
    //         'grant_type'    => 'password',
    //         'client_id'     => $this->client->id,
    //         'client_secret' => $this->client->secret,
    //         'username'      => request('studentnumber'),
    //         'password'      => request('password'),
    //         'scope'         => '*',
    //     ];

    //     $request->request->add($params);

    //     $proxy = Request::create('oauth/token', 'POST');

    //     return Route::dispatch($proxy);
    // }
}
