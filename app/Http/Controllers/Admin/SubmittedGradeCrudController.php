<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SubmittedGradeRequest as StoreRequest;
use App\Http\Requests\SubmittedGradeRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

use App\Models\SetupGrade;
use App\Models\SetupGradeItem;
use App\Models\Period;
use App\Models\SectionManagement;
use App\Models\Transmutation;

use App\Models\SchoolYear;
use App\Models\Department;
use App\Models\Employee;
use App\Models\TeacherSubject;
use App\Models\YearManagement;

use App\Models\User;

use DB;

/**
 * Class SubmittedGradeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SubmittedGradeCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | Check User Role, Must Be `School Head`
        |--------------------------------------------------------------------------
        */
        if (!backpack_auth()->user()->hasRole('School Head')) { abort(401); }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SubmittedGrade');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/submitted-grade');
        $this->crud->setEntityNameStrings('Submitted Grade', 'Submitted Grades');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in SubmittedGradeRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        
        $this->crud->setListView('submittedGrades.dashboard');

        // $this->crud->allowAccess('show');
        $this->crud->denyAccess(['create', 'delete', 'update', 'reorder']);
        $this->crud->removeColumns(['rows', 'state', 'teacher_id']);

       

        // $this->data['total_submitted_grades'] = $this->crud->model::getTotalSubmittedGrades();
        // $this->data['total_unsubmitted_grades'] = $this->crud->model::getTotalUnsubmittedGrades();

        if(!\Route::current()->parameter('school_year_id'))
        {
            $this->data['schoolYears'] = SchoolYear::all();
            $this->crud->setListView('submittedGrades.dashboard');
        }
        else if(!\Route::current()->parameter('department_id') && \Route::current()->parameter('school_year_id'))
        {
            $this->school_year_id = \Route::current()->parameter('school_year_id');
            $this->data['departments'] = Department::active()->get();
            $this->data['schoolYear'] = SchoolYear::where('id', $this->school_year_id)->first();
            $this->crud->setListView('submittedGrades.departments');
        }
        else if(\Route::current()->parameter('department_id') && \Route::current()->parameter('school_year_id') && !\Route::current()->parameter('teacher_id'))
        {   

            $this->school_year_id = \Route::current()->parameter('school_year_id');
            $this->department_id  = \Route::current()->parameter('department_id');
            $this->data['school_year'] = SchoolYear::where('id', $this->school_year_id)->first();
            $this->data['department']  = Department::with('term')->where('id', $this->department_id)->first();
           
            $this->crud->setRoute(config('backpack.base.route_prefix') . '/submitted-grade/' . $this->school_year_id . '/school-year/' . $this->department_id . '/department');

            $employee_departments = DB::table('employee_departments')->where('department_id', $this->department_id)->get();
            // dd($employee_departments);
            $employees = Employee::with('user')->whereIn('id', $employee_departments->pluck('employee_id'))->get();
            // dd($employees);
            // dd($employees->pluck('user.id'));
            $this->crud->addClause('where', 'school_year_id', $this->school_year_id);

            $this->crud->addClause('whereIn', 'teacher_id', $employees->pluck('user.id'));
            $this->crud->setListView('submittedGrades.new_list');
            $this->crud->groupBy('teacher_id');

            /*
            |--------------------------------------------------------------------------
            | Columns
            |--------------------------------------------------------------------------
            */
            $this->crud->addColumn([
                'label' => 'Employee No.',
                'type'  => 'text',
                'name'  => 'employee_no',
                'prefix' => config('settings.schoolabbr') . ' - '
            ]);

            $this->crud->addColumn([
                'label' => 'Teacher',
                'type' => 'select',
                'name' => 'teacher_id',
                'entity' => 'employee',
                'attribute' => 'full_name',
                'model' => 'App\Models\Employee',
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->whereHas('employees', function ($q) use ($column, $searchTerm) {
                        $q->where('firstname', 'like', '%'.$searchTerm.'%')
                            ->orWhere('middlename', 'like', '%'.$searchTerm.'%')
                            ->orWhere('lastname', 'like', '%'.$searchTerm.'%')
                            ->orWhere('employee_id', 'like', '%'.$searchTerm.'%');
                    });
                },
            ])->beforeColumn('template_id');

            $this->crud->addColumn([
                'label' => 'No. of Classes',
                'type'  => 'text',
                'name'  => 'no_of_classes'
            ]);

            $this->crud->addColumn([
                'label' => 'No. Period',
                'type'  => 'text',
                'name'  => 'department_period'
            ]);

            $this->crud->addColumn([
                'label' => 'Submitted',
                'type'  => 'text',
                'name'  => 'teacher_submitted_grades'
            ]);

            $this->crud->addColumn([
                'label' => 'Unsubmitted',
                'type'  => 'text',
                'name'  => 'teacher_unsubmitted_grades'
            ]);
            $this->crud->addButtonFromView('line', 'View Records', 'submittedGrade.view_records', 'beginning');
        }
        else
        {
            $this->school_year_id   =   \Route::current()->parameter('school_year_id');
            $this->department_id    =   \Route::current()->parameter('department_id');
            $this->teacher_id       =   \Route::current()->parameter('teacher_id');
            $this->data['teacher']  =   User::with('employee')->where('id', $this->teacher_id)->first();

            $levels = YearManagement::where('department_id', $this->department_id)->get();
            $sections = SectionManagement::whereIn('level_id', $levels->pluck('id'))->get();

            $this->teacherClass = TeacherSubject::where('teacher_id', $this->data['teacher']->employee->id)
                                    ->where('school_year_id', $this->school_year_id)
                                    ->whereIn('section_id', $sections->pluck('id'))
                                    ->get();

            $periods = Period::where('department_id', \Route::current()->parameter('department_id'))->get();

            $this->crud->setRoute(config('backpack.base.route_prefix') . '/submitted-grade/' . $this->school_year_id . '/school-year/' . $this->department_id . '/department/' . $this->teacher_id . '/records');

            $employee_departments = DB::table('employee_departments')->where('department_id', $this->department_id)->get();
            $employees = Employee::with('user')->whereIn('id', $employee_departments->pluck('employee_id'))->get();
            

            $this->crud->addClause('where', 'school_year_id', $this->school_year_id);
            $this->crud->addClause('whereIn', 'teacher_id', $employees->pluck('user.id'));
            $this->crud->addClause('where', 'teacher_id', $this->teacher_id);
            $this->crud->addClause('whereIn', 'period_id', $periods->pluck('id'));
            $this->crud->addClause('whereIn', 'section_id', $this->teacherClass->pluck('section_id'));
            $this->crud->addClause('whereIn', 'subject_id', $this->teacherClass->pluck('subject_id'));

            $this->crud->orderBy('section_id', 'ASC');
            $this->crud->orderBy('subject_id', 'ASC');
            $this->crud->orderBy('period_id', 'ASC');

            $this->crud->allowAccess('show');
            // $this->crud->addColumn([
            //     'label' => 'Teacher',
            //     'type' => 'select',
            //     'name' => 'teacher_id',
            //     'entity' => 'user',
            //     'attribute' => 'name',
            //     'model' => 'App\Models\User',
            //     // 'searchLogic' => function ($query, $column, $searchTerm) {
            //     //     $query->orWhereHas('employees', function ($q) use ($column, $searchTerm) {
            //     //         $q->where('firstname', 'like', '%'.$searchTerm.'%')
            //     //             ->orWhere('middlename', 'like', '%'.$searchTerm.'%')
            //     //             ->orWhere('lastname', 'like', '%'.$searchTerm.'%')
            //     //             ->orWhere('employee_id', 'like', '%'.$searchTerm.'%');
            //     //     });
            //     // },
            // ])->beforeColumn('template_id');

            // $this->crud->addColumn([
            //     'label' => 'Template',
            //     'type' => 'select',
            //     'name' => 'template_id',
            //     'entity' => 'template',
            //     'attribute' => 'name',
            //     'model' => 'App\Models\GradeTemplate'
            // ]);

            // $this->crud->addColumn([
            //     'label' => 'School Year',
            //     'type' => 'select',
            //     'name' => 'school_year_id',
            //     'entity' => 'schoolYear',
            //     'attribute' => 'schoolYear',
            //     'model' => 'App\Models\SchoolYear'
            // ]);

            $this->crud->addColumn([
                'label' => 'Level',
                'type' => 'text',
                'name' => 'level'
            ]);

            $this->crud->addColumn([
                'label' => 'Section',
                'type' => 'select',
                'name' => 'section_id',
                'entity' => 'section',
                'attribute' => 'name',
                'model' => 'App\Models\SectionManagement'
            ]);

            $this->crud->addColumn([
                'label' => 'Subject',
                'type' => 'select',
                'name' => 'subject_id',
                'entity' => 'subject',
                'attribute' => 'subject_title',
                'model' => 'App\Models\SubjectManagement'
            ]);

            $this->crud->addColumn([
                'label' => 'Submitted',
                'type' => 'select_from_array',
                'name' => 'submitted',
                'options' => [
                    0 => 'No',
                    1 => 'Yes'
                ],
            ]);

            $this->crud->addColumn([
                'label' => 'Period',
                'type' => 'select',
                'name' => 'period_id',
                'entity' => 'period',
                'attribute' => 'name',
                'model' => 'App\Models\PeriodManagement'
            ]);

            $this->crud->addColumn([
                'label' => 'Submitted Date',
                'type' => 'datetime',
                'format' => 'MMMM DD, YYYY | hh:mm A', 
                'name' => 'submitted_at',
            ]);

            $this->crud->addColumn([
                'label' => 'Published',
                'type' => 'select_from_array',
                'name' => 'is_published',
                'options' => [
                    0 => 'No',
                    1 => 'Yes'
                ],
            ]);

            $this->crud->addFilter([ // dropdown filter
              'name' => 'status',
              'type' => 'dropdown',
              'label'=> 'Status'
            ], [
              21 => 'Submitted',
              0 => 'Unsubmitted',
            ], function($value) { // if the filter is active
                $this->crud->addClause('where', 'submitted', $value);
            });

            $this->crud->addButtonFromView('line', 'Publish', 'submittedGrade.publish', 'beginning');
            $this->crud->addButtonFromView('line', 'Reopen', 'submittedGrade.reopen', 'beginning');
            $this->crud->setListView('submittedGrades.teacher_records');

        }
      
       
    }

    public function show($id)
    {
        $this->crud->hasAccessOrFail('show');
        $this->crud->setOperation('show');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.preview').' '.$this->crud->entity_name;

        $this->school_year_id   =   \Route::current()->parameter('school_year_id');
        $this->department_id    =   \Route::current()->parameter('department_id');
        $this->teacher_id       =   \Route::current()->parameter('teacher_id');
        $employee_departments   =   DB::table('employee_departments')->where('department_id', $this->department_id)->get();
        $employees  = Employee::with('user')->whereIn('id', $employee_departments->pluck('employee_id'))->get();

        $model  =   $this->crud->model::where('id', $id)
                        ->where('school_year_id', $this->school_year_id)
                        ->where('teacher_id', $this->teacher_id)
                        ->whereIn('teacher_id', $employees->pluck('user.id'))
                        ->first();
        if(!$model)
        {
            abort(403);
        }
        $request = new Request;
        $request->merge([
            'school_year_id' => $model->school_year_id, 
            'subject_id' => $model->subject_id, 
            'section_id' => $model->section_id, 
            'term_type' => $model->term_type, 
            'period_id' => $model->period_id,
            'teacher_id' => $model->teacher_id
        ]);

        // Get Top Column Header
        $topcolumnheader = self::topcolumnheader($request);

        // Get Sub Column Header
        $subcolumnheader = self::subcolumnheader($request);

        // Get Transmutation Table
        $transmutation = Transmutation::where('id', $this->data['entry']->transmutation_id)->first();

        // Get Tabs
        $tabs                               = self::getTabsPeriod($request);
        $this->data['transmutation_table']  = $transmutation;
        $this->data['topcolumnheader']      = $topcolumnheader;
        $this->data['subcolumnheader']      = $subcolumnheader;
        $this->data['tabs']                 = $tabs;
        $this->data['rows']                 = $model->rows;
        $this->data['template_id']          = $model->template_id;
        $this->data['school_year_id']       = $model->school_year_id;
        $this->data['subject_id']           = $model->subject_id;
        $this->data['section_id']           = $model->section_id;
        $this->data['term_type']            = $model->term_type;
        $this->data['period_id']            = $model->period_id;
        $this->data['teacher_id']           = $model->teacher_id;

        // remove preview button from stack:line
        $this->crud->removeButton('show');
        $this->crud->removeButton('delete');

        // remove bulk actions colums
        $this->crud->removeColumns(['blank_first_column', 'bulk_actions']);

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('submittedGrades.show', $this->data);
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

    public function reopen ($id)
    {
        $model = $this->crud->model::find($id);

        if($model) {
            $model->submitted = 0;
            $model->save();
            \Alert::success("Successfully Reopened Grade")->flash();
        }
     
        return redirect('admin/submitted-grade');
    }

    public function publish ($id, $publish)
    {
        $model = $this->crud->model::find($id);

        if($model) {
            $model->is_published = $publish === 'publish' ? 1 : 0;
            $model->save();
            \Alert::success("Successfully " . ucfirst($publish) . " Grade")->flash();
        }
     
        return redirect()->back();
    }


    private function topcolumnheader($request)
    {
        $header = SetupGrade::where('is_approved', 1)
                                ->where('period_id', $request->period_id)
                                ->where('teacher_id', $request->teacher_id)
                                // ->where('template_id', $request->template_id)
                                ->where('subject_id', $request->subject_id)
                                ->where('section_id', $request->section_id)
                                ->with(['setupGradeItems' => function ($query) {
                                    $query->where('parent_id', null);
                                }])->first();

        if($header !== null) {
            $header = $header->setupGradeItems;
        }

        $array = [];
        // $json = json_encode($header);
        $studentinfo = [
            'name'          =>  'studentnumber', 
            'datafield'     =>  'string', 
            'text'          =>  'Student No.', 
            'editable'      =>  false,
            'parent'        =>  null
        ];

        array_push($array, $studentinfo);

        $studentinfo = [
            'name' => 'studentinfo', 
            'datafield' => 'string', 
            'text' => 'Student Information', 
            'editable'=>false,
            'parent'=>null
        ];

        array_push($array, $studentinfo);

        if($header !== null) {
            foreach($header as $row) {
                $data = [
                    'name' => $row->id,
                    'datafield' => 'string',
                    'text' => $row->type == 'percent' ? $row->name . " " . $row->max . "%" : $row->name . " " . $row->max ."pts",
                    'editable' => false,
                    'parent' => null,
                ];

                array_push($array, $data);
            }
        }
        $columns = [
            ["columns" => $array]
        ];

        return $columns;
    }

    private function subcolumnheader($request)
    {
        $isEditableColumn = true;

        // Get Encode Grade If Exists
        $encodeGrade =  $this->crud->model::where([
                            // 'template_id' => $request->template_id,
                            'subject_id' => $request->subject_id,
                            'section_id' => $request->section_id,
                            'period_id' => $request->period_id,
                            'teacher_id' => $request->teacher_id,
                        ]);

        if($encodeGrade->exists()) { 
            if($encodeGrade->first()->submitted) {
                $isEditableColumn = false;
            } else {
                $isEditableColumn = true;
            }
        }

        $getTrees = SetupGrade::where([
                                    'teacher_id' => $request->teacher_id,
                                    'subject_id' => $request->subject_id,
                                    'section_id' => $request->section_id,
                                    'period_id' => $request->period_id,
                                ])->with([
                                'setupGradeItems' => function ($query) {

                                }])->first();

        if($getTrees === null) {
            // return null;
            $getTrees = [];
        } else {
            $getTrees = $getTrees->setupGradeItems;
        }

        // return $getTrees;
        // Check If Trees Is NULL then then return immediately
        if(count($getTrees) === 0) {
            return response()->json($getTrees);
        }

        // HPS: WS
        $hps_ws = [];

        // Container: Concatenate corresponding to their parents and childs (parent_id's).
        $reformParentChild = [];

        //  Get All Parents If (parent_id == null)
        foreach ($getTrees as $tree) {
            if($tree->parent_id == null) {
                $reformParentChild[$tree->id] = [];
                array_push($hps_ws, $tree);
            }
        }

        //  Get All Child That Has Parent If (parent_id !== null)
        foreach ($getTrees as $child) {
            if($child->parent_id !== null) {
                foreach ($reformParentChild as $key => $reform) {
                    if($child->parent_id == $key) {
                        $reformParentChild[$key][] = $child;
                    }
                }
            }
        }

        $array = [];

        // Must always 1st item of the array
        $studentfullname =  [
                                'datafield' => 'fullname', 
                                'text' => "Learner's Name", 
                                'editable'=>false,
                                'columngroup'=>'studentinfo',
                                'width' => '300px'
                            ];

        array_push($array, $studentfullname);

        $counter = 1;

        foreach ($reformParentChild as $key => $child) 
        {
            $hps = 0;

            foreach($child as $row) {

                //  2nd item on array
                $data = [
                    'datafield' => camel_case($row->name) . '_' . (string)$row->id,
                    'text' => $row->type === 'percent' ? $row->name . " (" . $row->max . "%)" : $row->name,
                    'editable' => $isEditableColumn,
                    'itemEditableCell' => true, // Set To Always true
                    'columngroup' => $row->parent_id,
                    'cellsalign' => 'center',
                    'cellsformat' => 'd',
                    'max' => $row->max,
                    'type' => $row->type,
                    'columntype' => 'numberinput',
                    'order' => $counter,
                    'width' => '60px',
                    'aggregates' => null,
                ];
                array_push($array, $data);

                $hps += $row->max;
            }
            //  TOTAL
            $total = [
                'datafield' => 'total-' . $key,
                'text' => "<b>Total</b>",
                'editable' => false,
                'columngroup' => $key,
                'cellsalign' => 'center',
                'cellsformat' => 'd',
                'columntype' => 'numberinput',
                'validation' => null,
                'totalOrder' => $counter,
                'width' => '100px'
            ];
            array_push($array, $total);

            //  PS
            $ps = [
                'datafield' => 'ps-' . $key,
                'text' => "<b>PS</b>",
                'editable' => false,
                'columngroup' => $key,
                'cellsalign' => 'center',
                'cellsformat' => 'd',
                'columntype' => 'numberinput',
                'psOrder' => $counter,
                'validation' => null,
                'hps-' . $counter => $hps,
                'width' => '60px'
            ];
            array_push($array, $ps);

            //  WS
            $ws = [
                'datafield' => 'ws-' . $key,
                'text' => "<b>WS</b>",
                'editable' => false,
                'columngroup' => $key,
                'cellsalign' => 'center',
                'cellsformat' => 'd',
                'columntype' => 'numberinput',
                'wsOrder' => $counter,
                'validation' => null,
                'width' => '60px'
            ];
            array_push($array, $ws);

            $counter++;
        }

        //  INITIAL GRADE
        $initialGrade = [
            'datafield' => 'initial-grade',
            'text' => "<b>Initial Grade</b>",
            'editable' => false,
            'columngroup' => $key,
            'cellsalign' => 'center',
            'cellsformat' => 'd',
            'columntype' => 'numberinput',
            'validation' => null,
            'width' => '100px'
        ];
        array_push($array, $initialGrade);

        //  QUARTERLY GRADE / (TRANSMUTED GRADE)
        $quarterlyGrade = [
            'datafield' => 'quarterly-grade', 
            'text' => "<b>Quarterly Grade</b>",
            'editable' => false,
            'columngroup' => $key,
            'cellsalign' => 'center',
            'cellsformat' => 'd',
            'columntype' => 'numberinput',
            'validation' => null,
            'width' => '150px'
        ];
        array_push($array, $quarterlyGrade);

        $columns = [
            [
                "columns" => $array,
                "counter" => $counter,
                "hps_ws" => $hps_ws,
                'submitted' => $isEditableColumn
            ]
        ];

        return $columns;  
    }


    private function getTabsPeriod ($request)
    {
        $section = SectionManagement::where('id', $request->section_id)->with('level')->first();
        $periods = [];
        if($section !== null) {
            $periods = Period::where('department_id', $section->level->department_id)->orderBy('sequence', 'asc')->get()->toArray();
        }

        return $periods;
    }

    // public function showDepartments ($school_year_id)
    // {
    //     $departments = Department::active()->get();
    //     $schoolYear  = SchoolYear::where('id', $school_year_id)->first();
    //     $crud        = $this->crud;

    //     return view('submittedGrades.departments', compact('departments', 'schoolYear', 'crud'));
    // }

    // public function showTeachers ($school_year_id, $department_id)
    // {
    //     $this->crud->setModel('App\Models\TeacherAssignment');
    //     $crud        = $this->crud;
    //     dd($crud);
    //     return view('submittedGrades.new_list', compact('departments', 'schoolYear', 'crud'));
    // }
}
