<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TurnstileLog;
use App\Models\Employee;
use App\Models\Rfid;

use Carbon\Carbon;
use Auth;

class OnlineAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('onlineAttendance.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function postAttendance (Request $request)
    {
    	$now = now();
    	if (Auth::attempt(['email' => $request->username, 'password' => $request->password])) {

    		if($request->has('login') || $request->has('logout')) {

		    	$employee = Employee::where('id', auth()->user()->employee_id)->first();
		    	if(!$employee) {
                    \Session::flash('message', 'This User Is Not Yet Tag'); 
		    		return redirect()->back()->withInput();
		    	}

		    	$rfid = Rfid::where('studentnumber', $employee->employee_id)->where('user_type', 'employee')->first();
		    	if(!$rfid) {
                    \Session::flash('message', 'No Rfid Found'); 
		    		return redirect()->back()->withInput();
		    	}

	    		if(auth()->user()->employee_id) {
                    $data = [
                        'rfid' => $rfid->rfid,
                        'turnstile' => 'ONLINE-' . $request->ip(),
                    ];

                    $turnstileLog = TurnstileLog::where('rfid', $rfid->rfid)->whereDate('created_at', Carbon::today())->latest()->first();

                    if($turnstileLog)
                    {
                        if($turnstileLog->is_logged_in == 1 && $request->has('login') && $turnstileLog->timeout == null) {
                            \Session::flash('message', 'Your last logged-in is : ' . Carbon::parse($turnstileLog->timein)->format('h:i:s a')); 
                            return redirect()->back()->withInput();
                        }

                        if($turnstileLog->is_logged_in == 0 && $request->has('logout')) {
                            \Session::flash('message', 'Your last logged-out is ' . Carbon::parse($turnstileLog->timeout)->format('h:i:s a')); 
                            return redirect()->back()->withInput();
                        }
                    }

                    if(!$turnstileLog && $request->has('logout')) {
                        \Session::flash('message', 'You must to login first'); 
                        return redirect()->back()->withInput();
                    }

                    $data['in'] = $request->has('login') ? 'in' : 'out'; 
                    $message    = $request->has('login') ? 'Time in : ' . now()->format('h:i:s a') : 'Time out : ' . now()->format('h:i:s a');
                    $request->request->add($data);
                    $trigger    = $request->create('trigger', 'GET');
                    
                    \Route::dispatch($trigger);

                    \Session::flash('message', $message); 
                    return redirect()->back()->withInput();
	    		} else {
                    \Session::flash('message', 'Error, Something Went Wrong!'); 
	    			return redirect()->back()->withInput();
	    		}
    		}

    	} else {
            \Session::flash('message', 'Invalid Email or Password'); 
            return redirect()->back()->withInput();
        }
    	return redirect()->back()->withInput();
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
