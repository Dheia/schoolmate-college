<?php

namespace App\Http\Middleware;

// Models
use App\Models\Student;
use App\Models\SchoolYear;
use App\Models\OnlineClass;
use App\Models\StudentSectionAssignment;

use Closure;

class CheckStudentClass
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
        $class_code = $request->class_code;

        $student        =   request()->user()->student;
        $schoolYear     =   SchoolYear::active()->first();

        $studentSections =  StudentSectionAssignment::where('school_year_id', $schoolYear->id)
                                ->whereJsonContains('students', $student->studentnumber)
                                ->get();

        $class          =   OnlineClass::where('code', $class_code)
                                ->where('school_year_id', $schoolYear->id)
                                ->whereIn('section_id', $studentSections->pluck('section_id'))
                                ->notArchive()
                                ->active()
                                ->first();

        $request->class =   $class;

        if(! $class) {
            $request->response_status  = 'error';
            $request->response_message = 'Class Not Found';
            return $next($request);
        }

        return $next($request);
    }
}
