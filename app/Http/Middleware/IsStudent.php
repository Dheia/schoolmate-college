<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class IsStudent
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
        $isStudent = Student::where('studentnumber', $request->student_number)->get();

        if(Auth::check() && count($isStudent) > 0) {
            return redirect('dashboard');
        }

        return $next($request);
    }
}
