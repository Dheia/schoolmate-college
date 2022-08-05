<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Student;
use App\Models\ParentStudent;

class StudentOfParent
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
        $parent         =   auth()->user()->parent;
        $studentnumber  =   $request->route('studentnumber');
        if(! $studentnumber) {
            abort(404, 'Student not found.');
        }

        $student        =   Student::where('studentnumber', $studentnumber)->first();
        if(! $student) {
            abort(404, 'Student not found.');
        }

        $parentStudent  =   ParentStudent::where('parent_user_id', $parent->id)->where('student_id', $student->id)->first();
        if(! $parentStudent) {
            abort(401);
        }

        return $next($request);
    }
}
