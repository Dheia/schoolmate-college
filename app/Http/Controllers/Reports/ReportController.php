<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\SchoolYear;
use App\Models\Enrollment;
use App\Models\Department;
use App\Models\YearManagement;
use App\Models\Student;
use App\Models\TermManagement;
use App\Models\TrackManagement As Track;

use Carbon\Carbon;

class ReportController extends Controller
{

	public function mainFilter(){
		$school_year = SchoolYear::all()->pluck('schoolYear', 'id');
		$department = Department::all()->pluck('name', 'id');
        $terms = TermManagement::all()->pluck('name', 'id');

		return view('reports.main_filter', compact('school_year','department', 'terms'));
	}

    //
    public function enrollmentList(Request $request){
    	$school_year_id = $request->school_year_id;
    	$department_id = $request->department_id;
    	$level_id = $request->level_id;
    	$track_id = $request->track_id;
        $sort_by = $request->sort_gender;

		$track = "";
    	$school_year = SchoolYear::where('id', $school_year_id)->first();

    	if($department_id != "all"){
    		$department = Department::where('id', $department_id)->first();
    		$level = YearManagement::where('id', $level_id)->first();
            $track = "All Tracks";

            $enrollment = Student::whereHas('enrollments', function($query) use ($school_year_id, $department_id){
                                $query->where('school_year_id', $school_year_id);
                                $query->where('department_id', $department_id);
                        })->with('enrollments')
                        ->where('deleted_at', null)

                        ->orderBy('lastname', 'asc')
                        // ->when($sort_by == 'male_female', function($query) {
                        //     $query->orderBy('gender', 'asc');
                        // })
                        // ->when($sort_by == 'female_male', function($query) {

                        //     $query->orderBy('gender', 'desc');
                        // })
                        // ->where('deleted_at', null)
                        // ->where('school_year_id', $school_year_id)
                        ->get();
    	} else {

    		$department = "All Departments";
            $level = "All Levels";
            $track = "All Tracks";
    		$enrollment = Student::whereHas('enrollments', function($query) use ($school_year_id){
                                $query->where('school_year_id', $school_year_id);
                        })->with('enrollments')
    					->where('deleted_at', null)
                        ->orderBy('lastname', 'asc')
                        // ->when($sort_by == 'male_female', function($query) {
                        //     $query->orderBy('gender', 'asc');
                        // })
                        // ->when($sort_by == 'female_male', function($query) {
                        //     $query->orderBy('gender', 'desc');
                        // })
    					// ->where('school_year_id', $school_year_id)
    					->get();
           

    	}

        if($level_id != "all"){
            $department = Department::where('id', $department_id)->first();
            $level = YearManagement::where('id', $level_id)->first();
            $track = "All Tracks";
            $enrollment = Student::whereHas('enrollments', function($query) use ($school_year_id, $department_id, $level_id){
                                $query->where('school_year_id', $school_year_id);
                                $query->where('department_id', $department_id);
                                $query->where('level_id', $level_id);
                        })->with('enrollments')
                        ->where('deleted_at', null)
                        ->orderBy('lastname', 'asc')
                        // ->where('deleted_at', null)
                        // ->where('school_year_id', $school_year_id)
                        ->get();
        } else {

            $department = Department::where('id', $department_id)->first();
            $level = "All Levels";
            $track = "All Tracks";
            $enrollment = Student::whereHas('enrollments', function($query) use ($school_year_id,$department_id){
                                $query->where('school_year_id', $school_year_id);
                                $query->where('department_id', $department_id);
                        })->with('enrollments')
                        ->where('deleted_at', null)
                        ->orderBy('lastname', 'asc')
                        // ->where('school_year_id', $school_year_id)
                        ->get();
           
            

        }

        if($track_id != "all"){
            $department = Department::where('id', $department_id)->first();
            $level = YearManagement::where('id', $level_id)->first();
            $track = Track::where('id', $track_id)->first();;
            $enrollment = Student::whereHas('enrollments', function($query) use ($school_year_id, $department_id, $level_id, $track_id){
                                $query->where('school_year_id', $school_year_id);
                                $query->where('department_id', $department_id);
                                $query->where('level_id', $level_id);
                                $query->where('track_id', $track_id);
                        })->with('enrollments')
                        ->where('deleted_at', null)
                        ->orderBy('lastname', 'asc')
                        ->get();
        } else {
            $department = Department::where('id', $department_id)->first();
            $level = YearManagement::where('id', $level_id)->first();
            $track = "All Tracks";
            $enrollment = Student::whereHas('enrollments', function($query) use ($school_year_id,$department_id,$level_id){
                                $query->where('school_year_id', $school_year_id);
                                $query->where('department_id', $department_id);
                                $query->where('level_id', $level_id);
                        })->with('enrollments')
                        ->where('deleted_at', null)
                        ->orderBy('lastname', 'asc')
                        // ->where('school_year_id', $school_year_id)
                        ->get();
        } 

    	$pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper(array(0, 0, 612, 936), 'portrait');

        $pdf->loadHTML( view('reports.enrollment_list', compact('enrollment','school_year','department','level','track')));
        return $pdf->stream('EnrollmentList' . Carbon::now() . '.pdf');

    }

    // Filters

    public function filtersDepartment(Request $request) {
    	$levels = YearManagement::where('department_id', $request->department_id)
    						->get()->pluck('year','id');

    	return response()->json($levels);
    }

    public function filtersTrack(Request $request) {
    	$tracks = Track::where('level_id', $request->level_id)
    						->get()->pluck('code','id');

    	return response()->json($tracks);
    }


}
