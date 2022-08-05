<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfStudentIsDisabled
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
        if(auth()->user()->is_disabled) {
            \Alert::warning("Kindly contact your school administrator.")->flash();
            return response()->make(view('layouts.student_disabled'));
        }

        return $next($request);
    }
}
