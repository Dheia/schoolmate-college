<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EmployeeEncodeGradeScheduleRequest as StoreRequest;
use App\Http\Requests\EmployeeEncodeGradeScheduleRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\Department;
use App\Models\TermManagement;
use App\Models\YearManagement;
use App\Models\TeacherSubject;
use App\Models\SubjectManagement;
use App\Models\SectionManagement;

/**
 * Class EmployeeEncodeGradeScheduleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EmployeeEncodeGradeScheduleCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\EmployeeEncodeGradeSchedule');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/employee-encode-grade-schedule');
        $this->crud->setEntityNameStrings('Employee Encoding Schedule', 'employee encode grade schedules');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->data['terms']    = TermManagement::all();
        $this->data['levels']   = YearManagement::orderBy('sequence', 'ASC')->get();

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();
        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
           // 1-n relationship
           'label' => "Employee", // Table column heading
           'type' => "select",
           'name' => 'employee_id', // the column that contains the ID of that connected entity;
           'entity' => 'employee', // the method that defines the relationship in your Model
           'attribute' => "fullname", // foreign key attribute that is shown to user
           'model' => "App\Models\Employee", // foreign key model
        ]);

        $this->crud->addColumn([
           // 1-n relationship
           'label' => "School Year", // Table column heading
           'type' => "select",
           'name' => 'school_year_id', // the column that contains the ID of that connected entity;
           'entity' => 'schoolYear', // the method that defines the relationship in your Model
           'attribute' => "schoolYear", // foreign key attribute that is shown to user
           'model' => "App\Models\SchoolYear", // foreign key model
        ]);

        $this->crud->addColumn([
            'name'              => 'term_type',
            'label'             => 'Term Type',
            'type'              => 'text',
        ]);

        $this->crud->addColumn([
            'name'              => 'level_name',
            'label'             => 'Level',
            'type'              => 'text',
        ]);

        $this->crud->addColumn([
            'name'              => 'track_name',
            'label'             => 'Track',
            'type'              => 'text',
        ]);

        $this->crud->addColumn([
           // 1-n relationship
           'label' => "Section", // Table column heading
           'type' => "select",
           'name' => 'section_id', // the column that contains the ID of that connected entity;
           'entity' => 'section', // the method that defines the relationship in your Model
           'attribute' => "name", // foreign key attribute that is shown to user
           'model' => "App\Models\SectionManagement", // foreign key model
        ]);

        $this->crud->addColumn([
           'label' => "Start",
           'type' => "date",
           'name' => 'start_at',
           'format' => 'MMMM DD, YYYY'
        ]);

        $this->crud->addColumn([
           'label' => "End",
           'type' => "date",
           'name' => 'end_at',
           'format' => 'MMMM DD, YYYY'
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([  // Select
           'label' => "Employee",
           'type' => 'select2',
           'name' => 'employee_id', // the db column for the foreign key
           'entity' => 'employee', // the method that defines the relationship in your Model
           'attribute' => 'fullname', // foreign key attribute that is shown to user
           'model' => "App\Models\Employee",
           // optional
           'options'   => (function ($query) {
                return $query->orderBy('firstname', 'ASC')->get();
            }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
           'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
           'allows_null' => true
        ]);

        $this->crud->addField([  // Select
           'label' => "School Year",
           'type' => 'select',
           'name' => 'school_year_id', // the db column for the foreign key
           'entity' => 'schoolYear', // the method that defines the relationship in your Model
           'attribute' => 'schoolYear', // foreign key attribute that is shown to user
           'model' => "App\Models\SchoolYear",
           // optional
           'options'   => (function ($query) {
                return $query->orderBy('sequence', 'ASC')->get();
            }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
           'wrapperAttributes' => [ 'class' => 'form-group col-md-4' ],
           'allows_null' => true
        ]);

        $this->crud->addField([
                'name'              => 'department_id',
                'label'             => 'Department',
                'type'              => 'select_from_array',
                'options'           => Department::active()->get()->pluck('name', 'id'),
                'wrapperAttributes' => [ 'class' => 'form-group col-md-4' ],
        ]);

        $this->crud->addField([
            'name' => 'term_type',
            'type' => 'select_from_array',
            'label' => 'Term Type',
            'options' => [],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-4' ],
        ]);

        $this->crud->addField([
            'name' => 'level_id',
            'type' => 'select_from_array',
            'label' => 'Level',
            'options' => [],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-4' ],
        ]);

        $this->crud->addField([
            'name' => 'section_id',
            'type' => 'select_from_array',
            'label' => 'Section',
            'options' => [],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-4' ],
        ]);

        $this->crud->addField([
            'name' => 'subject_id',
            'type' => 'select_from_array',
            'label' => 'Subject',
            'options' => [],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-4' ],
        ]);

        $this->crud->addField([
            'name' => 'event_date_range', // a unique name for this field
            'start_name' => 'start_at', // the db column that holds the start_date
            'end_name' => 'end_at', // the db column that holds the end_date
            'label' => 'Date Range',
            'type' => 'date_range',
            // OPTIONALS
            'start_default' => Carbon::now(), // default value for start_date
            'end_default' => Carbon::now(), // default value for end_date
            'date_range_options' => [ // options sent to daterangepicker.js
                'timePicker' => false,
                'locale' => ['format' => 'DD/MM/YYYY']
            ]
        ]);

        $this->crud->addField([
            'name' => 'script',
            'type' => 'employeeEncodingSchedule.script',
            'label' => '',
        ]);

        // add asterisk for fields that are required in EmployeeEncodeGradeScheduleRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
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

    public function getLevels(Request $request)
    {
        $employee_id    = $request->employee_id;
        $school_year_id = $request->school_year_id;
        $department_id  = $request->department_id;
        $term_type      = $request->term_type;

        $teacherSubjects    =   TeacherSubject::where('school_year_id', $school_year_id)
                                    ->where('term_type', $term_type)
                                    ->where('teacher_id', $employee_id)
                                    ->get();
        $sections           =   SectionManagement::whereIn('id', $teacherSubjects->pluck('section_id'))->get();
        $levels             =   YearManagement::where('department_id', $department_id)->whereIn('id', $sections->pluck('level_id'))->get();

        return response()->json($levels);
    }

    public function getSections(Request $request)
    {
        $employee_id    = $request->employee_id;
        $school_year_id = $request->school_year_id;
        $department_id  = $request->department_id;
        $term_type      = $request->term_type;
        $level_id       = $request->level_id;

        $teacherSubjects    =   TeacherSubject::where('school_year_id', $school_year_id)
                                    ->where('term_type', $term_type)
                                    ->where('teacher_id', $employee_id)
                                    ->get();
        $sections           =   SectionManagement::where('level_id', $level_id)->whereIn('id', $teacherSubjects->pluck('section_id'))->get();

        return response()->json($sections);
    }

    public function getSubjects(Request $request)
    {
        $employee_id    = $request->employee_id;
        $school_year_id = $request->school_year_id;
        $department_id  = $request->department_id;
        $term_type      = $request->term_type;
        $level_id       = $request->level_id;
        $section_id     = $request->section_id;

        $teacherSubjects    =   TeacherSubject::where('school_year_id', $school_year_id)
                                    ->where('term_type', $term_type)
                                    ->where('teacher_id', $employee_id)
                                    ->where('section_id', $section_id)
                                    ->get();
        $subjects           =   SubjectManagement::whereIn('id', $teacherSubjects->pluck('subject_id'))->get();

        return response()->json($subjects);
    }
}
