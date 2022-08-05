<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Models
use App\Models\SchoolYear;
use App\Models\Department;
use App\Models\YearManagement;
use App\Models\Enrollment;

class StudentBalancesReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $schoolYears = SchoolYear::get();
        $departments = Department::active()->get();

        $data = [
            'title' => 'Student Balances Report',
            'schoolYears' => $schoolYears,
            'departments' => $departments,
        ];

        return view('reports.accounting.student_balances_report', $data);
    }

    /*
    |--------------------------------------------------------------------------
    | Get Department's Levels (AJAX API)
    |--------------------------------------------------------------------------
    */
    public function getLevels($department_id)
    {
        $department = Department::where('id', $department_id)->active()->first();

        if(! $department) {
            return response()->json('Department Not Found.', 404);
        }
        
        // $levels = $department->levels->sortBy('sequence');
        $levels = YearManagement::where('department_id', $department->id)->orderBy('sequence', 'ASC')->get();
        // dd($levels);
        return response()->json($levels, 201);
    }

    /*
    |--------------------------------------------------------------------------
    | Get Enrollment List (AJAX API)
    |--------------------------------------------------------------------------
    */
    public function getEnrollmentList(Request $request)
    {
        $schoolYear = null;
        $department = null;
        $level      = null;

        if(! $request->school_year_id) {
            return response()->json('School Year Not Found.', 404);
        }

        // Get School Year
        $schoolYear = SchoolYear::where('id', $request->school_year_id)->first();
        if(! $schoolYear) {
            return response()->json('School Year Not Found.', 404);
        }

        $whereClause = [];

        // Get Department
        if($request->department_id) {
            $department = Department::where('id', $request->department_id)->active()->first();
            if(! $department) {
                return response()->json('Department Not Found.', 404);
            }

            $whereClause['department_id'] = $department->id;

            // Get Level
            if($request->level_id) {
                $level = YearManagement::where('id', $request->level_id)
                            ->where('department_id', $request->department_id)
                            ->first();
                if(! $level) {
                    return response()->json('Level Not Found.', 404);
                }
                $whereClause['level_id'] = $level->id;
            }
        }

        $enrollments =  Enrollment::where('school_year_id', $schoolYear->id)
                            ->where('is_applicant', 0)
                            ->where($whereClause)
                            ->orderBy('level_id', 'ASC')
                            ->get();

        $data = [
            'schoolYear' => $schoolYear,
            'department' => $department,
            'level'      => $level,
            'enrollments' => $enrollments,
        ];

        return response()->json($data);
    }

    /*
    |--------------------------------------------------------------------------
    | Print Enrollment List
    |--------------------------------------------------------------------------
    */
    public function download(Request $request)
    {
        $schoolYear = null;
        $department = null;
        $level      = null;

        if(! $request->school_year_id) {
            \Alert::warning("School Year Not Found.")->flash();
            return redirect()->back();
        }

        // Get School Year
        $schoolYear = SchoolYear::where('id', $request->school_year_id)->first();
        if(! $schoolYear) {
            \Alert::warning("School Year Not Found.")->flash();
            return redirect()->back();
        }

        $whereClause = [];

        // Get Department
        if($request->department_id) {
            $department = Department::where('id', $request->department_id)->active()->first();
            if(! $department) {
                \Alert::warning("Department Not Found.")->flash();
                return redirect()->back();
            }

            $whereClause['department_id'] = $department->id;

            // Get Level
            if($request->level_id) {
                $level = YearManagement::where('id', $request->level_id)
                            ->where('department_id', $request->department_id)
                            ->first();
                if(! $level) {
                    \Alert::warning("Level Not Found.")->flash();
                    return redirect()->back();
                }
                $whereClause['level_id'] = $level->id;
            }
        }

        $enrollments =  Enrollment::where('school_year_id', $schoolYear->id)
                            ->where('is_applicant', 0)
                            ->where($whereClause)
                            ->orderBy('level_id', 'ASC')
                            ->get();

        $data = [
            'schoolYear' => $schoolYear,
            'department' => $department,
            'level'      => $level,
            'enrollments' => $enrollments,
            'schoollogo'      => config('settings.schoollogo') ? (string)\Image::make(config('settings.schoollogo'))->encode('data-url') : null,
            'schoolmate_logo' => (string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url')
        ];

        // dd($data);
        return view('reports.accounting.student_balances_pdf', $data );
    }
    
}
