<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Student;
use App\Models\Enrollment;
use App\Models\SchoolYear;

class CheckIfStudentIsEnrolled
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
        $studentEnrollment  = null;
        $student            = auth()->user()->student;
        $schoolYear         = SchoolYear::active()->first();
        $request->isStudentEnrolled = true;

        // Get Student Enrollment Of Active School Year
        if($student && $schoolYear)
        {
            $studentEnrollment      =   Enrollment::where('student_id', $student->id)->where('school_year_id', $schoolYear->id)->first();
            if(!$studentEnrollment)
            {
                $studentEnrollment  =   Enrollment::where('studentnumber', $student->studentnumber)->where('school_year_id', $schoolYear->id)->first();
            }
        }

        // Check If Student Is Enrolled In Active School Year
        // If Student Not Enrolled
        if(!$studentEnrollment) {
            $request->isStudentEnrolled = false;
            $error = [
                'image'        =>  '/images/error-sorry.png',
                'title'         =>  'You are NOT enrolled this school year.',
                'description'   =>  'Please Contact Your School Administrator.'
            ];
            \Alert::warning("You are NOT Enrolled this School Year.")->flash();
            return response(view('errors/error', compact(['error'])));
        }
        
        return $next($request);
    }
}
