<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\StudentSectionAssignmentRequest as StoreRequest;
use App\Http\Requests\StudentSectionAssignmentRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\SchoolYear;
use App\Models\SectionManagement;
use App\Models\YearManagement;
use App\Models\SubjectManagement;
use App\Models\StudentSectionHistory;
use App\Models\StudentSectionAssignment;
use App\Models\Enrollment;
use App\Models\Department;

use Validator;

// use App\Models\Student;
/**
 * Class StudentSectionAssignmentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StudentSectionAssignmentCrudController extends CrudController
{

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
       
        $this->crud->setModel('App\Models\StudentSectionAssignment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/student-section-assignment');
        $this->crud->setEntityNameStrings('Student Section', 'Assign Student Sections');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in StudentSectionAssignmentRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->enableExportButtons();
        $this->crud->removeColumns(['students']);

        $this->crud->allowAccess('clone');
        $this->crud->addButtonFromView('line', 'clone', 'studentSectionAssignment.cloneAndUpdate', 'beginning'); // add a button whose 

        // SET THE DATA IF ACTION METHOD IS CREATE
        $actionMethod               = $this->crud->getActionMethod();
        $this->data['actionMethod'] = $actionMethod;

        // MORE BUTTONS
        $this->crud->data['dropdownButtons'] = [
            'print',
        ];
        $this->crud->addButtonFromView('line', 'More', 'dropdownButton', 'end');

        $this->crud->addField([
            'label'             => 'Class Code',
            'type'              => 'text',
            'name'              => 'class_code',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
        ]);

        $this->crud->addField([
            'type'  => 'custom_html',
            'name'  => 'clearfix',
            'value' => '<div class="clearfix"></div>'
        ])->afterField('class_code');

        $this->crud->addField([
            'name' => 'department_id',
            'type' => 'select_from_array',
            'label' => 'Select Department',
            'options' => Department::active()->pluck('name', 'id'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'attributes' => [
                'id' => 'searchDepartment',
            ],
        ]);

        $this->crud->addField([
            'name' => 'level_id',
            'type' => 'studentSectionAssignment.searchSectionLevel',
            'label' => 'Select Level',
            'options' => YearManagement::whereIn('department_id', Department::active()->pluck('id'))->pluck('year', 'id'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'attributes' => [
                'id' => 'searchSectionLevel',
            ],
        ]);

        $this->crud->addField([
            'label'             => 'School Year',
            'type'              => 'select_from_array',
            'name'              => 'school_year_id',
            'options'           => SchoolYear::active()->get()->pluck('schoolYear', 'id'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'attributes' => [
                'readonly' => 'readonly'
            ]
        ], 'create');

        $this->crud->addField([
            'label'             => 'School Year',
            'type'              => 'select_from_array',
            'name'              => 'school_year_id',
            'options'           => SchoolYear::get()->pluck('schoolYear', 'id'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'attributes' => [
                'readonly' => 'readonly'
            ],
            'allows_null'     => false
        ], 'update');

        $this->crud->addField([
            'label'                      => 'Section',
            'type'                       => 'select_from_array',
            'name'                       => 'section_id',
            'options'                   => [],
            'wrapperAttributes'          => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        $this->crud->addField([   // Select
            'label' => "Term",
            'type' => 'select_from_array',
            'name' => 'term_type', 
            'options' => [],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([   // color_picker
            'label' => 'Summer',
            'name' => 'summer',
            'type' => 'checkbox',
            'color_picker_options' => [
                'customClass' => 'custom-class'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([  // Select2
           'label' => "Curriculum",
           'type' => 'select2',
           'name' => 'curriculum_id', // the db column for the foreign key
           'entity' => 'curriculum', // the method that defines the relationship in your Model
           'attribute' => 'curriculum_name', // foreign key attribute that is shown to user
           'model' => "App\Models\CurriculumManagement", // foreign key model
        ]);

        $this->crud->addField([  // Select2
           'label' => "Adviser",
           'type' => 'select2',
           'name' => 'employee_id', // the db column for the foreign key
           'entity' => 'employee', // the method that defines the relationship in your Model
           'attribute' => 'full_name', // foreign key attribute that is shown to user
           'model' => "App\Models\Employee", // foreign key model
        ]);

        $this->crud->addField([ // Table
            'name' => 'student_table',
            'label' => 'Students',
            'type' => 'studentSectionAssignment.studentListTable',            
        ]);

        $this->crud->addField([
            'name' => 'students',
            'type' => 'hidden',
            'label' => '',
            'attributes' => ['id' => 'students'],
        ]);

        $this->crud->addField([ // Table
            'name' => 'fields_script',
            'type' => 'studentSectionAssignment.script',            
        ]);

        // COLUMN

        $this->crud->addColumn([  // Select2
           'label' => "Curriculum",
           'type' => 'select',
           'name' => 'curriculum_id', // the db column for the foreign key
           'entity' => 'curriculumWithTrashed', // the method that defines the relationship in your Model
           'attribute' => 'curriculum_name', // foreign key attribute that is shown to user
           'model' => "App\Models\CurriculumManagement", // foreign key model
        ]);

        $this->crud->addColumn([  // Select2
           'label' => "Adviser",
           'type' => 'select',
           'name' => 'employee_id', // the db column for the foreign key
           'entity' => 'employeeWithTrashed', // the method that defines the relationship in your Model
           'attribute' => 'full_name', // foreign key attribute that is shown to user
           'model' => "App\Models\Employee", // foreign key model
           'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('employeeWithTrashed', function ($q) use ($column, $searchTerm) {
                    $q->where('firstname', 'like', '%'.$searchTerm.'%')
                    ->orWhere('middlename', 'like', '%'.$searchTerm.'%')
                    ->orWhere('lastname', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);

        $this->crud->addColumn([
            'label'             => 'Level',
            'type'              => 'text',
            'name'              => 'level',
        ]);

        $this->crud->addColumn([
            'label'             => 'Department',
            'type'              => 'text',
            'name'              => 'department',
        ]);

        $this->crud->addColumn([
            'label'             => 'Section',
            'type'              => 'select',
            'name'              => 'section_id',
            'attribute'         => 'name',
            'entity'            => 'section',
            'model'             => 'App\Models\SectionManagement'
        ]);

        $this->crud->addColumn([
            'label'             => 'School Year',
            'type'              => 'select',
            'name'              => 'school_year_id',
            'attribute'         => 'schoolYear',
            'entity'            => 'schoolYear',
            'model'             => 'App\Models\SchoolYear'
        ]);

        $this->crud->addColumn([
            'label'             => 'Track',
            'type'              => 'text',
            'name'              => 'track',
            'attribute'         => 'year',
        ]);

        $this->crud->addColumn([
            'label'             => 'Total Students',
            'type'              => 'text',
            'name'              => 'total_students_per_section',
        ]);

        $this->crud->addColumn([
           'name' => 'summer', // The db column name
           'label' => "Summer", // Table column heading
           'type' => 'check'
        ]);

        // FILTERS
        $this->crud->addFilter([ // select2 filter
          'name' => 'school_year_id',
          'type' => 'select2',
          'label'=> 'School Year'
        ], function() {
            return SchoolYear::all()->pluck('schoolYear', 'id')->toArray();
        }, function($value) { // if the filter is active
            $this->crud->addClause('where', 'school_year_id', $value);
        });

        $this->crud->addFilter([ // select2 filter
          'name' => 'section_id',
          'type' => 'select2',
          'label'=> 'Section'
        ], function() {
            return SectionManagement::all()->pluck('name', 'id')->toArray();
        }, function($value) { // if the filter is active
            $this->crud->addClause('where', 'section_id', $value);
        });

        // $this->crud->groupBy(['section_id', 'school_year_id']);
        $this->crud->orderBy('school_year_id', 'DESC');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');
        $this->crud->setOperation('update');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // get the info for that entry
        $this->data['entry']        =   $entry  =   $this->crud->getEntry($id);
        $this->data['crud']         =   $this->crud;
        $this->data['saveAction']   =   $this->getSaveAction();
        $this->data['fields']       =   $this->crud->getUpdateFields($id);
        $this->data['title']        =   $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;

        $section    =   $entry->sectionWithTrashed;
        $level      =   $section ? $section->levelWithTrashed : null;
        $department =   $level ? $level->departmentWithTrashed : null;

        abort_if(!$section, 404, 'Section not found.');
        abort_if(!$level, 404, 'Level not found.');
        abort_if(!$department, 404, 'Department not found.');

        $department->term    =   $department->termWithTrashed;

        abort_if(!$department->term, 404, 'Department not found.');

        $this->data['section']      =   $section;
        $this->data['level']        =   $level;
        $this->data['department']   =   $department;
        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }

    public function store(StoreRequest $request)
    {
        $isExist =  $this->crud->model::where('school_year_id', $request->school_year_id)
                                        ->where('section_id', $request->section_id)
                                        ->where('term_type', $request->term_type)
                                        ->where('summer', $request->summer)
                                        ->exists();

        if($isExist) {
            \Alert::warning('Section Is Already Exist in this School Year and Term')->flash();
            return redirect()->back();
        }
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $isExist =  $this->crud->model::where('id', '!=', $request->id)
                        ->where('school_year_id', $request->school_year_id)
                        ->where('section_id', $request->section_id)
                        ->where('term_type', $request->term_type)
                        ->where('summer', $request->summer)
                        ->exists();

        if($isExist) {
            \Alert::warning('Section Is Already Exist in this School Year and Term')->flash();
            return redirect()->back();
        }
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function clone ($id)
    {
        $this->crud->hasAccessOrFail('clone');
        $this->crud->setOperation('clone');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // get the info for that entry
        $this->data['entry']        =   $entry  =   $this->crud->getEntry($id);
        $this->data['crud']         =   $this->crud;
        $this->data['saveAction']   =   $this->getSaveAction();
        $this->data['fields']       =   $this->crud->getUpdateFields($id);
        $this->data['title']        =   $this->crud->getTitle() ?? trans('Clone').' '.$this->crud->entity_name;

        $this->data['id']           =   $id;

        $section    =   $entry->sectionWithTrashed;
        $level      =   $section ? $section->levelWithTrashed : null;
        $department =   $level ? $level->departmentWithTrashed : null;

        abort_if(!$section, 404, 'Section not found.');
        abort_if(!$level, 404, 'Level not found.');
        abort_if(!$department, 404, 'Department not found.');

        $department->term    =   $department->termWithTrashed;

        abort_if(!$department->term, 404, 'Department not found.');

        $this->data['section']      =   $section;
        $this->data['level']        =   $level;
        $this->data['department']   =   $department;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('studentSectionAssignment.clone', $this->data);
    }

    public function searchStudent (Request $request)
    {
        $students = Student::join('enrollments', function ($join) use ($request) {
                                $join->on('students.studentnumber', 'enrollments.studentnumber')->where([
                                    'enrollments.school_year_id'    => SchoolYear::active()->first()->id,
                                    'enrollments.level_id'          => $request->level_id,
                                    'enrollments.deleted_at'        => null
                                ]);
                            })
                            ->where('students.studentnumber', 'LIKE', '%' . $request->phrase . '%')
                            ->orWhere('students.firstname', 'LIKE', '%' . $request->phrase . '%')
                            ->orWhere('students.middlename', 'LIKE', '%' . $request->phrase . '%')
                            ->orWhere('students.lastname', 'LIKE', '%' . $request->phrase . '%')
                            ->select('students.id', 'students.studentnumber', 'students.gender', 'students.schoolyear', 'students.level_id', 'students.firstname', 'students.middlename', 'students.lastname')
                            ->with('schoolYear')
                            ->with('yearManagement')
                            ->take(5)
                            ->get();

        return response()->json($students);
    }

    public function getStudent ($id) 
    {
        $model = $this->crud->model::find($id)->students;
        $studentArray = json_decode($model);

        $student = Student::whereIn('studentnumber', $studentArray)->get();
        return response()->json($student);
    }

    public function print($id) {
        $this->data["student_section"] = $this->crud->model::where('id', $id)->with(['section' => function ($q) { $q->with('level'); }])->first();
        $this->data["crud"] = $this->crud;
        $this->data['students'] = collect($this->data['student_section']->all_students)->sortBy('full_name')->groupBy('gender');
        // $pdf = \App::make('dompdf.wrapper');
        // $pdf->setPaper(array(0, 0, 612, 936), 'portrait');

        // $pdf->loadHTML( view('studentSectionAssignment.print', $this->data) );
        // return $pdf->stream();
        $schoollogo      = config('settings.schoollogo') ? (string)\Image::make(config('settings.schoollogo'))->encode('data-url') : null;
        $schoolmate_logo = (string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url');
     
        return view('studentSectionAssignment.new_print',$this->data, compact('schoollogo','schoolmate_logo'));
    }

    public function getSection ()
    {
        $level_id = request()->level_id;
        $sections = SectionManagement::where('level_id', $level_id)->with('track')->get(['name', 'id']);

        return response()->json($sections);
    }
}
