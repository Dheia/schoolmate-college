<?php

namespace App\Listeners;

use Illuminate\Http\Request;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Logout;

use Carbon\Carbon;
use App\Models\AuthenticationLog;

class LogSuccessfulLogout
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
    public function handle(Logout $event)
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

        // Check If Has Not Currently Logged In Logs 
        AuthenticationLog::whereDate('created_at', Carbon::today())
                         ->where('user_id', $user->id)
                         ->where('user_type', $userType)
                         ->where('login_at', '!=', null)
                         ->where('logout_at', null)
                         ->latest()
                         ->update(['logout_at' => Carbon::now()]);
    }
}
