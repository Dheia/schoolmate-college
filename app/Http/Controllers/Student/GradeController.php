<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

// MODELS
use App\Models\Enrollment;
use App\Models\EncodeGrade;
use App\Models\Period;
use App\Models\SchoolYear;
use App\Models\SectionManagement;
use App\Models\StudentSectionAssignment;
use App\Models\SetupGrade;
use App\Models\SubjectMapping;
use App\Models\SubjectManagement;

class GradeController extends Controller
{

	private $student;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->student = auth()->user()->student;
            return $next($request);
        });
    }

    public function getAllSchoolYearEnrolled ()
    {
        $studentnumber = $this->student->studentnumber;
        $enrollments = Enrollment::where('studentnumber', $this->student->studentnumber)
                                    ->with('schoolYear')
                                    ->with(['studentSectionAssignment' => function ($query) use ($studentnumber) {
                                        $query->where('students', 'like', '%' . $studentnumber . '%');
                                        $query->with(['section' => function ($q) {
                                            $q->with('level');
                                        }]);
                                    }])->get();
                
        return view('student.grades.enrolled_list', compact('enrollments'));
    }

    // public function getTabsPeriod (Request $request)
    // {
    //     $section = SectionManagement::where('id', $request->section_id)->with('level')->first();
    //     $periods = [];
    //     if($section !== null) {
    //         $periods = Period::where('department_id', $section->level->department_id)->orderBy('sequence', 'asc')->get();
    //     }
    //     return response()->json($periods);
    // }

    // public function viewGrades (Request $request)
    // {
    //     $subjectMapping = SubjectMapping::where('level_id', $request->level_id)->with('level')->first();

    //     if($subjectMapping === null) {
    //         // \Alert::warning("Error")->flash();
    //         return redirect('student/grades');
    //     }

    //     $subjectsIds = collect($subjectMapping->subjects)->pluck('subject_code')->toArray();

    //     $subjects       = SubjectManagement::findMany($subjectsIds);
    //     $section        = SectionManagement::where('id', $request->section_id)->with('level')->first();
    //     $schoolYear     = SchoolYear::where('id', $request->school_year_id)->first();
    //     $periods        = [];

    //     if($section !== null) {
    //         $periods = Period::where('department_id', $section->level->department_id)->orderBy('sequence', 'asc')->get();
    //     }

    //     $grades = EncodeGrade::getGrades($this->student->studentnumber, $request->level_id, $request->section_id, count($periods));
    // 	return view('student.grades.displayGrades', compact('grades', 'subjects', 'subjectMapping', 'periods', 'schoolYear', 'section'));
    // }
    public function viewGrades ($enrollment_id)
    {
        $section    =   null;
        $title      =   "Grades";
        $studentnumber = $this->student->studentnumber;
        $enrollment = Enrollment::where('id', $enrollment_id)
                                ->where('studentnumber', $studentnumber)
                                ->with('schoolYear')
                                ->with(['studentSectionAssignment' => function ($query) use ($studentnumber) {
                                    $query->where('students', 'like', '%' . $studentnumber . '%');
                                    $query->with(['section' => function ($q) {
                                        $q->with('level');
                                    }]);
                                }])->first();
                                
        $subjectMapping =   SubjectMapping::where('level_id', $enrollment->level_id)
                                ->where('curriculum_id', $enrollment->curriculum_id)
                                ->where('department_id', $enrollment->department_id)
                                ->where('course_id', $enrollment->course_id)
                                ->where('track_id', $enrollment->track_id)
                                ->where('term_type', $enrollment->term_type)
                                ->with('level')->first();

        if($subjectMapping === null) {
            \Alert::warning("Subject Mapping NOT Found. <br> Please Contact Your School Administrator.")->flash();
            return redirect('student/enrollments');
        }

        $subjectsIds = collect($subjectMapping->subjects)->pluck('subject_code')->toArray();
        $subjects       = SubjectManagement::findMany($subjectsIds);
        if($enrollment->studentSectionAssignment)
        {
            if($enrollment->studentSectionAssignment->section)
            {
                $section        = SectionManagement::where('id', $enrollment->studentSectionAssignment->section->id)->with('level')->first();
            }
        }
        $schoolYear     = SchoolYear::where('id', $enrollment->school_year_id)->first();
        $periods        = [];

        if($section !== null) {
            $periods = Period::where('department_id', $section->level->department_id)->orderBy('sequence', 'asc')->get();
        }
        else {
            \Alert::warning("Section not found.")->flash();
            return redirect('student/enrollments');
        }

        $grades = EncodeGrade::getTermGrades($this->student->studentnumber, $enrollment->level_id, $enrollment->studentSectionAssignment->section->id, count($periods),  $enrollment->term_type);
        // dd($grades);
        return view('student.grades.displayGrades', compact('grades', 'subjects', 'subjectMapping', 'periods', 'schoolYear', 'section', 'title'));
    }
}
