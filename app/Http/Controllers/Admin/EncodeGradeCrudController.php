<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EncodeGradeRequest as StoreRequest;
use App\Http\Requests\EncodeGradeRequest as UpdateRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\EncodeGrade;
use App\Models\SubjectManagement;
use App\Models\SectionManagement;
use App\Models\GradeTemplate;
use App\Models\Student;
use App\Models\StudentSectionAssignment;
use App\Models\Period;
use App\Models\SetupGrade;
use App\Models\SchoolYear;
use App\Models\YearManagement;
use App\Models\Transmutation;
/**
 * Class EncodeGradeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EncodeGradeCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SetupGrade');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/encode-grade');
        $this->crud->setEntityNameStrings('Grade', 'Grades Encoding');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        if(!backpack_auth()->user()->employee) {
             abort(403, 'Your User Account Is Not Yet Tag As Employee');
        }

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in EncodeGradeRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->addColumn([
            'label' => 'Level',
            'type' => 'text',
            'name' => 'level'
        ])->beforeColumn('section_id');

        $this->crud->addColumn([
            'label' => 'Section',
            'type' => 'select',
            'name' => 'section_id',
            'entity' => 'section',
            'attribute' => 'name',
            'model' => 'App\Models\SectionManagement',
        ]);

        $this->crud->addColumn([
            'label' => 'Term',
            'type' => 'text',
            'name' => 'term_type',
        ]);

        // $this->crud->addColumn([
        //     'label' => 'Period',
        //     'type' => 'select',
        //     'name' => 'period_id',
        //     'entity' => 'period',
        //     'attribute' => 'name',
        //     'model' => 'App\Models\Period',
        // ])->afterColumn('term_type');


        $this->crud->addColumn([
            'label' => 'Template Name',
            'type' => 'select',
            'name' => 'template_id',
            'attribute' => 'name',
            'entity' => 'template',
            'model' => "App\Models\GradeTemplate",
            // 'scope' => function($model) {
            //    return $model->groupBy('template_id');
            // }
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
            'name' => 'school_year_id',
            'label' => 'School Year',
            'type' => 'select',
            'entity' => 'schoolYear',
            'attribute' => 'schoolYear',
            'model' => "App\Models\SchoolYear",
        ]);

        // FILTERS
        $this->crud->addFilter([ // select2 filter
            'name' => 'school_year_id',
            'type' => 'select2',
            'label'=> 'School Year'
        ], function() {
            return SchoolYear::all()->keyBy('id')->pluck('schoolYear', 'id')->toArray();
        }, function($value) { // if the filter is active
            $this->crud->addClause('where', 'school_year_id', $value);
        });

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

        $this->crud->denyAccess(['create', 'delete', 'update']);
        $this->crud->removeColumns(['rows', 'state', 'teacher_id', 'parent_id', 'period_id', 'max', 'type', 'name']);
        // $this->crud->setListView('encodeGrade.list');
        // $this->crud->addButtonFromView('top', 'lookup', 'gradeSetup.lookup', 'end');
        $this->crud->addButtonFromView('line', 'manage', 'encodeGrade.manage', 'end');
        $this->crud->addButtonFromView('line', 'encode', 'encodeGrade.encode', 'end');
        $this->crud->addButtonFromView('line', 'gradeSetup', 'encodeGrade.gradeSetup', 'end');
        
        $this->crud->groupBy(['subject_id', 'section_id', 'teacher_id', 'school_year_id']);
        if(!backpack_auth()->user()->hasRole('School Head'))
        {
            $this->crud->addClause('where', 'teacher_id', backpack_auth()->user()->id);
        }
        $this->crud->addClause('where', 'is_approved', 1);

        // Get Active Sections Only (Not Deleted)
        $sections = SectionManagement::all();
        $this->crud->addClause('whereIn', 'section_id', $sections->pluck('id'));
    }


    public function isDataExist(Request $request)
    {
        $isDataExist = EncodeGrade::where('section_id', $request->section_id)
                                ->where('template_id', $request->template_id)
                                ->where('term_type', $request->term_type)
                                ->where('subject_id', $request->subject_id)
                                ->where('teacher_id', backpack_auth()->user()->id)
                                ->where('period_id', $request->period_id)
                                ->exists();
        return response()->json(['message' => $isDataExist]);
    }

    public function loadData(Request $request)
    {   
        $grade = EncodeGrade::where('template_id', $request->template_id)
                            ->where('subject_id', $request->subject_id)
                            ->where('section_id', $request->section_id)
                            ->where('term_type', $request->term_type)
                            ->where('period_id', $request->period_id)
                            ->where('school_year_id', $request->school_year_id)
                            ->where('teacher_id', backpack_auth()->user()->id)
                            ->first();

        if($grade !== null) {
            return response()->json($grade->rows);
        }
        return [];
    }


    public function saveEncodeGrade(Request $request)
    {
        // CHECK IF TEACHER IS HAVING THIS SUBJECT
        $isSetupGradeExists = SetupGrade::where('section_id', $request->section_id)
                                    ->where('term_type', $request->term_type)
                                    ->where('subject_id', $request->subject_id)
                                    ->where('period_id', $request->period_id)
                                    ->where('teacher_id', backpack_auth()->user()->id)
                                    ->exists();
        if(!$isSetupGradeExists) {
            return response()->json(['message' => 'Error, Something Went Wrong...', 'error' => true]);
        }

        $isGradeExists = EncodeGrade::where('section_id', $request->section_id)
                                    ->where('term_type', $request->term_type)
                                    ->where('period_id', $request->period_id)
                                    ->where('subject_id', $request->subject_id)
                                    ->where('school_year_id', $request->school_year_id)
                                    ->where('teacher_id', backpack_auth()->user()->id)
                                    ->exists();
                                    
        if($isGradeExists) {
            
            $update = EncodeGrade::where('teacher_id', backpack_auth()->user()->id)
                                    ->where('section_id', $request->section_id)
                                    ->where('term_type', $request->term_type)
                                    ->where('period_id', $request->period_id)
                                    ->where('subject_id', $request->subject_id)
                                    ->where('school_year_id', $request->school_year_id)
                                    ->update(["state" => $request->state, "rows" => json_encode($request->rows)]);
            if($update) {
                return response()->json(['message' => 'Successfully Updated Records.', 'error' => false]);
            }
            return response()->json(['message' => 'Error Updating Records.', 'error' => true]);
        } else {
            $transmutation = Transmutation::active()->first();
            if(!$transmutation) {
                return response()->json(['message' => 'Error No Activated Transmutation Table Found.', 'error' => true]);
            }

            $grades                     = new EncodeGrade;
            $grades->template_id        = $request->template_id;
            $grades->subject_id         = $request->subject_id;
            $grades->section_id         = $request->section_id;
            $grades->term_type          = $request->term_type;
            $grades->period_id          = $request->period_id;
            $grades->school_year_id     = $request->school_year_id;
            $grades->teacher_id         = backpack_auth()->user()->id;
            $grades->transmutation_id   = $transmutation->id;
            $grades->state              = "open";
            $grades->rows               = json_encode($request->rows);

            if($grades->save()) {
                return response()->json(['message' => 'Successfully Added Records.', 'error' => false]);
            }
            return response()->json(['message' => 'Error Adding Record(s).', 'error' => true]);
        }
    }

    public function getTabsPeriod (Request $request)
    {
        $section = SectionManagement::where('id', $request->section_id)->with('level')->first();
        $periods = [];
        if($section !== null) {
            $periods = Period::where('department_id', $section->level->department_id)->orderBy('sequence', 'asc')->get();
        }
        return response()->json($periods);
    }

    public function encode (Request $request)
    {
        $id = $request->get('id');

        // Get Period Management For Tabs
        $periods = $this->getTabsPeriod($request);
        $template = GradeTemplate::where('id', app('request')->input('template_id'))->withTrashed()->first();
        $subject  = SubjectManagement::where('id', app('request')->input('subject_id'))->withTrashed()->first();
        $section  = SectionManagement::where('id', app('request')->input('section_id'))->withTrashed()->first();
        $schoolYear  = SchoolYear::where('id', app('request')->input('school_year_id'))->first();

        $setupGrade = SetupGrade::where([
                        'template_id' => $request->template_id, 
                        'subject_id' => $request->subject_id, 
                        'section_id' => $request->section_id, 
                        'term_type' => $request->term_type, 
                        'school_year_id'=> $request->school_year_id,
                        'is_approved' => 1])
                    ->first();
        if(!$setupGrade) { abort(404); }
        if(!$setupGrade->is_approved) {
            \Alert::warning("This Item Is Not Yet Approved")->flash();
            return redirect()->back();
        }
        // Abort If Encoding Of Grades For The Setup Grade is Already Closed.
        if(!$setupGrade->encoding_status) {
            if(count($setupGrade->allowed_employee) > 0) {
                if(in_array(backpack_auth()->user()->employee_id, $setupGrade->allowed_employee->toArray())) {
                    return view('encodeGrade.encode', compact('id', 'periods', 'template', 'subject', 'section', 'schoolYear'));
                }
            }
            \Alert::warning("Encoding of Grades is Already Closed.")->flash();
            abort(403, 'Encoding of Grades is Already Closed.'); 
        }
        return view('encodeGrade.encode', compact('id', 'periods', 'template', 'subject', 'section', 'schoolYear'));
        // return view('encodeGrade.encode', compact('id', 'periods', 'studentRosters', 'topColumnHeader'));
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

    public function destroy ($id)
    {
        $model = $this->crud->model::findOrFail($id);
        $model->delete();
    }

    public function submitGrades (Request $request)
    {
        $response = null;
        $errorFlag = false;
        $model = EncodeGrade::where([
                    'template_id'       => $request->template_id,
                    'subject_id'        => $request->subject_id,
                    'section_id'        => $request->section_id,
                    'term_type'         => $request->term_type,
                    'period_id'         => $request->period_id,
                    'school_year_id'    => $request->school_year_id,
                    'teacher_id'        => backpack_auth()->user()->id,
                ]);

        // Check If Exists 
        if($model->exists() === false) {
            $errorFlag = true;
            $response = [
                'message' => 'You Do Not Have Right To Submit This Grades.',
                'status'  => 'ERROR',
            ];
        }

        // Check If Already Submitted
        if($model->first()->submitted) {
            $errorFlag = true;
            $response = [
                'message' => 'Grades has already been submitted.',
                'status'  => 'ERROR',
            ];
        }

        // Check The Password
        if(!Hash::check($request->password, backpack_auth()->user()->password)) {
            $errorFlag = true;
            $response = [
                'message' => 'Incorrect Input Password',
                'status'  => 'ERROR',
            ];
        }

        // Check If The User ID Match To Teacher ID
        if(!backpack_auth()->user()->id === $model->first()->teacher_id) {
            $errorFlag = true;
            $response = [
                'message' => 'You are not allowed to submit this grades',
                'status'  => 'ERROR',
            ];
        }

        if(!$errorFlag) {
            
            $grades = $model->first();
            $grades->submitted = 1;
            $grades->submitted_at = now();
            $grades->save();

            $response = [
                'message' => 'Grades Submitted Successfully',
                'status'  => 'OK',
            ];
        }

        return response()->json($response);
    }

    public function getSubmittedGrades (Request $request)
    {
        $submittedGrades =  EncodeGrade::where([
                                'template_id'   => $request->template_id,
                                'school_year_id' => $request->school_year_id,
                                'subject_id'    => $request->subject_id,
                                'section_id'    => $request->section_id,
                                'term_type'     => $request->term_type,
                                'teacher_id'    => backpack_auth()->user()->id,
                                'submitted'     => 1,
                            ])->select('id', 'submitted_at', 'period_id')->orderBy('submitted_at', 'DESC')->get();

        return response()->json($submittedGrades, 200);
    }

    public function getSummaryGrades (Request $request)
    {
        $periods        = $this->getTabsPeriod($request)->getData();
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
            $studentnumber = $student->studentnumber;
            $data = [
                // 'studentnumber'     => $student->studentnumber,
                'learnersname'          => strtoupper($student->fullname_last_first),
            ];


            // Set Value Grades Each Period
            foreach ($periods as $period) {
                // dd($request->subject_id, (int)$request->section_id);
                $encodeGrades = EncodeGrade::where([
                    'template_id' => $request->template_id,
                    'subject_id' => (int)$request->subject_id,
                    'section_id' => (int)$request->section_id,
                    'school_year_id' => $request->school_year_id,
                    'term_type' => $request->term_type,
                    'period_id' => $period->id,
                ])
                ->first();

                $row = collect($encodeGrades ? $encodeGrades->rows ?? null : null)
                        ->where('studentnumber', $studentnumber)
                        ->first();

                $data['period' . $period->id] = $row ? $row->quarterly_grade : null;
            }

            array_push($array, $data);
        }

        $columns = $array;

        return $columns;
        // $periods = $this->getTabsPeriod($request)->getData();


        // // Set DataFields Value
        // $datafields = [
        //     [ "name" => 'learnersname', "type" => 'string' ],
        // ];

        // // Set Rows
        
        // // Set Columns
        // $columns = [
        //     [ "text" => "Learner's Name", "datafield" => 'learnersname', "width" => 250],
        // ];

        // foreach ($periods as $period) {
        //     array_push($datafields, [
        //         "name" => 'period' . $period->id,
        //         "type" => 'string'
        //     ]);

        //     array_push($columns, [
        //         "text" => $period->name,
        //         "datafield" => "period" . $period->id,
        //         "width" => 180
        //     ]);
        // }

        // $data = [
        //     'datafields' => $datafields,
        //     'rows' => [
        //         'learnersname' => 'Marlon Tandoc',
                
        //     ],
        //     'columns' => $columns,
        // ];

        return response()->json([
            [
                'learnersname' => 'Marlon Tandoc'
            ]
        ]);
        return response()->json($data);
    }
}
