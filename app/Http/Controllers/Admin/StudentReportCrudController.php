<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\StudentReportRequest as StoreRequest;
use App\Http\Requests\StudentReportRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\SchoolYear;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\Student;

use App\Models\YearManagement;
use App\Models\TrackManagement;
use App\Models\TermManagement;
use Illuminate\Support\Facades\Storage;
/**
 * Class StudentReportCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StudentReportCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\StudentReport');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/student-report');
        $this->crud->setEntityNameStrings('Generate Report', 'Generate Report');


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in StudentReportRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->denyAccess(['create', 'update', 'delete', 'reorder']);
        $this->crud->setListView('studentReport.home');

        // Set DATa
        $this->data['departments']  = Department::with('term')->active()->get(); 
        $this->data['levels']       = YearManagement::select('id', 'year', 'department_id')->get(); 
        $this->data['tracks']       = TrackManagement::select('id', 'code', 'level_id')->get(); 
        $this->data['department_with_semester']   = json_encode(collect(TermManagement::where('type', 'Semester')->get())->pluck('department_id'));

        // $this->crud->addField([
        //     'label' => 'Select Report Type',
        //     'name' => 'report_type',
        //     'type' => 'select_from_array',
        //     'options' => ['enrollment' => 'Enrollment List'],
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-6'
        //     ]
        // ]);

        $this->crud->addField([
            'label' => 'School Year Entered',
            'name' => 'school_year_id',
            'type' => 'select_from_array',
            'options' => SchoolYear::all()->pluck('schoolYear', 'id'),
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-4 col-md-6'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Department',
            'name' => 'department_id',
            'type' => 'select_from_array',
            'options' => Department::active()->pluck('name', 'id'),
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-4 col-md-6'
            ],
            'allows_null' => false
        ]);

        $this->crud->addField([
            'label' => 'Level',
            'name' => 'level_id',
            'type' => 'select_from_array',
            'options' => [],
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-4 col-md-6'
            ],
            'allows_null' => true
        ]);

        $this->crud->addField([
            'label' => 'Track',
            'name' => 'track_id',
            'type' => 'select_from_array',
            'options' => [],
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-4 col-md-6'
            ],
            'allows_null' => true
        ]);

         $this->crud->addField([   // select_from_array
            'name' => 'term_type',
            'label' => "Term",
            'type' => 'select_from_array',
            'options' => [],
            'allows_null' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-4 col-md-6'
            ]
            // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
        ]);

        // SORT / ORDER BY
        $this->crud->addField([   // Checkbox
            'name' => 'sort_id',
            'label' => 'Sort',
            'type' => 'checkbox',
            'default' => 1,
            'attributes' => [ 'id' => 'sort_id']
        ]);
        $this->crud->addField([
            'label' => 'First Level',
            'name' => 'first_level',
            'type' => 'select_from_array',
            'options' => [
                'students.level_id'   => 'Level',
                'students.track_id'   => 'Track',
                'gender'                 => 'Gender',
                'lastname'               => 'Last Name',
                'firstname'              => 'First Name',
                'students.studentnumber' => 'Student Number',
                'students.lrn'           => 'LRN'
            ],
            'default'   =>  'enrollments.level_id',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-sm-6 col-xs-6 indent-10'
            ],
            'allows_null' => false
        ]);
        $this->crud->addField([
            'label' => 'Order',
            'name' => 'first_level_order',
            'type' => 'select_from_array',
            'options' => ['ASC' => 'Ascending', 'DESC' => 'Descending'],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-sm-6 col-xs-6'
            ],
            'allows_null' => false
        ]);
         $this->crud->addField([
            'label' => 'Second Level',
            'name' => 'second_level',
            'type' => 'select_from_array',
            'options' => [
                'students.level_id'   => 'Level',
                'students.track_id'   => 'Track',
                'gender'                 => 'Gender',
                'lastname'               => 'Last Name',
                'firstname'              => 'First Name',
                'students.studentnumber' => 'Student Number',
                'students.lrn'           => 'LRN'
            ],
            'default'   =>  'students.track_id',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-xs-6 indent-10'
            ],
            'allows_null' => false
        ]);
        $this->crud->addField([
            'label' => 'Order',
            'name' => 'second_level_order',
            'type' => 'select_from_array',
            'options' => ['ASC' => 'Ascending', 'DESC' => 'Descending'],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-xs-6'
            ],
            'allows_null' => false
        ]);
        $this->crud->addField([
            'label' => 'Third Level',
            'name' => 'third_level',
            'type' => 'select_from_array',
            'options' => [
                'students.level_id'   => 'Level',
                'students.track_id'   => 'Track',
                'gender'                 => 'Gender',
                'lastname'               => 'Last Name',
                'firstname'              => 'First Name',
                'students.studentnumber' => 'Student Number',
                'students.lrn'           => 'LRN'
            ],
            'default'   =>  'gender',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-xs-6 indent-10'
            ],
            'allows_null' => false
        ]);
        $this->crud->addField([
            'label' => 'Order',
            'name' => 'third_level_order',
            'type' => 'select_from_array',
            'options' => ['ASC' => 'Ascending', 'DESC' => 'Descending'],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-xs-6'
            ],
            'allows_null' => false
        ]);
        $this->crud->addField([
            'label' => 'Fourth Level',
            'name' => 'fourth_level',
            'type' => 'select_from_array',
            'options' => [
                'students.level_id'   => 'Level',
                'students.track_id'   => 'Track',
                'gender'                 => 'Gender',
                'lastname'               => 'Last Name',
                'firstname'              => 'First Name',
                'students.studentnumber' => 'Student Number',
                'students.lrn'           => 'LRN'
            ],
            // 'default'   =>  'lastname',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-xs-6 indent-10'
            ],
            'allows_null' => false
        ]);
        $this->crud->addField([
            'label' => 'Order',
            'name' => 'fourth_level_order',
            'type' => 'select_from_array',
            'options' => ['ASC' => 'Ascending', 'DESC' => 'Descending'],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-xs-6'
            ],
            'allows_null' => false
        ]);
        $this->crud->addField([
            'label' => 'Fifth Level',
            'name' => 'fifth_level',
            'type' => 'select_from_array',
            'options' => [
                'students.level_id'   => 'Level',
                'students.track_id'   => 'Track',
                'gender'                 => 'Gender',
                'lastname'               => 'Last Name',
                'firstname'              => 'First Name',
                'students.studentnumber' => 'Student Number',
                'students.lrn'           => 'LRN'
            ],
            // 'default'   =>  'firstname',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-xs-6 indent-10'
            ],
            'allows_null' => false
        ]);
        $this->crud->addField([
            'label' => 'Order',
            'name' => 'fifth_level_order',
            'type' => 'select_from_array',
            'options' => ['ASC' => 'Ascending', 'DESC' => 'Descending'],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-xs-6'
            ],
            'allows_null' => false
        ]);

    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    // public function generateReport (StoreRequest $request)
    // {
    //     $whereClause = [];

    //     if(request()->school_year_id !== null) { $whereClause['schoolYear'] = request()->school_year_id; }

    //     $students = [];
    //     if(count($whereClause) > 0) {
    //         $students = Student::where($whereClause)->with(['level', 'track', 'department', 'schoolYear'])->get(); 
    //     }

    //     $total_male = $students->where('gender', 'Male')->count();
    //     $total_female = $students->where('gender', 'Female')->count();

    //     $pdf = \App::make('dompdf.wrapper');
    //     $pdf->setPaper(array(0, 0, 612, 936), 'portrait');

    //     return view('studentReport.generateReport', compact('students', 'total_male', 'total_female'));
    //     $pdf->loadHTML( view('studentReport.generateReport', compact('students', 'total_male', 'total_female')) );

    //     return $pdf->stream(config('settings.schoolabbr') . '.pdf');
    //     // return view('studentReport.generateReport', compact('enrollments', 'crud', 'schoolLogo'));
    // }

    public function generateReport (StoreRequest $request, $action)
    {
        if($action == 'download')
        {
            $action = 'new_generateReport';
        }
        else {
            $action = 'showReport';
        }
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', -1);
        
        $whereClause = [];

        $schoolYear = null;
        $department = null;
        $level      = null;
        $track      = null;
        $term       = null;

        $orderBy['firstlevel']  = request()->first_level;
        $orderBy['order1']      = request()->first_level_order;
        $orderBy['secondlevel'] = request()->second_level;
        $orderBy['order2']      = request()->second_level_order;
        $orderBy['thirdlevel']  = request()->third_level;
        $orderBy['order3']      = request()->third_level_order;
        $orderBy['fourthlevel'] = request()->fourth_level;
        $orderBy['order4']      = request()->fourth_level_order;
        $orderBy['fifthlevel']  = request()->fifth_level;
        $orderBy['order5']      = request()->fifth_level_order;

        if(request()->school_year_id !== null) { 
            $whereClause['schoolyear'] = request()->school_year_id;
            $schoolYear = SchoolYear::where('id', request()->school_year_id)->first()->schoolYear;
        }

        if(request()->department_id !== null) { 
            $whereClause['students.department_id'] = request()->department_id;
            $department = Department::where('id', request()->department_id)->first();
        }

        if(request()->level_id !== null) { 
            $whereClause['students.level_id'] = request()->level_id;
            $level = YearManagement::where('id', request()->level_id)->first()->year;
        }

        if(request()->track_id !== null) { 
            $whereClause['students.track_id'] = request()->track_id;
            $track = TrackManagement::where('id', request()->track_id)->first()->code;
        }

        if(request()->term_type !== null){
            $term = request()->term_type;
        }
        $total_male  = 0;
        $total_female  = 0;
        $total_applicant = 0;
        $total_enrolled  = 0;
        $students        = [];
        $total_students_tracks = null;

        if(count($whereClause) > 0) {
            if(request()->sort_id){

                 $students  =   $this->crud->model::where($whereClause)
                                        ->with(['level', 'track', 'department', 'schoolYear'])
                                        ->orderBy($orderBy['firstlevel'], $orderBy['order1'])
                                        ->orderBy($orderBy['secondlevel'], $orderBy['order2'])
                                        ->orderBy($orderBy['thirdlevel'], $orderBy['order3'])
                                        ->orderBy($orderBy['fourthlevel'], $orderBy['order4'])
                                        ->orderBy($orderBy['fifthlevel'], $orderBy['order5'])
                                        ->get();
            }
            else{

               $students   =   $this->crud->model::where($whereClause)
                                        ->with(['level', 'track', 'department', 'schoolYear'])
                                        ->orderBy('students.level_id')
                                        ->orderBy('students.track_id')
                                        ->orderBy('gender')
                                        ->orderBy('lastname')
                                        ->orderBy('firstname')
                                        ->orderBy('middlename')
                                        ->get();
            }
            $total_applicant = collect($students)->where('is_enrolled', 'Applicant')->count();
            $total_enrolled  = collect($students)->where('is_enrolled', 'Enrolled')->count();

            $total_female    = collect($students)->where('gender', 'Female')->count();
            $total_male      = collect($students)->where('gender', 'Male')->count();
            // dd($total_applicant);
        }   
        if (request()->track_id == null) {
            if (request()->level_id == null) {
                foreach ( $this->data['tracks'] as $key => $tracks) {
                    $year = YearManagement::where('id', $tracks->level_id)->first();
                    $total_students_tracks[$year->year.' - '.$tracks->code] = collect($students)->where('track_id', $tracks->id)->count();
                }
            }
            else{
                $year = TrackManagement::where('level_id', request()->level_id)->get();
                foreach ($year as $key => $tracks) {
                $total_students_tracks[$tracks->code] = collect($students)->where('track_id', $tracks->id)->count();
                }
            }
            
        }  
        
        // dd($total_students_tracks);
        // DOWNLOAD PDF VIA JSPDF
        $schoollogo      = config('settings.schoollogo') ? (string)\Image::make(config('settings.schoollogo'))->encode('data-url') : null;
        $schoolmate_logo = (string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url');

        return view('studentReport.'.$action,  compact('students', 'schoolYear', 'total_applicant', 'total_enrolled', 'total_male', 'total_female', 'department', 'level', 'track', 'term', 'total_students_tracks', 'schoollogo', 'schoolmate_logo'));

        // dd($total_students_tracks);
        // $schoollogo      = (string)\Image::make(config('settings.schoollogo'))->encode('data-url');
        // $schoolmate_logo = (string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url');
        // return view('studentReport.generateReport', compact('students', 'schoolYear', 'total_applicant', 'total_enrolled', 'total_male', 'total_female', 'department', 'level', 'track', 'schoollogo', 'schoolmate_logo',));
        // $pdf = \App::make('dompdf.wrapper');

        // $pdf->loadHTML( view('studentReport.pdf', compact('students', 'schoolYear', 'total_applicant', 'total_enrolled', 'total_male', 'total_female', 'department', 'level', 'track', 'term', 'total_students_tracks')) );
        // return $pdf->stream();
    }
}
