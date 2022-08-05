<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\MessageBag;

class StudentLoginController extends Controller
{

    public function __construct()
    {
      $this->middleware('guest:student')->except('logout');
    }

    public function showLoginForm()
    {
      // return view('auth.login');
      $title = "Login";
       return view('auth.new_login')->with('title', $title);
    }

    public function login(Request $request)
    {
      $errors = new MessageBag;

      $this->validate($request, [
        'studentnumber' => 'required',
        'password'      => 'required'
      ]);

      // Attempt to log the user in
      if (Auth::guard('student')->attempt(['studentnumber' => $request->studentnumber, 'password' => $request->password], $request->remember_token)) 
      { 
        // if successful, then redirect to their intended location
        return redirect()->to(url()->current());
      }
      $errors->add('studentnumber', 'These credentials do not match our records.');
            

      // if unsuccessful, then redirect back to the login with the form data
      return redirect()->back()->withErrors($errors)->withInput($request->only('studentnumber'));
    }

    public function redirect ()
    {
      return redirect('student/login');
    }

    //
    public function logout(Request $request) 
    {
      Auth::guard('student')->logout();
      return redirect('login');
    }
}
