<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SetupGradeRequest as StoreRequest;
use App\Http\Requests\SetupGradeRequest as UpdateRequest;
use App\Http\Requests\SetupGradeAddItemRequest as AddItemRequest;
use Illuminate\Http\Request;

use App\Models\SetupGrade;
use App\Models\GradeTemplate;
use App\Models\SubjectManagement;
use App\Models\SectionManagement;
use App\Models\StudentSectionAssignment;
use App\Models\Student;
use App\Models\SubjectMapping;
use App\Models\YearManagement;
use App\Models\Period;
use App\Models\EncodeGrade;
use App\Models\SetupGradeItem;
use App\Models\SchoolYear;
use App\Models\TeacherSubject;
use App\Models\TermManagement;

use Validator;
use Auth;


/**
 * Class SetupGradeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SetupGradeCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SetupGrade');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/setup-grade');
        $this->crud->setEntityNameStrings('Grade Setup', 'Grade Setup');


        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in SetupGradeRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->allowAccess('reorder');
        $this->crud->enableReorder('name', 2);
        $this->crud->removeButtons(['create']);
        $this->crud->addButtonFromView('top', 'Add Setup Grade', 'gradeSetup.addSetupGrade', 'beginning');

        $this->crud->addColumn([
            'name' => 'school_year_id',
            'label' => 'School Year',
            'type' => 'select',
            'entity' => 'schoolYear',
            'attribute' => 'schoolYear',
            'model' => "App\Models\SchoolYear",
        ]);

        $this->crud->addColumn([
            'name' => 'template_id',
            'label' => 'Template Name',
            'type' => 'select',
            'entity' => 'template',
            'attribute' => 'name',
            'model' => "App\Models\GradeTemplate",
        ]);

        $this->crud->addColumn([
            'name' => 'level',
            'label' => 'Level',
            'type' => 'text',
        ])->afterColumn('template_id');

        $this->crud->addColumn([
            'name' => 'section_id',
            'label' => 'Section',
            'type' => 'select',
            'entity' => 'section',
            'attribute' => 'name',
            'model' => "App\Models\SectionManagement",
        ]);

        $this->crud->addColumn([
            'name' => 'term_type',
            'label' => 'Term',
            'type' => 'text',
        ])->afterColumn('section_id');

        $this->crud->addColumn([
            'name' => 'period_id',
            'label' => 'Period',
            'type' => 'select',
            'entity' => 'period',
            'attribute' => 'name',
            'model' => "App\Models\Period",
        ]);

        $this->crud->addColumn([
            'name' => 'subject_id',
            'label' => 'Subject',
            'type' => 'select',
            'entity' => 'subject',
            'attribute' => 'subject_code',
            'model' => "App\Models\SubjectManagement",
        ])->beforeColumn('name');

        $this->crud->addColumn([
            'name' => 'name',
            'label' => 'Component Name',
        ]);

        $this->crud->addColumn([
            'label' => 'Assigned To',
            'type' => 'select',
            'name' => 'teacher_id',
            'entity' => 'teacher',
            'attribute' => 'name',
            'model' => 'App\Model\User'
        ]);

        $this->crud->addColumn([
            'label' => 'Approved',
            'type' => 'text',
            'name' => 'is_approved',
        ]);

        $this->crud->addColumn([
            'label' => 'Approved By',
            'type' => 'select',
            'name' => 'approved_by',
            'entity' => 'approveBy',
            'attribute' => 'name',
            'model' => 'App\Models\User'
        ]);


        // FILTERS
        $this->crud->addFilter([ // select2 filter
          'name' => 'levels',
          'type' => 'select2',
          'label'=> 'Level'
        ], function() {
            return YearManagement::all()->keyBy('id')->pluck('year', 'id')->toArray();
        }, function($value) { // if the filter is active
            $this->crud->query = $this->crud->query->whereHas('section',
              function ($query) use ($value) {
                  $query->where('level_id', $value);
            });
        });

        $this->crud->addFilter([ // select2 filter
            'name' => 'school_year_id',
            'type' => 'select2',
            'label'=> 'School Year'
        ], function() {
            return SchoolYear::all()->keyBy('id')->pluck('schoolYear', 'id')->toArray();
        }, function($value) { // if the filter is active
            $this->crud->addClause('where', 'school_year_id', $value);
        });

        if(backpack_auth()->user()->hasRole('School Head')) {
            
        } else {
            $this->crud->addClause('where', 'teacher_id', backpack_auth()->user()->id);
        }


        
        // $this->crud->addButtonFromView('top', 'gtemp', 'gradeSetup.quarter', 'end');
        // $this->crud->addButtonFromView('top', 'lookup', 'gradeSetup.lookup', 'end');
        $this->crud->addButtonFromView('line', 'update', 'gradeSetup.edit', 'end');
        $this->crud->addButtonFromView('line', 'custom_delete', 'gradeSetup.delete', 'end');
        $this->crud->addButtonFromView('line', 'approve', 'gradeSetup.approve', 'end');
        // $this->crud->addButton('line', 'delete', 'gradeSetup.delete', 'end');
        // Access

        $this->crud->enableDetailsRow();
        // $this->crud->allowAccess('details_row');

        $this->crud->removeColumns(['name', 'type', 'actions']);
        // $this->crud->removeFields(['teacher_id']);
        $this->crud->removeButtons(['delete', 'reorder']);
        
        $this->crud->setListView('gradeSetup.list');
        $this->crud->setEditView('gradeSetup.edit');
        $this->crud->setCreateView('gradeSetup.create');
        
        if(SchoolYear::active()->first() !== null) {
            $this->crud->addClause('where', 'school_year_id', SchoolYear::active()->first()->id);
        }

        if(!backpack_auth()->user()->hasRole('School Head')) {
            $this->crud->denyAccess('details_row');
            $this->crud->addClause('where', 'teacher_id', backpack_auth()->user()->id);
        } else {
            $this->crud->addFilter([ 
              'type' => 'simple',
              'name' => 'show_my_items_only',
              'label'=> 'Show My Items Only'
            ],
            false, // the simple filter has no values, just the "Draft" label specified above
            function() { // if the filter is active (the GET parameter "draft" exits)
                $this->crud->addClause('where', 'teacher_id', backpack_auth()->user()->id); 
            });
        }
        if (!$this->request->has('order')) {
            $this->crud->orderBy('is_approved');
        }
        $this->crud->allowAccess('revisions');
    }

    public function showDetailsRow ($id)
    {
        $unique_id = (int)$id;
        $crud = $this->crud;

        $setupGrade = SetupGrade::where('id', $id)->first();
        $selectEntries = SetupGradeItem::where('setup_grade_id', $id)->get();

        $entries = $this->crud->getEntries();
        return view('gradeSetup.setup_grade_details_row', compact('entries', 'setupGrade', 'selectEntries', 'crud', 'unique_id'));
    }

    public function destroy ($id)
    {      
        $this->crud->model::findOrFail($id)->delete();
        SetupGradeItem::where('setup_grade_id', $id)->delete();
    }

    public function deleteItem($id)
    {
        $response = [
            'error' => false,
            'message'=> null,
            'data' => null 
        ];

        // Check If Item Is Already `Approved` And Has Role Of Teacher
        $childItem = SetupGradeItem::where('id', $id);

        // Check If Item Not Exists
        if(!$childItem->exists()) {
            $response['error'] = true;
            $response['mesasge'] = "Item Not Found";
            return $response;
        }

        if(!backpack_auth()->user()->hasRole('Coordinator')) {
            if(backpack_auth()->user()->hasRole("Teacher")) {
                if($childItem->first()->is_approved) {
                    $response["error"] = true;
                    $response["message"] = "Unable To Delete. This Item Is Already Approved.";
                    return $response;
                }
            } else {
                $response["error"] = true;
                $response["message"] = "Unauthorized.";
                return $response;
            }
        }

        // Get The Parent ID
        $parentItem = $this->crud->model::where('id', $childItem->first()->setup_grade_id)->with('setupGradeItems')->first();

        if($parentItem) {

            // Check If This Item Is Already In Used
            if(count($sg->encode_grades) > 0) {
                $response["error"] = true;
                $response["message"] = "Unable To Delete, This Item Is Already In Used.";
                return $response;
            }

            // Delete The Item And Children Items
            SetupGradeItem::where('parent_id', $childItem->first()->id)->delete();
            $childItem->delete();
            
            $response['message'] = 'Successfully Deleted Item.';
            return $response;
        }

        $response['error'] = true;
        $response['message'] = "The Parent ID Not Found";
        return $response;
    }

    private function simplified ($request)
    {
        $data = [
            [
                "name"          => "Written Works",
                "max"           => (float)$request->written_work['percentage'],
                "type"          => "percent",
                "description"   => "Written Works",
                "childrens"     =>  [
                                        "name"    => "Quizzes",
                                        "items"   => 1,
                                        "max"     => (float)$request->written_work['raw'],
                                        "type"    => "raw"
                                    ]
            ],
            [
                "name"          => "Performance Tasks",
                "max"           => (float)$request->performance_task['percentage'],
                "type"          => "percent",
                "description"   => "Performance Tasks",
                "childrens"     =>  [
                                        "name"    => "Performance Tasks",
                                        "items"   => 1,
                                        "max"     => (float)$request->performance_task['raw'],
                                        "type"    => "raw"
                                    ]
            ],
            [
                "name"          => "Quarterly Assessment",
                "max"           => (float)$request->quarterly_assessment['percentage'],
                "type"          => "percent",
                "description"   => "Quarterly Assessment",
                "childrens"     =>  [
                                        "name"    => "Quarterly Assessments",
                                        "items"   => 1,
                                        "max"     => (float)$request->quarterly_assessment['raw'],
                                        "type"    => "raw"
                                    ]
            ],
        ];

        return $data;
    }

    private function detailed ($request)
    {
        $data = [
            [
                "name"          => "Written Works",
                "max"           => (float)$request->written_work['percentage'],
                "type"          => "percent",
                "description"   => "Written Works",
                "childrens"     =>  [
                                        "name"    => "Quiz",
                                        "items"   => (int)$request->written_work['no_of_items'],
                                        "max"     => 10,
                                        "type"    => "raw"
                                    ]
            ],
            [
                "name"          => "Performance Tasks",
                "max"           => (float)$request->performance_task['percentage'],
                "type"          => "percent",
                "description"   => "Performance Tasks",
                "childrens"     =>  [
                                        "name"    => "PT",
                                        "items"   => (int)$request->performance_task['no_of_items'],
                                        "max"     => 10,
                                        "type"    => "raw"
                                    ]
            ],
            [
                "name"          => "Quarterly Assessment",
                "max"           => (float)$request->quarterly_assessment['percentage'],
                "type"          => "percent",
                "description"   => "Quarterly Assessment",
                "childrens"     =>  [
                                        "name"    => "QA",
                                        "items"   => (int)$request->quarterly_assessment['no_of_items'],
                                        "max"     => 100,
                                        "type"    => "raw"
                                    ]
            ],
        ];
        return $data;
    }


    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $schoolYear = SchoolYear::active()->first();

        if($schoolYear === null) {
            \Alert::warning("No School Year Active, Please Select School Year")->flash();
            return redirect('admin/setup-grade');
        }

        // VALIDATE IF TEMPLATE IS ALREADY EXIST
        $isExist = $this->crud->model::where('subject_id', $request->subject_id)
                                        ->where('section_id', $request->section_id)
                                        ->where('term_type', $request->term_type)
                                        ->where('period_id', $request->period_id)
                                        ->where('school_year_id', $schoolYear->id)
                                        ->exists();
        if($isExist) {
            \Alert::warning("Template Is Already Exist or Already Taken")->flash();
            return redirect()->back();
        }

        // 1. create a parent
        // 2. create a child and bind the id of their parents after they created which is the step no. 1 

        $template_id  = $request->template_id;
        $subject_id   = $request->subject_id;
        $section_id   = $request->section_id;
        $term_type    = $request->term_type;
        $period_id    = $request->period_id;
        $teacher_id   = backpack_auth()->user()->id;
        $data = [];

        if($request->class_record_type === 'detailed') {
            $data = $this->detailed($request);
        }

        if($request->class_record_type === 'simplified') {
            $data = $this->simplified($request);
        }
        

        $setupGrade                 = new SetupGrade;
        $setupGrade->school_year_id = $schoolYear->id;
        $setupGrade->template_id    = $template_id;
        $setupGrade->subject_id     = $subject_id;
        $setupGrade->section_id     = $section_id;
        $setupGrade->term_type      = $term_type;
        $setupGrade->period_id      = $period_id;
        $setupGrade->teacher_id     = $teacher_id;

        $items = [];

        if($setupGrade->save()) {
            foreach ($data as $key => $parent) {

                $setupGradeItem                     = new SetupGradeItem;
                $setupGradeItem->setup_grade_id     = $setupGrade->id;
                $setupGradeItem->name               = $parent["name"];
                $setupGradeItem->description        = $parent["description"];
                $setupGradeItem->type               = $parent["type"];
                $setupGradeItem->max                = $parent["max"];
                $setupGradeItem->parent_id          = null;
                $setupGradeItem->lft                = $key + 1;

                if($setupGradeItem->save()) {
                    // if parent has childrens
                    $i = 1;
                    for($i; $i <= $parent["childrens"]["items"]; $i++)
                    {
                        $child                                  = $parent["childrens"];

                        $setupGradeItemChild                    = new SetupGradeItem;
                        $setupGradeItemChild->setup_grade_id    = $setupGrade->id;
                        $setupGradeItemChild->name              = $child["name"] . ' ' . $i;
                        $setupGradeItemChild->description       = $child["name"] . ' ' . $i;
                        $setupGradeItemChild->type              = $child["type"];
                        $setupGradeItemChild->max               = $child["max"];
                        $setupGradeItemChild->parent_id         = $setupGradeItem->id;
                        $setupGradeItemChild->lft               = $i;
                        $setupGradeItemChild->save();
                    }
                }

            }
        }

        // $this->crud->model->insert([
        //     'name' => "List",
        //     'created_at' => Carbon\Carbon::now(),
        // ]);


        // $request->request->set('teacher_id', backpack_auth()->user()->id);

        // $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        \Alert::success("Successfully Created")->flash();
        return redirect()->back();
    }

    public function addItem (Request $request)
    {
        $validator =    Validator::make($request->all(), [
                            'name'         => 'string',
                            'description'  => 'max:190',
                            'max'          => 'required|numeric',
                            'type'         => 'nullable|in:null,percent,raw',
                        ]);

        if($validator->fails()) {
            $errorMessages = $validator->getMessageBag()->getMessages();
            $errorMessage  = '';
            foreach ($errorMessages as $message) {
                foreach($message as $m) {
                    $errorMessage .= '- ' . $m . '<br/>';
                }
            }
            return response()->json(["status" => "ERROR", "data" => null, "message" => $errorMessage]);
        }

        // PROCESS OF ADDING ITEM
        $setupGradeItem                 = new SetupGradeItem;
        $setupGradeItem->setup_grade_id = $request->id; 
        $setupGradeItem->name           = $request->name;
        $setupGradeItem->type           = $request->type ?? '-';
        $setupGradeItem->max            = $request->max;
        $setupGradeItem->description    = $request->description;
        
        if($setupGradeItem->save()) { 
            return response()->json(["data" => $setupGradeItem, "status" => "OK", "message" => "Successfully Added Item"]);
        } else {
            return response()->json(["data" => null, "status" => "ERROR", "message" => "Something Went Wrong, Please Try Again..."]);
        }
    }


    public function deleteAll ($id)
    {

    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function saveReorder()
    {
        $this->crud->hasAccessOrFail('reorder');
        $this->crud->setOperation('reorder');
        $this->crud->setModel('App\Models\SetupGradeItem');

        $all_entries = \Request::input('tree');

        if (count($all_entries)) {
            $count = $this->crud->updateTreeOrder($all_entries);
        } else {
            return false;
        }

        return 'success for '.$count.' items';
    }

    public function getSubjects (Request $request)
    {
        $schoolYearActive   = SchoolYear::active()->first();
        $teacherSubject     = TeacherSubject::where('teacher_id', backpack_auth()->user()->employee_id)->where('school_year_id', $schoolYearActive->id)->get();
        $teacherSubject     = $teacherSubject->pluck('subject_id');
        $section            = SectionManagement::where('id', $request->section_id)->first();

        // Extrack All Subject ID's From SubjectMapping Model
        $subject_ids = null;
        if($section) {
            $subject_ids = SubjectMapping::where([
                'level_id'      => $section->level_id,
                'track_id'      => $section->track_id,
                'curriculum_id' => $section->curriculum_id,
                'term_type'     => request()->term_type,
            ])->first();
        }

        if($subject_ids == null) { return response()->json([]); }

        $subject_ids = collect($subject_ids->subjects)->pluck('subject_code');
        $subject_ids = collect($subject_ids)->filter(function ($item, $key) use ($teacherSubject) {
            return $teacherSubject->contains($item) ?  $item : null;
        });

        $subjects = SubjectManagement::whereIn('id', $subject_ids)->get();

        return response()->json($subjects);
    }

    public function getPeriods ()
    {
        $section = SectionManagement::where('id', request()->section_id)->with('level')->first();
        $periods = null;

        if($section) {
            if($section->level !== null) {
                $periods = Period::where('department_id', $section->level->department_id)->get();
            }
        }

        return response()->json($periods);
    }

    public function getTerms ()
    {
        $section = SectionManagement::where('id', request()->section_id)->with('level')->first();
        $terms = null;

        if($section) {
            if($section->level !== null) {
                $term = TermManagement::where('department_id', $section->level->department_id)->first();
                if($term !== null) {
                    return response()->json($term->ordinal_terms);
                } else {
                    return null;
                }
            }
        }

        return null;
    }
    // Class Roster

    public function studentroster(Request $request){

        $section        = SectionManagement::where('id',$request->section_id)->first();
        $template       = GradeTemplate::where('id', $request->template_id)->first();
        $setup_grade    = SetupGrade::where("section_id", $section->id)->first();
        $studentsAssign = StudentSectionAssignment::where('section_id', $setup_grade->section_id)->first();

        $students = [];
        if($studentsAssign !== null) {
            $students       = $studentsAssign == null ? null : json_decode($studentsAssign->students);
            $students       = Student::whereIn('studentnumber', $students)->orderBy('gender','asc')->orderBy('lastname','asc')->get();
        }

        $array = [];
        
        foreach($students as $student) {
            $data = [
                'lrn'               => $student->lrn ?? '',
                'studentnumber'     => $student->studentnumber,
                'fullname'          => strtoupper($student->full_name_last_first),
                'gender'            => strtoupper($student->gender),
            ];
            array_push($array, $data);
        }

        return $array;
    }

    public function topcolumnheaderroster(Request $request){

        $header = SetupGrade::where('parent_id', null)
                    ->where('teacher_id', backpack_auth()->user()->id)
                    ->where('template_id', $request->template_id)
                    ->where('subject_id', $request->subject_id)
                    ->where('section_id', $request->section_id)
                    ->where('period_id', $request->period_id)
                    ->where('is_approved', 1)
                    ->get();

        $array = [];
        // $json = json_encode($header);

        $studentinfo = [
            'name'      => 'lrn', 
            'datafield' => 'string', 
            'text'      => 'LRN', 
            'editable'  => false,
            'parent'    => null
        ];

        array_push($array, $studentinfo);

        $studentinfo = [
            'name' => 'studentnumber', 
            'datafield' => 'string', 
            'text' => 'Student Number', 
            'editable'=>false,
            'parent'=>null
        ];

        array_push($array, $studentinfo);

        $studentinfo = [
            'name' => 'fullname', 
            'datafield' => 'string', 
            'text' => 'Student Information', 
            'editable'=>false,
            'parent'=>null
        ];

        array_push($array, $studentinfo);


        $columnsroster = [
            ["columnsroster" => $array]
        ];

        return $columnsroster;
    }


    public function topcolumnheader(Request $request){

        $header = SetupGrade::where('is_approved', 1)
                                ->where('period_id', $request->period_id)
                                ->where('teacher_id', backpack_auth()->user()->id)
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

    public function subcolumnheader(Request $request){

        $isEditableColumn = true;

        // Get Encode Grade If Exists
        $encodeGrade =  EncodeGrade::where([
                            // 'template_id' => $request->template_id,
                            'subject_id' => $request->subject_id,
                            'section_id' => $request->section_id,
                            'period_id' => $request->period_id,
                            'teacher_id' => backpack_auth()->user()->id,
                        ]);

        if($encodeGrade->exists()) { 
            if($encodeGrade->first()->submitted) {
                $isEditableColumn = false;
            } else {
                $isEditableColumn = true;
            }
        }

        $getTrees = SetupGrade::where([
                                    'teacher_id' => backpack_user()->id,
                                    'subject_id' => $request->subject_id,
                                    'section_id' => $request->section_id,
                                    'period_id' => $request->period_id,
                                    'is_approved' => 1,
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

        // dd($hps_ws);
        
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

        //  REMARKS 
        $remarks = [
            'datafield' => 'remarks', 
            'text' => "<b>Remarks</b>",
            'editable' => false,
            'columngroup' => $key,
            'cellsalign' => 'center',
            'cellsformat' => 'd',
            'validation' => null,
            'width' => '150px'
        ];
        array_push($array, $remarks);

        $columns = [
            [
                "columns" => $array,
                "counter" => $counter,
                "hps_ws" => $hps_ws,
                'submitted' => $isEditableColumn
            ]
        ];

        return $columns;


        // // ----------------------------------------------------------------------------
          
    }

    public function datafield(Request $request){
        // Static Fields
        // { text: 'Student No.', datafield: 'studentnumber', columngroup: 'studentinfo', editable: false},
        // { text: 'Student Name', datafield: 'fullname', columngroup: 'studentinfo', editable: false},
        $header = SetupGrade::where('parent_id', '!=', null)
                            ->where('teacher_id', backpack_auth()->user()->id)
                            ->where('template_id', $request->template_id)
                            ->where('subject_id', $request->subject_id)
                            ->where('section_id', $request->section_id)
                            ->where('is_approved', 1)
                            ->orderBy('lft', 'ASC')
                            ->get();
        $array = [];
        // $json = json_encode($header);
        $studentfullname = [
            'name' => 'fullname', 
            'type' => 'string', 
        ];

        array_push($array, $studentfullname);

        foreach($header as $row) {
            $data = [
                'name' => $row->id,
                'type' => 'integer'
            ];

            array_push($array, $data);
        }

        $columns = [
            ["columns" => $array]
        ];

        return $columns;
    }

    public function studentdata(Request $request){

        $section        = SectionManagement::where('id',$request->section_id)->first();
        $template       = GradeTemplate::where('id', $request->template_id)->first();
        $setup_grade    = SetupGrade::where("section_id", $section->id)->first();
        $studentsAssign = StudentSectionAssignment::where('section_id', $setup_grade->section_id)->first();
        $students       = [];

        if($studentsAssign !== null) {
            $students       = json_decode($studentsAssign->students);
            $students       = Student::whereIn('studentnumber', $students)->orderBy('gender','asc')->orderBy('lastname','asc')->get();
        }

        $array = [];
        
        foreach($students as $student) {
            $data = [
                'studentnumber'     => $student->studentnumber,
                'fullname'          => strtoupper($student->fullname_last_first),
            ];

            array_push($array, $data);
        }

        $columns = $array;

        return $columns;
    }

    public function hps(Request $request)
    {
        $setupGrade = SetupGrade::where('teacher_id', backpack_auth()->user()->id)
                                ->where('template_id', $request->template_id)
                                ->where('subject_id', $request->subject_id)
                                ->where('section_id', $request->section_id)
                                ->where('period_id', $request->period_id)
                                ->with(['setupGradeItems' => function ($query) {
                                    $query->where('parent_id', '!=', null);
                                    // $query->orderBy('lft', 'ASC');
                                    // $query->pluck('id', 'max');
                                }])->first();

        if($setupGrade === null) {
            return null;
        }
        $items = collect($setupGrade->setupGradeItems)->sortBy('lft')->pluck('id', 'max');
        return $items;
    }
  
    public function approve ($id)
    {
        $model = $this->crud->model::find($id);

        if($model === null) { 
            \Alert::warning('Data Not Found.')->flash();
            return back(); 
        }

        // Check If User Has Role Of School Head Then He/She Can Allow To Approve
        if(!backpack_user()->hasRole('School Head')) {
            \Alert::warning("You Do Not Have Permission To Approve")->flash();
            return back();
        }

        // Check If Has Already Approved
        if($model->is_approved === "Approved") {
            \Alert::warning("This Item Is Already Approved")->flash();
            return back();
        }

        $modelToUpdate =    $this->crud->model::where('id', $id)
                            ->update([
                                'is_approved' => 1,
                                'approved_by' => backpack_user()->id,
                                'approved_at' => now()
                            ]);

        if($modelToUpdate) {
            \Alert::success("Successfully Approved")->flash();
            return back();
        }
    }

}