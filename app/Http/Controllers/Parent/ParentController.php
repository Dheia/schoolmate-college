<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

use App\Models\ParentStudent;
use App\Models\ParentUser;
use App\Models\SchoolYear;
use App\StudentCredential;
use App\Models\Student;
use App\Models\SchoolCalendar;

class ParentController extends Controller
{

    private $parent;
    private $user;
    private $student_classes;

    public function index()
    {
        $parent            = auth()->user()->parent;
        $parentStudents    = ParentStudent::with('student')->where('parent_user_id', $parent->id)->get();
        $students       =   $parentStudents->pluck('student');
        $upcoming_calendar  =   SchoolCalendar::where('start_at', '>=', Carbon::now()->toDateString())
        ->where('end_at', '>=', Carbon::now()->toDateString())
        ->orderBy('start_at', 'ASC')
        ->orderBy('end_at', 'ASC')
        ->first();

        $title = "Dashboard";
        return view('parentPortal.dashboardV2',compact(['parent', 'title','students','upcoming_calendar']));
    }

    public function showAddStudentForm()
    {
        $parent         =   auth()->user()->parent;
        $parentStudents =   ParentStudent::with('student')->where('parent_user_id', $parent->id)->get();

        $students       =   $parentStudents->pluck('student');
        $title          =   "Add Student";

        return view('parentPortal.add_student',compact(['title', 'parent', 'students']));
    }

    public function addStudent(Request $request)
    {
        $this->validate($request, [
          'studentnumber' => 'required',
          'password'      => 'required'
        ]);

        $url                =   url('/api/login');
        $studentCredential  =   StudentCredential::with('student')->where('studentnumber', $request->studentnumber)->first();

        if(!$studentCredential) {
            \Alert::warning("Credentials doesn't match!")->flash();
            return redirect()->back();
        }

        if (!Hash::check($request->password, $studentCredential->password)) {
            \Alert::warning("Credentials doesn't match!")->flash();
            return redirect()->back();
        }

        $parentStudent  =   ParentStudent::create([
                                'parent_user_id'    =>  auth()->user()->parent->id,
                                'student_id'        =>  $studentCredential->student->id
                            ]);
        \Alert::success("Student Successfully Added!")->flash();
        return redirect()->back();
    }

    public function showStudentInformation($studentnumber)
    {
        $title          =   "Student Information";
        $parent         =   auth()->user()->parent;
        $student        =   Student::where('studentnumber', $studentnumber)->first();
        $schooltable    =   [];

        if(!$student)
        {
            abort(404, 'Student not found.');
        }

        $parentStudent  =   ParentStudent::where('parent_user_id', $parent->id)->where('student_id', $student->id)->first();

        if(!$parentStudent)
        {
            abort(401);
        }

        // GET SCHOOL TABLE UNTIL AND FROM
        if($student->schooltable)
        {
            if( count($student->schooltable) > 0 )
            {
                foreach ($student->schooltable as $key => $studentSchoolTable) 
                {
                    if($studentSchoolTable)
                    {
                        $grade_level_until  = YearManagement::where('id', $studentSchoolTable['grade_level_until'])->first();
                        $grade_level_from   = YearManagement::where('id', $studentSchoolTable['grade_level_from'])->first();
                        $schooltable[] = [
                            'grade_level_until' =>  $grade_level_until ? $grade_level_until->year : '-',
                            'grade_level_from'  =>  $grade_level_from ? $grade_level_from->year : '-',
                            'school_name'       =>  $studentSchoolTable['school_name'],
                            'year_attended'     =>  $studentSchoolTable['year_attended'],
                        ];
                    }
                }
            }
        }

        $student->schooltable = json_encode($schooltable);

        return view('parentPortal.student_information', compact(['title', 'parent', 'student']));

    }

    public function showStudentEnrollments($studentnumber)
    {
        dd($studentnumber);
    }
    public function studentInsurance(){

        return view('parentPortal.student_pa');
    }


}
