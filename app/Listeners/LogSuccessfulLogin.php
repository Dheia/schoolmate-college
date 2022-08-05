<?php

namespace App\Listeners;

use Illuminate\Http\Request;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;

use Carbon\Carbon;
use App\Models\AuthenticationLog;

class LogSuccessfulLogin
{
    private $request;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user      = $event->user;
        $ipAddress = $this->request->ip();
        $userAgent = $this->request->userAgent();

        $userType  = 'App\User';

        if($event->guard == 'student')
        {
            if($user->student)
            {
                $userType   = 'App\Models\Student';
                $user       = $user->student;
            }
            else
            {
                $userType  = 'App\StudentCredential';
            }
        }
        else if($event->guard == 'parent')
         {
            if($user->parent)
            {
                $userType   = 'App\Models\ParentUser';
                $user       = $user->parent;
            }
            else
            {
                $userType  = 'App\ParentCredential';
            }
        }

        $logModel = AuthenticationLog::whereDate('created_at', Carbon::today())
                                     ->where('user_id', $user->id)
                                     ->where('user_type', $userType)
                                     ->latest()
                                     ->first();

        // Check If Has Not Currently Logged In Logs 
        if($logModel === null) {
            $log = new AuthenticationLog;
            $log->user_id = $user->id;
            $log->user_type = $userType;
            $log->login_at = Carbon::now();
            $log->save();
            return false;
        }
        
        // Check If Has Curreny LoggedIn Logs But Completed Fill The Time-in & Time-out
        if($logModel->login_at !== null && $logModel->logout_at !== null) {
            $log = new AuthenticationLog;
            $log->user_id   = $user->id;
            $log->user_type = $userType;
            $log->login_at  = Carbon::now();
            $log->save();
            return false;
        }


    }
}
