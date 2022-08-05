<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EnrollmentReportRequest as StoreRequest;
use App\Http\Requests\EnrollmentReportRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\SchoolYear;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\Student;

use App\Models\YearManagement;
use App\Models\TrackManagement;
use App\Models\TermManagement;

/**
 * Class EnrollmentReportCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EnrollmentReportCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\EnrollmentReport');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/enrollment-report');
        $this->crud->setEntityNameStrings('Enrollment Report', 'Enrollment Reports');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in EnrollmentReportRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->setListView('enrollmentReport.home');

        // Set DATa
        $this->data['departments']  = Department::with('term')->active()->get(); 
        $this->data['levels']       = YearManagement::select('id', 'year', 'department_id')->get(); 
        $this->data['tracks']       = TrackManagement::select('id', 'code', 'level_id')->get();
        $this->data['department_with_semester']   = json_encode(collect(TermManagement::where('type', 'Semester')->get())->pluck('department_id'));
        $this->crud->addField([
            'label' => 'School Year',
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
                'enrollments.level_id'   => 'Level',
                'enrollments.track_id'   => 'Track',
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
                'enrollments.level_id'   => 'Level',
                'enrollments.track_id'   => 'Track',
                'gender'                 => 'Gender',
                'lastname'               => 'Last Name',
                'firstname'              => 'First Name',
                'students.studentnumber' => 'Student Number',
                'students.lrn'           => 'LRN'
            ],
            'default'   =>  'enrollments.track_id',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-sm-6 col-xs-6 indent-10'
            ],
            'allows_null' => false
        ]);
        $this->crud->addField([
            'label' => 'Order',
            'name' => 'second_level_order',
            'type' => 'select_from_array',
            'options' => ['ASC' => 'Ascending', 'DESC' => 'Descending'],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-sm-6 col-xs-6'
            ],
            'allows_null' => false
        ]);
        $this->crud->addField([
            'label' => 'Third Level',
            'name' => 'third_level',
            'type' => 'select_from_array',
            'options' => [
                'enrollments.level_id'   => 'Level',
                'enrollments.track_id'   => 'Track',
                'gender'                 => 'Gender',
                'lastname'               => 'Last Name',
                'firstname'              => 'First Name',
                'students.studentnumber' => 'Student Number',
                'students.lrn'           => 'LRN'
            ],
            'default'   =>  'gender',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-sm-6 col-xs-6 indent-10'
            ],
            'allows_null' => false
        ]);
        $this->crud->addField([
            'label' => 'Order',
            'name' => 'third_level_order',
            'type' => 'select_from_array',
            'options' => ['ASC' => 'Ascending', 'DESC' => 'Descending'],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-sm-6 col-xs-6'
            ],
            'allows_null' => false
        ]);
        $this->crud->addField([
            'label' => 'Fourth Level',
            'name' => 'fourth_level',
            'type' => 'select_from_array',
            'options' => [
                'enrollments.level_id'   => 'Level',
                'enrollments.track_id'   => 'Track',
                'gender'                 => 'Gender',
                'lastname'               => 'Last Name',
                'firstname'              => 'First Name',
                'students.studentnumber' => 'Student Number',
                'students.lrn'           => 'LRN'
            ],
            'default'   =>  'lastname',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-sm-6 col-xs-6 indent-10'
            ],
            'allows_null' => false
        ]);
        $this->crud->addField([
            'label' => 'Order',
            'name' => 'fourth_level_order',
            'type' => 'select_from_array',
            'options' => ['ASC' => 'Ascending', 'DESC' => 'Descending'],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-sm-6 col-xs-6'
            ],
            'allows_null' => false
        ]);
        $this->crud->addField([
            'label' => 'Fifth Level',
            'name' => 'fifth_level',
            'type' => 'select_from_array',
            'options' => [
                'enrollments.level_id'   => 'Level',
                'enrollments.track_id'   => 'Track',
                'gender'                 => 'Gender',
                'lastname'               => 'Last Name',
                'firstname'              => 'First Name',
                'students.studentnumber' => 'Student Number',
                'students.lrn'           => 'LRN'
            ],
            'default'   =>  'firstname',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-sm-6 col-xs-6 indent-10'
            ],
            'allows_null' => false
        ]);
        $this->crud->addField([
            'label' => 'Order',
            'name' => 'fifth_level_order',
            'type' => 'select_from_array',
            'options' => ['ASC' => 'Ascending', 'DESC' => 'Descending'],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-sm-6 col-xs-6'
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

    public function generateReport (StoreRequest $request, $action)
    {
        if($action == 'download')
        {
            $action = 'generateReport';
        }
        else {
            $action = 'showReport';
        }
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', -1);

        $whereClause = [];
        $orderBy     = [];

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
            $whereClause['enrollments.school_year_id'] = request()->school_year_id;
            $schoolYear = SchoolYear::where('id', request()->school_year_id)->first()->schoolYear;
        }

        if(request()->department_id !== null) { 
            $whereClause['enrollments.department_id'] = request()->department_id;
            $department = Department::where('id', request()->department_id)->with('term')->first();
        }

        if(request()->term_type) {
            $whereClause['enrollments.term_type'] = request()->term_type;
            $term = request()->term_type;
        }

        if(request()->level_id !== null) { 
            $whereClause['enrollments.level_id'] = request()->level_id;
            $level = YearManagement::where('id', request()->level_id)->first()->year;
        }

        if(request()->track_id !== null) {
            $whereClause['enrollments.track_id'] = request()->track_id;
            $track = TrackManagement::where('id', request()->track_id)->first()->code;
        }

        $total_female         = 0;
        $total_male           = 0;
        $enrollments          = [];
        $total_students_tracks = null;

        if(count($whereClause) > 0) {
            if(request()->sort_id){
                $enrollments    = Student::where($whereClause)
                                        ->with(['level', 'track', 'department', 'schoolYear'])
                                        ->join('enrollments', function ($join) {
                                            $join->on('students.studentnumber', 'enrollments.studentnumber')
                                            ->where('enrollments.is_applicant', 0)
                                            ->where('enrollments.deleted_at', null);
                                        })
                                        ->orderBy($orderBy['firstlevel'], $orderBy['order1'])
                                        ->orderBy($orderBy['secondlevel'], $orderBy['order2'])
                                        ->orderBy($orderBy['thirdlevel'], $orderBy['order3'])
                                        ->orderBy($orderBy['fourthlevel'], $orderBy['order4'])
                                        ->orderBy($orderBy['fifthlevel'], $orderBy['order5'])
                                        ->get(); 
            }
            else{
                $enrollments    = Student::where($whereClause)
                                        ->with(['level', 'track', 'department', 'schoolYear'])
                                        ->join('enrollments', function ($join) {
                                            $join->on('students.studentnumber', 'enrollments.studentnumber')
                                            ->where('enrollments.is_applicant', 0)
                                            ->where('enrollments.deleted_at', null);
                                        })
                                        ->orderBy('enrollments.level_id')
                                        ->orderBy('enrollments.track_id')
                                        ->orderBy('gender')
                                        ->orderBy('lastname')
                                        ->orderBy('firstname')
                                        ->orderBy('middlename')
                                        ->get(); 
            }
                                        
            $total_male     = collect($enrollments)->where('gender', 'Male')->count();
            $total_female   = collect($enrollments)->where('gender', 'Female')->count();

            if (request()->track_id == null) {
                if (request()->level_id == null) {
                    foreach ( $this->data['tracks'] as $key => $tracks) {
                        $year = YearManagement::where('id', $tracks->level_id)->first();
                        $total_students_tracks[$year->year.' - '.$tracks->code] = collect($enrollments)->where('track_id', $tracks->id)->count();
                    }
                }
                else{
                    $year = TrackManagement::where('level_id', request()->level_id)->get();
                    foreach ($year as $key => $tracks) {
                        $total_students_tracks[$tracks->code] = collect($enrollments)->where('track_id', $tracks->id)->count();
                    }
                }
                
            }
        }  
        // dd($total_students_tracks);
        // DOWNLOAD PDF VIA JSPDF
        $schoollogo      = config('settings.schoollogo') ? (string)\Image::make(config('settings.schoollogo'))->encode('data-url') : null;
        $schoolmate_logo = (string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url');

        return view('enrollmentReport.'.$action, compact('enrollments', 'schoolYear', 'total_male', 'total_female','department', 'level', 'track', 'term', 'total_students_tracks', 'schoollogo', 'schoolmate_logo'));

        // VIEW DOMPDF
        // $pdf = \App::make('dompdf.wrapper');

        // $pdf->loadHTML( view('enrollmentReport.pdf',  compact('enrollments', 'schoolYear', 'total_male', 'total_female','department', 'level', 'track', 'term', 'total_students_tracks')) );
        // return $pdf->stream();
        // return $pdf->download('sample.pdf');
    }
}
