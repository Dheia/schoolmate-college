<?php

namespace App\Http\Middleware;

use Closure;

class StudentFirstTimeLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->is_first_time_login) {
            \Alert::warning("Please You Need To Change Your Password For First Time Login")->flash();
            return redirect('student/change-password');
        }

        return $next($request);
    }
}
