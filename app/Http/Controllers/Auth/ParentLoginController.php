<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\MessageBag;

use App\Models\ParentUser;
use App\ParentCredential;

class ParentLoginController extends Controller
{
  public function __construct()
  {
    $this->middleware('guest:parent')->except('logout');
  }

  public function showLoginForm()
  {
    // return view('auth.login');
    $title = "Login";
     return view('auth.parent_login')->with('title', $title);
  }

  public function showRegistrationForm()
  {
    // return view('auth.login');
    $title = "Parent Register";
    return view('auth.parent_register')->with('title', $title);
  }

  public function login(Request $request)
  {
    $errors = new MessageBag;

    $this->validate($request, [
      'email' => 'required',
      'password'      => 'required'
    ]);

    // Attempt to log the user in
    if (Auth::guard('parent')->attempt(['email' => $request->email, 'password' => $request->password, 'active' => 1], $request->remember_token)) 
    { 
      // if successful, then redirect to their intended location
      return redirect()->to(url()->current());
    }
    $errors->add('email', 'These credentials do not match our records.');
          

    // if unsuccessful, then redirect back to the login with the form data
    return redirect()->back()->withErrors($errors)->withInput($request->only('email'));
  }

  public function redirect ()
  {
    return redirect('parent/login');
  }

  //
  public function logout(Request $request) 
  {
    Auth::guard('parent')->logout();
    return redirect('parent/login');
  }

  public function register(Request $request)
  {
    $errors = new MessageBag;

    $this->validate($request, [
      'firstname'   =>  'required|string',
      'middlename'  =>  'nullable|string',
      'lastname'    =>  'required|string',
      'gender'      =>  'required|in:Male,Female',
      'birthdate'   =>  'required|date',
      'mobile'      =>  'required|digits:11',
      'telephone'   =>  'nullable|numeric',
      'email'       =>  'required|unique:parent_users,email',
      'password'    =>  'required|min:6|confirmed',
    ]);

   $parent          =   ParentUser::create([
                          'firstname'     =>  $request->firstname,
                          'lastname'      =>  $request->lastname,
                          'gender'        =>  $request->gender,
                          'birthdate'     =>  $request->birthdate,
                          'mobile'        =>  $request->mobile,
                          'telephone'     =>  $request->telephone,
                          'email'         =>  $request->email,
                          'verified'      =>  0
                        ]);

    $username       =   strtolower(substr($request->firstname, 0, 1) . $request->lastname);
    $password       =   bcrypt($request->password);

    $credential     =   ParentCredential::create([
                          'parent_user_id' =>   $parent->id,
                          'fullname'       =>   $parent->fullname,
                          'username'       =>   $parent->email,
                          'email'          =>   $parent->email,
                          'password'       =>   $password,
                          'active'         =>   0
                        ]);
    \Alert::success("Successfully Register")->flash();
    return redirect()->back()->with('success', 'Regristration sent!');
  }

}
