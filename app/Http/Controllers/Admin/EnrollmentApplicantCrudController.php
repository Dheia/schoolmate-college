<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EnrollmentApplicantRequest as StoreRequest;
use App\Http\Requests\EnrollmentApplicantRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

// MODELS
use App\Models\Student;
use App\Models\SectionManagement;
use App\Models\Tuition;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\Department;
use App\Models\EncodeGrade;
use App\Models\YearManagement;
use App\Models\CommitmentPayment;
use App\Models\TermManagement;
use App\Models\OtherService;
use App\Models\TrackManagement;
use App\Models\SpecialDiscount;
use App\Models\KioskEnrollment;

/**
 * Class EnrollmentApplicantCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EnrollmentApplicantCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Enrollment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/enrollment-applicant');
        $this->crud->setEntityNameStrings('enrollmentapplicant', 'Enrollment Applicants');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->removeButtons(['create', 'delete']);
        $this->crud->denyAccess(['create', 'update']);

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        $this->crud->removeButton('create');
        $this->crud->removeButton('update');
        $this->crud->removeField('old_or_new');
        $this->crud->removeField('qr_code');
        $this->crud->removeField('is_passed');

        $this->crud->allowAccess('redirectButton');
        $this->crud->addButtonFromView('redirect', 'Redirect', 'redirectButton', 'start'); 


        $this->crud->enableAjaxTable();
        $this->crud->enableExportButtons();


        // $this->crud->data['content-header-style'] = "padding: 10px;";
        $this->crud->data['redirectButton']   =   [
            'route'         =>   $this->crud->route . '?school_year_id=' . request()->school_year_id, //Button Route (Default Crud Route)
            'label'         =>   'Back to all Departments', //Button Text (Default: Redirect)
            'attribute'     =>   [
                'class' => 'btn'
            ],
            // 'button-style'  => [
            //     'background-image'  => 'linear-gradient(147deg, #000000 0%, #04619f 74%)',
            //     'background-color'  => '#000000',
            //     'color'             =>  'white',
            //     'border-radius'     => '10px'
            // ], //Button CSS  ( <a></a> )
            'icon-attribute'   => [
                'class' => 'fa fa-angle-double-left'
            ], //Icon Class (Bootstrap) Default( <i class="fa fa-angle-double-left"></i> )
            'icon-style'    => [
                // 'margin' => '20px'
            ], //Icon CSS ( <i></i> )   
        ];
        
        /*
        |--------------------------------------------------------------------------
        | FIELD DETAILS
        |--------------------------------------------------------------------------
        */
         $this->crud->addField([
            'label'         => 'Student Number',
            'name'          => 'studentnumber',
            'type'          => 'hidden',
            'attributes'    => [ 'id' => 'studentNumber' ]
        ]);

        $this->crud->addField([
            'name' => 'searchStudent',
            'type' => 'enrollment.searchStudent',
            'label' => '',
            'attributes' => [
                'id' => 'searchInput',
                'placeholder' => 'Search For Name or ID Number (ex. 1100224)',
            ],
        ])->beforeField('studentnumber');

        // dd($this->crud);
        if($this->crud->getActionMethod() === "edit") {
            $this->crud->addField([
                    'name'              => 'school_year_id',
                    'label'             => 'School Year',
                    'type'              => 'select_from_array',
                    'options'           => SchoolYear::all()->pluck('schoolYear', 'id'),
                    'wrapperAttributes' => [ 'class' => 'form-group col-md-3' ],
            ]);
        } else {
            $this->crud->addField([
                    'name'              => 'school_year_id',
                    'label'             => 'School Year',
                    'type'              => 'select_from_array',
                    'options'           => SchoolYear::all()->pluck('schoolYear', 'id'),
                    'default'           => SchoolYear::active()->first()->id,
                    'wrapperAttributes' => [ 'class' => 'form-group col-md-3' ],
            ]);
        }

         $this->crud->addField([   // Select
            'label'             => "Department",
            'type'              => 'select_from_array',
            'name'              => 'department_id',
            'options'           => Department::active()->pluck('name', 'id'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);


        $this->crud->addField([   // Select
            'label' => "Level",
            'type' => 'select_from_array',
            'name' => 'level_id',
            'options' => [],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

         $this->crud->addField([   // Select
            'label' => "Strand",
            'type' => 'select_from_array',
            'name' => 'track_id', 
            'options' => [],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ])->afterField('level_id');

        // $this->crud->addField([   // Select
        //     'label' => "Term",
        //     'type' => 'select_from_array',
        //     'name' => 'term_type', 
        //     'options' => [],
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-3'
        //     ]
        // ])->afterField('track_id');

        $this->crud->addField([   // Select
            'label' => "Term",
            'type' => 'select_from_array',
            'name' => 'term_type', 
            'options' => [],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ])->afterField('track_id');

        $this->crud->addField([
            'label' => 'Curriculum',
            'type' => 'select',
            'name' => 'curriculum_id',
            'entity' => 'curriculum',
            'attribute' => 'curriculum_name',
            'model' => 'App\Models\CurriculumManagement',
            'allows_null' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ])->beforeField('tuition_id');

        
        $this->crud->addField([
            'label' => 'Select Tution Form Name',
            'type' => 'select_from_array',
            'name' => 'tuition_id',
            'options' => [],
            // 'entity' => 'tuition',
            // 'attribute' => 'form_name',
            // 'model' => 'App\Models\Tuition',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'attributes' => [
                'id' => 'tuition_id',
                // 'disabled' => true,
            ]
        ]);

        $this->crud->addField([
            'label' => 'Commitment Payment <br><small style="font-weight: 100 !important; color: red">Once you set this payment this will be irreversible.</small>',
            'type'  => 'enrollment.select_commitment_payment',
            'name'  => 'commitment_payment_id',
            'entity' => 'commitmentPayment',
            'attribute' => 'name',
            'model' => 'App\Models\CommitmentPayment',
            'wrapperAttributes' => [
                'id' => 'commitment_payment',
                'class' => 'form-group col-md-3'
            ]
        ]);

        // $this->crud->addField([   // Select
        //     'label' => "Term",
        //     'type' => 'select_from_array',
        //     'name' => 'term_id', 
        //     'options' => [],
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-3'
        //     ]
        // ]);

        $this->crud->addField([
            'label' => '',
            'type' => 'enrollment.script',
            'name' => 'enrollment_script'
        ])->afterField('studentnumber');

        /*
        |--------------------------------------------------------------------------
        | COLUMN DETAILS
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
            'label' => 'Student Number',
            'type' => 'text',
            'key' => 'studentnumber',
            'name' => 'studentnumber',
            'prefix' => \Setting::get('schoolabbr') . '-',
        ]);

        $this->crud->addColumn([
             // 1-n relationship
            'label' => "Age", // Table column heading
            'type' => "text",
            'name' => 'age', // the column that contains the ID of that connected entity;
            'visibleInExport' => true,
            'visibleInTable' => false
        ]);

        $this->crud->addColumn([
            'label' => 'Term',
            'type' => 'text',
            'name' => 'term_type',
            'suffix' => ' Term',    
            'visibleInExport' => true,
            'visibleInTable' => true
        ])->afterColumn('track_id');

        $this->crud->addColumn([
            'label' => 'Birth Date',
            'type' => 'date',
            'name' => 'birth_date',
            'format' => 'MMMM DD, Y',
            'visibleInExport' => true,
            'visibleInTable' => false
        ]);

        $this->crud->addColumn([
            'label' => 'Gender',
            'type' => 'text',
            'name' => 'gender',
            'visibleInExport' => true,
            'visibleInTable' => false
        ]);

        $this->crud->addColumn([
            'label' => 'Citizenship',
            'type' => 'text',
            'name' => 'citizenship',
            'visibleInExport' => true,
            'visibleInTable' => false
        ]);

        $this->crud->addColumn([
            'label' => 'Birth Place',
            'type' => 'text',
            'name' => 'birthplace',
            'visibleInExport' => true,
            'visibleInTable' => false
        ]);

        $this->crud->addColumn([
            'label' => 'Residential Address',
            'type' => 'text',
            'name' => 'residentialaddress',
            'visibleInExport' => true,
            'visibleInTable' => false
        ]);

        $this->crud->addColumn([
            'label' => 'Religion',
            'type' => 'text',
            'name' => 'religion',
            'visibleInExport' => true,
            'visibleInTable' => false
        ]);
        
        $this->crud->addColumn([
            // 1-n relationship
            'label' => "Full Name", // Table column heading
            'type' => "text",
            'name' => 'full_name', // the column that contains the ID of that connected entity;
            'entity' => "student", 
            'attribute' => "full_name", 
            'model' => "App\Models\Student", 
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('students', function ($q) use ($column, $searchTerm) {
                    $q->where('firstname', 'like', '%'.$searchTerm.'%')
                        ->orWhere('lastname', 'like', '%'.$searchTerm.'%')
                        ->orWhere('studentnumber', 'like', '%'.$searchTerm.'%');
                })->orWhereHas('studentsById', function ($q) use ($column, $searchTerm) {
                    $q->where('firstname', 'like', '%'.$searchTerm.'%')
                        ->orWhere('lastname', 'like', '%'.$searchTerm.'%')
                        ->orWhere('studentnumber', 'like', '%'.$searchTerm.'%');
                });
            },
            'priority' => 1,
        ])->afterColumn('studentnumber');

        $this->crud->addColumn([   // Select
            'label' => "First Name",
            'type' => 'text',
            'name' => 'firstname',
            'visibleInExport' => true,
            'visibleInTable' => false
        ])->afterColumn('full_name');

        $this->crud->addColumn([   // Select
            'label' => "Last Name",
            'type' => 'text',
            'name' => 'lastname',
            'visibleInExport' => true,
            'visibleInTable' => false
        ])->afterColumn('firstname');

        $this->crud->addColumn([   // Select
            'label' => "Year Level",
            'type' => 'select',
            'name' => 'level_id', 
            'entity' => 'level', 
            'attribute' => 'year', 
            'model' => "App\Models\YearManagement" 
        ]);

        $this->crud->addColumn([   // Select
            'label' => "School Year",
            'type' => 'select',
            'name' => 'school_year_id', 
            'entity' => 'schoolYear', 
            'attribute' => 'schoolYear', 
            'model' => "App\Models\SchoolYear" 
        ]);

        $this->crud->addColumn([   // Select
            'label' => "Department",
            'type' => 'select',
            'name' => 'department_id', 
            'entity' => 'department', 
            'attribute' => 'name', 
            'model' => "App\Models\Department" 
        ]);

        $this->crud->addColumn([   // Select
            'label' => "Track",
            'type' => 'select',
            'name' => 'track_id', 
            'entity' => 'track', 
            'attribute' => 'code', 
            'model' => "App\Models\TrackManagement" 
        ])->afterField('level_id');

        $this->crud->addColumn([   // Select
            'label' => "Tuition",
            'type' => 'text',
            'name' => 'tuition_fee_name'

        ])->afterColumn('track_id');

        $this->crud->addColumn([
            'label' => 'Curriculum',
            'type' => 'select',
            'name' => 'curriculum_id',
            'entity' => 'curriculum',
            'attribute' => 'curriculum_name',
            'model' => 'App\Models\CurriculumManagement',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);
        
        $this->crud->addColumn([
            'label' => 'Payment Method',
            'type'  => 'select',
            'name'  => 'commitment_payment_id',
            'entity' => 'commitmentPayment',
            'attribute' => 'name',
            'model' => 'App\Models\CommitmentPayment'
        ]);

        // $this->crud->addColumn([   // Select
        //     'label' => "Date Enrolled",
        //     'type' => 'text',
        //     'name' => 'created_at'
        // ]);

        $this->crud->addColumn([   // Select
            'label' => "Referral",
            'type' => 'text',
            'name' => 'referred'
        ]);

        $this->crud->addColumn([   // Select
            'label' => "Referrer Contact",
            'type' => 'text',
            'name' => 'referrer_contact'
        ]);

        $this->crud->addColumn([   // Select
            'label' => "Status",
            'type' => 'text',
            'name' => 'enrollment_status'
        ]);

        /*
        |--------------------------------------------------------------------------
        | FILTER DETAILS
        |--------------------------------------------------------------------------
        */

        $this->crud->addFilter([ // select2_multiple filter
          'name' => 'level_id',
          'type' => 'select2',
          'label'=> 'Year Level',
          'attribute' => ['id' => 'filter_level'],
        ], function() { // the options that show up in the select2
            return YearManagement::where('department_id',  request()->department)->pluck('year', 'id')->toArray();
        }, function($value) { // if the filter is active
            // foreach (json_decode($values) as $key => $value) {
                $this->crud->addClause('where', 'level_id', $value);
            // }
        });
        if(request()->department){
            $department  = Department::with('term')->where('id', request()->department)->first();
            $term_type = TermManagement::where('department_id', request()->department)->first();
            
            if($department){
                if($department->with_track == '1'){
                    $this->crud->addFilter([ // select2 filter
                      'name' => 'track_id',
                      'type' => 'select2',
                      'label'=> 'Strand'
                    ], function() {
                        return TrackManagement::distinct()->pluck('code', 'code')->toArray();
                    }, function($value) { // if the filter is active
                        $this->crud->addClause('trackCode', $value);
                    });
                }
            }
            if($term_type){
                if($term_type->type == 'Semester'){
                    $this->crud->addFilter([ // select2 filter
                      'name' => 'term',
                      'type' => 'select2',
                      'label'=> 'Term',
                      'attributes' => [ 'id' => 'term_filter'],
                    ], function() {
                        $term = TermManagement::where('department_id', request()->department)->first();
                        $ordinal_terms = [];
                        if($term->ordinal_terms > 0){
                            foreach ($term->ordinal_terms as $key => $ordinal) {
                                $ordinal_terms += [$ordinal => $ordinal];
                            } 
                        }
                        return $ordinal_terms;
                    }, function($value) {
                        $this->crud->addClause('where', 'term_type', '=', $value);
                    });
                }
            }
        }
        // DEPARTMENT FILTER SCRIPT
         $this->crud->addFilter([   // Select
            'label' => "",
            'type' => 'student_department_script',
            'name' => 'script'
        ]);

        $this->data['schoolYears']  = SchoolYear::with(['enrollment_applicants', 'enrollment_applicants.department'])
                                                    ->withCount('enrollment_applicants')
                                                    ->get();
        $this->data['departments']  = Department::active()->get();
        if(request()->school_year_id && request()->department){
            $this->crud->removeColumns(['department_id','school_year_id']);
            $this->data['school_year']  = SchoolYear::where('id', request()->school_year_id)->first();
            $this->data['department']   = $department;
            $this->crud->addClause('where', 'school_year_id', '=', request()->school_year_id);
            $this->crud->addClause('where', 'department_id', '=', request()->department);
            $this->crud->setListView('enrollment.applicant.list');
        }
        // ELSE GO TO DASHBOARD
        else {
            $this->data['active_sy']    = SchoolYear::active()
                                                    ->first();
            $this->data['enrollments']  =   Enrollment::where('school_year_id', $this->data['active_sy']->id)
                                                ->withCount('level')
                                                ->orderBy('department_id')
                                                ->orderBy('level_id')
                                                ->get();
            $this->crud->setHeading('Enrollment Applicants');                             
            $this->crud->setListView('enrollment.applicant.dashboard');
        }

        $this->crud->addClause('orderBy', 'created_at');
        $this->crud->addClause('where', 'is_applicant',1);
        $this->crud->addButtonFromView('line', 'Print', 'enrollment.applicant.print_application', 'end');
        $this->crud->addButtonFromView('line', 'Enroll Now', 'enrollment.applicant.enroll', 'end');
        $this->crud->addButtonFromView('line', 'Delete', 'enrollment.applicant.delete', 'end');
        $this->crud->removeColumns(['is_passed','curriculum_id','tuition_id']);




        // add asterisk for fields that are required in EnrollmentApplicantRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->disablePersistentTable();
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

    public function destroy($id)
    {
        $applicant = Enrollment::findOrFail($id);
        if($applicant->old_or_new == "new")
        {
            if($applicant->student_id)
            {
                Student::destroy($applicant->student_id);
            }
            else if($applicant->studentnumber)
            {
                $student = Student::where('studentnumber', $applicant->studentnumber)->first();
                Student::destroy($student->id);
            }
        }
        $this->crud->hasAccessOrFail('delete');

        return $this->crud->delete($id);
    }

    public function enroll ($id)
    {
        $model = $this->crud->model::where('id', $id)
                                ->with(['kioskEnrollment' => function ($query) {
                                    $query->with('student');
                                }])
                                ->with('kioskEnrollment.enrollment')
                                ->first();

        if(!$model) {
            abort(404, 'No Enrollment Found');
        }

        if(!$model->is_applicant && $model->studentnumber == null) {
            \Alert::warning("This Enrollment Is Already Enrolled")->flash();
            return redirect('admin/enrollment');
        }

        $this->data['crud']     = $this->crud;
        $this->data['entry']    = $model;
        $this->data['levels']   = YearManagement::select('id', 'year', 'department_id')->get();
        $this->data['tracks']   = TrackManagement::select('id', 'code', 'level_id')->where('active', 1)->get();
        $this->data['terms']    = TermManagement::select('id', 'type', 'no_of_term', 'department_id')->get();

        $this->crud->removeFields(['searchStudent', 'enrollment_script']);
        $this->crud->addField([
            'label' => '',
            'type'  => 'enrollment.scriptEnroll',
            'name'  => 'enrollment_script'
        ])->afterField('studentnumber');

        return view('enrollment.applicant.enroll', $this->data);
    }

    public function submitEnrollmentForm ($id, Request $request)
    {
        $model = $this->crud->getEntry($id);

        if(!$model->is_applicant) {
            \Alert::warning('This enrollment is already enrolled')->flash();
            return redirect('admin/enrollment');
        }

        $student = null;
        if($model->student_id != null)
        {
            $student = Student::where('id', $model->student_id)->first();
        }
        if($model->studentnumber != null)
        {
            $student = Student::where('studentnumber', $model->studentnumber)->first();
        }

        $sy = SchoolYear::where('id', $request->school_year_id)->first()->schoolYear;
        $generateStudentNumber = $this->getNextStudentUserId($sy);
        $studentnumber = null;
        if($student != null)
        {
            if($student->studentnumber != null)
            {
                $request->request->set('studentnumber', $student->studentnumber);
                $studentnumber = $student->studentnumber;
            }
            else
            {
                $request->request->set('studentnumber', $generateStudentNumber);
                $studentnumber = $generateStudentNumber;
            }
        }
        else
        {
            abort(404, 'Student not Found.');
        }

        $rules = [
            'studentnumber'             => 'required|numeric',
            'tuition_id'                => 'required|numeric|exists:tuitions,id',
            'school_year_id'            => 'required|numeric|exists:school_years,id',
            'department_id'             => 'required|numeric|exists:departments,id',
            // 'track_id'                  => 'required|numeric',
            'curriculum_id'             => 'required|numeric|exists:curriculum_managements,id',
            // 'section_id'                => 'required|numeric',
            'commitment_payment_id'     => 'required|numeric|exists:commitment_payments,id',
        ];

        // CHECK DEPARTMENT IF TRACK HAS CHECKED
        $departmentWithTrack = Department::where('id', $request->department_id)->first();
        if($departmentWithTrack !== null) {
            $departmentWithTrack->with_track ? $rules['track_id'] = 'nullable|numeric|exists:track_managements,id' : '';
        }

        // CHECK GRADE/YEAR LEVEL
        $levels = YearManagement::where('department_id', $request->department_id)->get()->pluck('id');
        $rules['level_id'] = ['required', 'numeric', 'exists:year_managements,id', Rule::in($levels)];

        $tuitions = Tuition::where([
                        'schoolyear_id' => $model->school_year_id,
                        'department_id' => $request->department_id,
                        'grade_level_id' => $request->level_id,
                        'track_id' => $request->track_id,
                    ])->get()->pluck('id');

        $rules['tuition_id'] = ['required', 'numeric', 'exists:tuitions,id', Rule::in($tuitions)];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->input());
        }

        // CHECK TERM VALUES 
        $term = TermManagement::where('department_id', $request->department_id)->first();
        $rules['term_type'] = ['required', Rule::in($term->ordinal_terms)];   

        // your additional operations before save here

        // CHECK IF IT IS ALREADY INVOICE IT SHOULD NOT ALLOWED TO UPDATE
        if($model->invoice_no !== null)
        {
            \Alert::warning("You won't allowed to update any changes.")->flash();
            return redirect()->back();
        }

        $input = $request->all();
        $input = collect($input)->except(['_token', 'http_referrer'])->toArray();
        $input['is_applicant'] = 0;

        $update = $this->crud->model::where('id', $id)->update($input);

        if($update) {
            \Alert::success("Successfully Enrolled")->flash();
            Student::where('id', $model->student_id)->update(['studentnumber' => $studentnumber]);
            return redirect('admin/enrollment');
        }

        \Alert::warning("Error Enrolling Student")->flash();
        return redirect('admin/enrollment');

    }

    private function getNextStudentUserId ($schoolYear) 
    {
        
        $extracted     = preg_replace('/-(\d+)/', '', $schoolYear);
        $lastTwoDigits = substr($extracted, -2); 

        $studentId = Student::where('studentnumber', 'LIKE', (int)$lastTwoDigits . '%')
                               // ->orWhere('deleted_at', 'null')
                               ->orderBy('studentnumber', 'DESC')
                               ->pluck('studentnumber')
                               ->first();
                               // dd($studentId);
        if($studentId == null) {
            $studentId = $lastTwoDigits . "0001";
            return (int)$studentId;
        }                       

        return (int)$studentId + 1;
    }

    public function print ($enrollment_id)
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', -1);
        
        $enrollment = Enrollment::with(['schoolYear', 'level', 'track'])->where('id', $enrollment_id)->first();
        if($enrollment == null)
        {
            abort(404, "Enrollment Not Found");
        }
        if($enrollment->student_id)
        {
            $student    = Student::where('id', $enrollment->student_id)->first();
        }
        else
        {
            $student    = Student::where('studentnumber', $enrollment->studentnumber)->with(['schoolYear', 'yearManagement', 'track', 'enrollments'])->first();
        }

        if($student == null)
        {
            abort(404, "Student Not Found");
        }

        $schooltable    =   [];

        // GET SCHOOL TABLE UNTIL AND FROM
        if($student->schooltable)
        {
            if( count($student->schooltable) > 0 )
            {
                foreach ($student->schooltable as $key => $studentSchoolTable) 
                {
                    if($studentSchoolTable)
                    {
                        $grade_level_until  = isset($studentSchoolTable['grade_level_until']) 
                                                ? YearManagement::where('id', $studentSchoolTable['grade_level_until'])->first()
                                                : null;
                        $grade_level_from   = isset($studentSchoolTable['grade_level_from'])
                                                ? YearManagement::where('id', $studentSchoolTable['grade_level_from'])->first()
                                                : null;
                        $schooltable[] = [
                            'grade_level_until' =>  $grade_level_until ? $grade_level_until->year : '-',
                            'grade_level_from'  =>  $grade_level_from ? $grade_level_from->year : '-',
                            'school_name'       =>  isset($studentSchoolTable['school_name']) ? $studentSchoolTable['school_name'] : '-',
                            'year_attended'     =>  isset($studentSchoolTable['year_attended']) ? $studentSchoolTable['year_attended'] : '-'
                        ];
                    }
                }
            }
        }

        $student->schooltable = json_encode($schooltable);

        // $pdf = \App::make('dompdf.wrapper');
        // $pdf->setPaper(array(0, 0, 612, 936), 'portrait');

        // $pdf->loadHTML( view('enrollment.applicant.print.print', compact('student' ,'enrollment')) );
        // return $pdf->stream(config('settings.schoolabbr') . $student->studentnumber . '.pdf');
        
        $schoollogo      = config('settings.schoollogo') ? (string)\Image::make(config('settings.schoollogo'))->encode('data-url') : null;
        $schoolmate_logo = (string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url');
         return view ('enrollment.applicant.print.new_print', compact('student' ,'enrollment','schoollogo', 'schoolmate_logo'));
    }
}
