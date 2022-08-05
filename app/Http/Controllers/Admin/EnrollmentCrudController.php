<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EnrollmentRequest as StoreRequest;
use App\Http\Requests\EnrollmentRequest as UpdateRequest;
use App\Http\Requests\EnrollmentDropTransferRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

// QUICKBOOKS
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Item;
use App\Http\Controllers\QuickBooks\QuickBooksOnline as QBO;
use Backpack\CRUD\CrudPanel;

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
use App\SelectedOtherService;
use App\SelectedOtherProgram;

use App\Models\PaymentHistory;
use App\Models\SubjectMapping;
use App\Models\StudentSectionAssignment;
use App\Models\Employee;
use App\Models\EnrollmentQboInvoice;

use App\Http\Controllers\Student\EnrollmentTuitionController;

use Config;
use Carbon\Carbon;

use Illuminate\Support\Facades\Hash;

/**
 * Class EnrollmentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EnrollmentCrudController extends CrudController
{
    private $qbo;

    public function show($id)
    {
        $this->crud->hasAccessOrFail('show');
        $this->crud->setOperation('show');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // set columns from db
        $this->crud->setFromDb();

        // cycle through columns
        foreach ($this->crud->columns as $key => $column) {
            // remove any autoset relationship columns
            if (array_key_exists('model', $column) && array_key_exists('autoset', $column) && $column['autoset']) {
                $this->crud->removeColumn($column['name']);
            }

            // remove the row_number column, since it doesn't make sense in this context
            if ($column['type'] == 'row_number') {
                $this->crud->removeColumn($column['name']);
            }

            // remove columns that have visibleInShow set as false
            if (isset($column['visibleInShow']) && $column['visibleInShow'] == false) {
                $this->crud->removeColumn($column['name']);
            }
        }

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.preview').' '.$this->crud->entity_name;

        // remove preview button from stack:line
        $this->crud->removeButton('show');
        $this->crud->removeButton('delete');

        // remove bulk actions colums
        $this->crud->removeColumns(['blank_first_column', 'bulk_actions']);

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getShowView(), $this->data);
    }

    private function ThisCrudIsBindedToQBO ()
    {
        try {
            $this->qbo = new QBO;
            $this->qbo->initialize();

            if($this->qbo->dataService === null)
            {
                $status  = "ERROR";
                $message = "Unauthorized QuickBooks";

                return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
            }

            $name           = "Enrolment Fee";
            $theResourceObj = $this->qbo->dataService()->Query("SELECT * FROM Item WHERE Name = '" . $name . "'");

            if($theResourceObj == null) 
            {

                $this->data['status']  = "ERROR";
                $url                   = url('admin/quickbooks/bind/' . str_slug($name) . '/services');
                $this->data['message'] = "This " . $this->crud->entity_name_plural . " Is Not Yet Binded To QuickBooks <br><br>" .
                                         "<a href=" . $url . " class='btn btn-lg btn-primary'>Bind Now &nbsp;<i class='fa fa-chain'></i></a>";

                $this->crud->setListView('quickbooks.layouts.fallbackMessage');
                $this->crud->setEditView('quickbooks.layouts.fallbackMessage');
                $this->crud->setCreateView('quickbooks.layouts.fallbackMessage');
                $this->crud->setShowView('quickbooks.layouts.fallbackMessage');
                $this->crud->setReorderView('quickbooks.layouts.fallbackMessage');
                $this->crud->setRevisionsView('quickbooks.layouts.fallbackMessage');
                $this->crud->setRevisionsTimelineView('quickbooks.layouts.fallbackMessage');
                $this->crud->setDetailsRowView('quickbooks.layouts.fallbackMessage');

            }

            $this->data['qbo_terms'] = $this->qbo->dataService()->Query("SELECT * FROM Term");
        } catch (\Exception $e) {
            $status  = "ERROR";
            $message = "Oops.. Something Went Wrong! Please Try Again.";
            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
        }
    }

    public function __construct()
    {
        if (! $this->crud) {
            $this->crud = app()->make(CrudPanel::class);

            // call the setup function inside this closure to also have the request there
            // this way, developers can use things stored in session (auth variables, etc)
            $this->middleware(function ($request, $next) {
                $this->request = $request;
                $this->crud->request = $request;
                $this->setup();

                return $next($request);
            });
        }
        self::ThisCrudIsBindedToQBO();
    }

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Enrollment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/enrollment');
        $this->crud->setEntityNameStrings('enrollment', 'enrollments');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        // ADD REDIRECT BUTTON TOP LEFT OF LIST (ABOVE datatable_info_stack)
        // BUTTON IN LIST = @include('crud::inc.button_stack', ['stack' => 'redirect'])
        $this->crud->allowAccess('redirectButton');
        $this->crud->addButtonFromView('redirect', 'Redirect', 'redirectButton', 'start'); 
        // $this->crud->data['content-header-style'] = "padding: 10px;";
        $this->crud->data['redirectButton']   =   [
            'route'         =>   $this->crud->route . '?school_year_id=' . request()->school_year_id, //Button Route (Default Crud Route)
            'label'         =>   'Back to Dashboard', //Button Text (Default: Redirect)
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

        // $this->crud->setFromDb();

        $this->crud->removeField('qr_code');

        // add asterisk for fields that are required in EnrollmentRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->enableAjaxTable();

        $this->crud->enableExportButtons();
        // $this->crud->enablePersistentTable();
        $this->crud->disablePersistentTable();

        $this->data['commitmentPayments'] = CommitmentPayment::active()->get();

        if($this->crud->getActionMethod() === "create" || $this->crud->getActionMethod() === "edit") {
            $this->data['levels'] = YearManagement::select('id', 'year', 'department_id')->get();
            $this->data['tracks'] = TrackManagement::select('id', 'code', 'level_id')->where('active', 1)->get();
            $this->data['terms'] = TermManagement::get();
        }
        $this->crud->removeButton('create');
        $this->crud->removeButton('update');
        $this->crud->removeField('old_or_new');
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
                    'wrapperAttributes' => [ 'class' => 'form-group col-md-3 col-xs-12' ],
            ]);
        } else {
            $this->crud->addField([
                    'name'              => 'school_year_id',
                    'label'             => 'School Year',
                    'type'              => 'select_from_array',
                    'options'           => SchoolYear::all()->pluck('schoolYear', 'id'),
                    'default'           => SchoolYear::active()->first()->id,
                    'wrapperAttributes' => [ 'class' => 'form-group col-md-3 col-xs-12' ],
            ]);
        }

        $this->crud->addField([
            'label' => 'Curriculum',
            'type' => 'select',
            'name' => 'curriculum_id',
            'entity' => 'curriculum',
            'attribute' => 'curriculum_name',
            'model' => 'App\Models\CurriculumManagement',
            'allows_null' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-xs-12'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Select Tution Form Name',
            'type' => 'select_from_array',
            'name' => 'tuition_id',
            'options' => [],
            // 'entity' => 'tuition',
            // 'attribute' => 'form_name',
            // 'model' => 'App\Models\Tuition',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-xs-12'
            ],
            'attributes' => [
                'id' => 'tuition_id',
                // 'disabled' => true,
            ]
        ]);

        $this->crud->addField([
            'label' => 'Commitment Payment <br><small style="font-weight: 100 !important; color: red">Once you set this payment this will be irreversible.</small>',
            'name'  => 'commitment_payment_id',
            'type'  => 'select_from_array',
            'options' => CommitmentPayment::active()->get()->pluck('name', 'id'),
            'wrapperAttributes' => [
                'id' => 'commitment_payment',
                'class' => 'form-group col-md-3 col-xs-12'
            ]
        ]);

         $this->crud->addField([   // Select
            'label'             => "Department",
            'type'              => 'select_from_array',
            'name'              => 'department_id',
            'options'           => Department::active()->pluck('name', 'id'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-xs-12'
            ]
        ]);


        $this->crud->addField([   // Select
            'label' => "Level",
            'type' => 'select_from_array',
            'name' => 'level_id',
            'options' => [],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-xs-12'
            ]
        ]);

         $this->crud->addField([   // Select
            'label' => "Strand",
            'type' => 'select_from_array',
            'name' => 'track_id', 
            'options' => [],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-xs-12'
            ]
        ])->afterField('level_id');

         $this->crud->addField([   // Select
            'label' => "Term",
            'type' => 'select_from_array',
            'name' => 'term_type', 
            'options' => [],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-xs-12'
            ]
        ])->afterField('track_id');


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
            'label' => 'Override Sequence',
            'type' => 'checkbox',
            'name' => 'override_sequence',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-xs-6'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Require Payment',
            'type' => 'checkbox',
            'name' => 'require_payment',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 col-xs-6'
            ]
        ]);

        $this->crud->addField([
            'label' => '',
            'type' => 'enrollment.script',
            'name' => 'enrollment_script'
        ]);

        // Set Column Details
        $this->crud->addColumn([
            'label' => 'Student Number',
            'type' => 'text',
            'key' => 'studentnumber',
            'name' => 'studentnumber',
            'prefix' => \Setting::get('schoolabbr') . '-',
        ]);

        $this->crud->addColumn([
            'label' => 'Age',
            'type' => 'text',
            'name' => 'age',
            'visibleInExport' => true,
            'visibleInTable' => false
        ]);

        $this->crud->addColumn([
            'label' => 'Term',
            'type' => 'text',
            'name' => 'term_type',
            'suffix' => ' Term',    
            'visibleInExport' => true,
            'visibleInTable' => false
        ])->afterColumn('track_id');

        $this->crud->addColumn([
            'label' => 'Birth Date',
            'type' => 'text',
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
            'type' => 'markdown',
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
            'label' => "Middle Name",
            'type' => 'text',
            'name' => 'middlename',
            'visibleInExport' => true,
            'visibleInTable' => false
        ])->afterColumn('firstname');

        $this->crud->addColumn([   // Select
            'label' => "Last Name",
            'type' => 'text',
            'name' => 'lastname',
            'visibleInExport' => true,
            'visibleInTable' => false
        ])->afterColumn('middlename');

        $this->crud->addColumn([
            'label'  => 'Emrg. Name',
            'name'   => 'emergency_contact_name_on_record',
            'type'   => 'text',
            'visibleInExport' => true,
            'visibleInTable' => false
        ]);


        $this->crud->addColumn([
            'label'  => 'Emrg. Address',
            'name'   => 'emergency_contact_address_on_record',
            'type'   => 'markdown',
            'visibleInExport' => true,
            'visibleInTable' => false
        ]);

        $this->crud->addColumn([
            'label'  => 'Emrg. Number',
            'name'   => 'emergency_contact_number_on_record',
            'type'   => 'text',
            'visibleInExport' => true,
            'visibleInTable' => false
        ]);

        $this->crud->addColumn([   // Select
            'label' => "Email",
            'type' => 'text',
            'name' => 'email'
        ])->afterColumn('lastname');

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
            'label' => "Strand",
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
            'label' => 'Payment Option',
            'type'  => 'select',
            'name'  => 'commitment_payment_id',
            'entity' => 'commitmentPayment',
            'attribute' => 'name',
            'model' => 'App\Models\CommitmentPayment'
        ]);

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

        // $this->crud->addColumn([   // Select
        //     'label' => "Date Enrolled",
        //     'type' => 'text',
        //     'name' => 'created_at'
        // ]);

        $this->crud->addColumn([   // Select
            'label' => "Status",
            'type' => 'text',
            'name' => 'enrollment_status'
        ]);

        $this->crud->addClause('orderBy', 'created_at');

        $this->crud->addButtonFromView('line', 'Print', 'enrollment.print', 'beginning'); // add a button whose
        $this->crud->allowAccess('print'); 
        $this->crud->addButtonFromView('line', 'Download Invoice', 'enrollment.downloadInvoice', 'beginning');
        $this->crud->addButtonFromView('line', 'Change Payment Plan', 'enrollment.changePaymentPlan', 'beginning');
        // $this->crud->addButtonFromView('line', 'Delete Invoice', 'enrollment.deleteInvoice', 'end');
        $this->crud->addButtonFromView('line', 'view', 'enrollment.view', 'beginning');
        $this->crud->addButtonFromView('line', 'Set Invoice', 'enrollment.invoice', 'beginning');
        $this->crud->addButtonFromView('line', 'Enroll Now', 'enrollment.enroll', 'end');
        $this->crud->addButtonFromView('line', 'Drop/Transfer', 'enrollment.drop_transfer', 'end');
        $this->crud->addButtonFromView('line', 'Enroll', 'enrollment.edit', 'end');
        // $this->crud->addButtonFromView('line', 'QR Code', 'enrollment.generateQRCode', 'end');

        $this->crud->allowAccess('enableAccount');
        $this->crud->allowAccess('disableAccount');

        $this->crud->data['dropdownButtons'] = [
            'enrollment.generateQRCode',
            'enrollment.enable_account',
            'enrollment.disable_account'
        ];
        $this->crud->addButtonFromView('line', 'More', 'dropdownButton', 'end');

        $this->crud->removeFields(['is_passed']);
        $this->crud->removeColumns(['is_passed','curriculum_id','tuition_id']);

        // Set Default School Year
        if(request()->all() == null)
        {
            $this->crud->addClause('where', 'school_year_id', '=', $this->getActiveSchoolYearId());
        }

        // Filters
        // $this->crud->addFilter([ // select2_multiple filter
        //   'name' => 'school_year_id',
        //   'type' => 'select2',
        //   'label'=> 'School Year'
        // ], function() { // the options that show up in the select2
        //     return SchoolYear::all()->pluck('schoolYear', 'id')->toArray();
        // }, function($value) { // if the filter is active
        //     // foreach (json_decode($values) as $key => $value) {
        //         $this->crud->addClause('where', 'school_year_id', $value);
        //     // }
        // });

        $this->crud->addFilter([ // select2_multiple filter
          'name' => 'level_id',
          'type' => 'select2',
          'label'=> 'Year Level',
          'attribute' => ['id' => 'filter_level'],
        ], function() { // the options that show up in the select2
            return YearManagement::where('department_id',  request()->department)->pluck('year', 'id')->toArray();
        }, function($value) { // if the filter is active
            // foreach (json_decode($values) as $key => $value) {
                $this->crud->addClause('where', 'enrollments.level_id', $value);
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
        $this->data['schoolYears']  = SchoolYear::with(['enrollment_enrolled', 'enrollment_enrolled.department'])
                                                    ->withCount('enrollment_enrolled')
                                                    ->get();
        $this->data['departments']  = Department::active()->get();
        if(request()->school_year_id && request()->department){
            $this->crud->removeColumns(['department_id','school_year_id']);
            $this->data['school_year']  = SchoolYear::where('id', request()->school_year_id)->first();
            $this->data['department']   = $department;
            $this->crud->addClause('where', 'school_year_id', '=', request()->school_year_id);
            $this->crud->addClause('where', 'department_id', '=', request()->department);
            $this->crud->setListView('enrollment.list');
        }
        // ELSE GO TO DASHBOARD
        else {
            $this->data['active_sy']    = SchoolYear::active()
                                                    ->first();
            $this->data['departments']  = Department::with(['enrollment_enrolled', 'enrollment_enrolled.level'])
                                                    ->withCount('enrollment_enrolled', 'students', 'levels')
                                                    ->active()
                                                    ->get();
            $this->data['department_per_schoolYears'] = Enrollment::with('level', 'department', 'schoolYear')->where('is_applicant', 0)
                                                    ->get();

            // $this->data['active_sy_population']       = collect($this->data['departments'])
            //                                             ->pluck('enrollments')->map(function ($item, $key) {
            //                                                 return collect($item)->where('school_year_id', $this->data['active_sy']->id)
            //                                                 ->count();
            //                                             });
            $this->data['enrollments']  =   Enrollment::where('school_year_id', $this->data['active_sy']->id)
                                                ->where('is_applicant', 0)
                                                ->withCount('level')
                                                ->orderBy('department_id')
                                                ->orderBy('level_id')
                                                ->get();
            $this->crud->setHeading('Enrollment Dashboard');                             
            $this->crud->setListView('enrollment.dashboard');
        }
        $this->crud->addClause('where', 'is_applicant', '0');
        $this->crud->setDefaultPageLength(10);
        $this->crud->setCreateView('enrollment.create');

    }

    public function getActiveSchoolYearId(){
        $schoolYear = SchoolYear::where('isActive',1)->first();
        if($schoolYear !== null) {
            return $schoolYear->id;
        }
        return 'No School Year Active';
    }


    private function checkEnrollingLevel($request)
    {
        // GET THE CURRENT GRADE LEVEL
        $enrollment = Enrollment::where('studentnumber', $request->studentnumber)
                                ->orderBy('level_id', 'desc')
                                ->first();                       

        // IF NULL RETURN NOTHING
        if($enrollment == null) { return true; }

        // GET THE GRADE LEVEL SEQUENCE 
        $currentGradeLevel    = YearManagement::where('id', $enrollment->level_id)->first();
        $currentLevelSequence = $currentGradeLevel->sequence;


        // ACQUIRING GRADE LEVEL
        $acquiringGradeLevel    = YearManagement::where('id', $request->level_id)->first();
        $acquiringLevelSequence = (int)$acquiringGradeLevel->sequence; 

        // GET THE NEXT ELIGIBLE GRADE LEVEL
        $nextEligibleGradeLevel = YearManagement::where('sequence', $currentLevelSequence + 1)->first();

        // Get Semestral Type And Check If Can Enroll Again For 2nd Sem
        // Query Term To Get The Term Type
        $term = TermManagement::where('department_id', $request->department_id)->first();
        $matchTerm = false;

        if($term === null) {
            \Alert::warning("Error, No Term Type Found For This Enrollment")->flash();
            return false;
        }

        // IF Full Term
        if($term->type === "Semester") {
            $enrollment = Enrollment::where('studentnumber', $request->studentnumber)
                                    ->where('school_year_id', $request->school_year_id)
                                    ->where('department_id', $request->department_id)
                                    ->where('level_id', $request->level_id)
                                    ->where('track_id', $request->track_id)
                                    ->get();

            if(count($enrollment) > 1) {
                \Alert::warning("You Can't Enrolled More Than This Year")->flash();
                return false; 
            } else {
                return true;
            }
        } 

        // IF OVERRIDE SEQUENCE IS CHECKED DO NOT VALIDATE NEXT ELIGIBLE
        if($request->override_sequence) { return true; }

        // CHECK IF STEPPING DOWN LEVEL
        if($acquiringLevelSequence < $nextEligibleGradeLevel->sequence) {
            \Alert::warning("Sorry Steppig Down Is Not Allowed, The Next Eligible Grade Level For You Is <b>" . $nextEligibleGradeLevel->year . "</b>")->flash();
            return false;
        }
        else if ($acquiringLevelSequence > $nextEligibleGradeLevel->sequence) { // CHECK IF STEPPING UP TO THE NEXT ELIGIBLE SCHOOL YEAR
            \Alert::warning("Sorry Steppig Up More Than Next Eligible Grade Level Is Not Allowed, The Next Eligible Grade Level For You Is: " . $nextEligibleGradeLevel->year)->flash();
            return false;
        }
        else if ($nextEligibleGradeLevel->id == $request->level_id ) { // CHECK IF MATCH TO ACQUIRING AND ELIGIBLE GRADE LEVEL
            return true;
        } else {
            \Alert::warning("Something Went Wrong, Please Try Again")->flash();
            return false;
        }
    }


    public function store(StoreRequest $request)
    {
        $isEligible = self::checkEnrollingLevel($request);

        if($isEligible == false) {
            return redirect()->back()->withInput($request->input());
        }

        // CHECK IF THE TUTION FORM IS MATCHING TO HIS/HER GRADE LEVEL
        $tuition = Tuition::where('id', $request->tuition_id); 
        
        if(!$tuition->exists()) {
            \Alert::warning("Tuition Not Found!")->flash();
            return back();
        }

        if($request->level_id !== $tuition->first()->grade_level_id) {
            \Alert::warning("Tuition Grade Level And Enrollment Level Did Not Match!")->flash();
            return back();
        }

        // Check if $studentnumber is existing on students table
        $student = Student::where('studentnumber', $request->studentnumber)->first();
        
        if($student == null) {
            \Alert::warning("Student Data Not Found")->flash();
            return redirect()->back()->withInput($request->input());
        }

        /**
         * [$student->qbo_customer_id CHECK IF QBO IS NULL THEN NEED TO REGISTER QUICKBOOKS FIRST]
         * @var [type]
         */
        if($student->qbo_customer_id == null){
            \Alert::warning("Please Register This Student On QuickBooks First")->flash();
            return redirect()->back()->withInput($request->input());
        }

        /**
         * [$tuition CHECK IF STUDENT IS EXISTING TUITION FORM]
         * @var [type]
         */
        $tuition = Tuition::where('id', $request->tuition_id)
                          ->where('grade_level_id', $request->level_id)
                          ->where('schoolyear_id', $request->school_year_id)->exists();
        
        if($tuition === false) {
            \Alert::warning("Tuition is not existing.")->flash();
            return redirect()->back()->withInput($request->input());
        }

        // CHECK WHAT STUDENT WILL APPLYING FOR TERM TYPE -> (Full Term or Semestral)

        // Query Term To Get The Term Type
        $term = TermManagement::where('department_id', $request->department_id)->first();

        if($term === null) {
            \Alert::warning("Error, No Term Type Found For This Enrollment")->flash();
            return back();
        }

        // IF Full Term
        if($term->type === "FullTerm") {
            $request->request->set('term_type', "Full");
        } 
        else if ($term->type === "Semester") {

            // Check If It Has No Record For This Current School Year.
            $enrollment = Enrollment::where('studentnumber', $request->studentnumber)->where('school_year_id', $request->school_year_id)->exists();
            
            // !$enrollment, Return false,
            if(!$enrollment) {
                $request->request->set('term_type', "First");
            } 
            else { // $enrollment, Return True

                // Check The Existing Record And Match All Current Selected Level, Department And Track
                $IsPreviousEnrollmentExist = Enrollment::where('studentnumber', $request->studentnumber)
                                                        ->where('school_year_id', $request->school_year_id)
                                                        ->where('department_id', $request->department_id)
                                                        ->where('level_id', $request->level_id)
                                                        ->where('track_id', $request->track_id)
                                                        ->exists();

                // If Previous Enrollment Exist And Did Match To The Enrollment Record, Return True 
                if($IsPreviousEnrollmentExist) {
                    $request->request->set('term_type', "Second");
                } else {
                    \Alert::warning("Error, You Selected Details Are Did Not Match To Your Previous Enrollment")->flash();
                    return back();
                }
            
            }
        } else {
            \Alert::warning("Something Went Wrong, Please Contact an Administrator.")->flash();
            return back();
        }

        // $enrollment = Enrollment::where('studentnumber', $request->studentnumber)
        //                         ->where('school_year_id', $request->school_year_id)
        //                         ->get();

        // if(count($enrollment) > 0) {
        //     \Alert::error('This Student Is Already Enrolled')->flash();
        //     return redirect()->back()->withInput($request->input());
        // }

        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function edit ($id)
    {
        // your additional operations before save here
        $enrollment = Enrollment::where('id', $id)->first();

        // Check If Applicant Return Back
        if($enrollment->is_applicant) {
            \Alert::warning('Applicant Status Is Not Allowed To Edit')->flash();
            return redirect()->back();
        }

        // CHECK IF IT IS ALREADY INVOICE IT SHOULD NOT ALLOWED TO UPDATE
        if($enrollment->invoice_no !== null)
        {
            \Alert::warning("You aren't allowed to update any changes.")->flash();
            return redirect('/admin/enrollment');
        }

        $this->crud->hasAccessOrFail('update');
        $this->crud->setOperation('update');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // get the info for that entry
        $this->data['entry']       = $this->crud->getEntry($id);
        $this->data['crud']        = $this->crud;
        $this->data['saveAction']  = $this->getSaveAction();
        $this->data['fields']      = $this->crud->getUpdateFields($id);
        $this->data['title']       = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;
        $this->data['id']          = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }


    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $enrollment = Enrollment::where('id', $request->id)->first();

        // CHECK IF IT IS ALREADY INVOICE IT SHOULD NOT ALLOWED TO UPDATE
        if($enrollment->invoice_no !== null)
        {
            \Alert::warning("You won't allowed to update any changes.")->flash();
            return redirect()->back();
        }

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


    // CUSTOM FUNCTIONS
    public function searchStudent (Request $request, $string)
    {
        $students = Student::where('studentnumber', 'LIKE', '%' . $string . '%')
                            ->orWhere('firstname', 'LIKE', '%' . $string . '%')
                            ->orWhere('middlename', 'LIKE', '%' . $string . '%')
                            ->orWhere('lastname', 'LIKE', '%' . $string . '%')
                            ->select('id', 'studentnumber', 'gender', 'schoolyear', 'level_id', 'firstname', 'middlename', 'lastname')
                            ->paginate(5);

        return response()->json($students);
    }


    public function getSections (Request $request)
    {
        $year_id  = $request->year_id;
        $level_id = $request->level_id;
        $sections = SectionManagement::where('level_id', $level_id)->where('year_id', $year_id)->where('isActive', 1)->get();

        return response()->json($sections);
    }

    public function getStudent ($id)
    {
        $students = Student::where('id', $id)->with('level')->with('track')->with('schoolYear')->with('yearManagement')->first();
        return response()->json($students);
    }

    public function downloadInvoice ($id)
    {
        $enrollment = $this->crud->model::where('id', $id)
                                        ->with(['QBOInvoices' => function ($q) {
                                            $q->where('error', 0);
                                        }])
                                        ->firstOrFail();

        if($enrollment->invoice_no) {
            $invoice    = $this->qbo->dataService()->Query("SELECT * FROM Invoice WHERE DocNumber = '". $enrollment->invoice_no ."'");
            $pdf        = $this->qbo->dataService->DownloadPDF($invoice[0]);
            
            return response()->download($pdf, Config::get('settings.schoolabbreviation') . '-' . $enrollment->studentnumber . '.pdf', ['Content-Type: application/pdf']);
        }

        if($enrollment->invoiced) {
            abort(403, "This invoice is not yet available.");
        }
    }

    public function setInvoice ($id, Request $request)
    {   
        $qbo_term = isset($request->qbo_term) ? $request->qbo_term : null;

        if(config('settings.taxrate') === "" || config('settings.taxrate') === null) {
            \Alert::warning("Please Set A Tax Rate On Settings")->flash(); 
            return redirect()->back();
        }

        // INITIALIZE QBO OAUTH
        if($this->qbo->dataService === null)
        {
            $status  = "ERROR";
            $message = "Unauthorized QuickBooks";
            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
        }

        $this->qbo->dataService->setMinorVersion(38);

        $enrollment = $this->crud->model->where('id', $id)
                                        ->with('schoolYear')
                                        ->with('tuition')
                                        ->with('kioskEnrollment')
                                        ->with('student')
                                        ->with('commitmentPayment')
                                        ->first();

        $student    = $enrollment->student;
        $tuition    = $enrollment->tuition;
        $kioskEnrollment = $enrollment->kioskEnrollment;
        $uniqid     = uniqid();

        if($tuition == null)
        {
            \Alert::warning("Tuition Not Found")->flash();
            return redirect()->back();
        }

        // CHECK IF STUDENT EXISTING ON QUICKBOOKS
        if($student->qbo_customer_id === null)
        {   
            \Alert::warning("Student Is Not Yet Registered On QuickBooks")->flash();
            return redirect()->back();
        }

        // // CHECK IF ALREADY HAVE INVOICE
        if($enrollment->invoice_no !== null)
        {
            \Alert::warning("This Enrollment Has Already Invoiced")->flash();
            return redirect()->back();
        }


        $invoiceEmail = $kioskEnrollment ? $kioskEnrollment->email : null;

        $amount     = null;
        $qboLines   = [];

        // INSERT THE TUITION FEE INVOICE
        // TUITION FEE total_payable_upon_enrollment
        $tuitionFees = collect($tuition->tuition_fees)->where('payment_type', $enrollment->commitment_payment_id)->first();

        if(!isset($tuitionFees->qb_map)) {
            \Alert::warning('The Tuition Fee of QB Is Not Yet Mapped.')->flash();
            return back();
        }

        $qboLines[] = [
            "Amount" => $tuitionFees->tuition_fees,
            "DetailType" => "SalesItemLineDetail",
            "SalesItemLineDetail" => [
                "ItemRef"     => [
                    "value" => $tuitionFees->qb_map,
                    // "name"  => $enrolmentFee->Name,
                ],
                "Qty"         => 1,
                "RatePercent" => null,
                "TaxCodeRef" => [
                    // "value" => "NON",
                    "value" => config('settings.taxrate'),
                ]
            ],
            "Description" => 'Tuition Fee: Initial Payment'
        ];

         // EARLY BIRD DISCOUNT
        $qboLines[] = [
            "Amount" => -$tuitionFees->discount,
            "DetailType" => "SalesItemLineDetail",
            "SalesItemLineDetail" => [
                "ItemRef"     => [
                    "value" => $tuitionFees->qb_discount,
                    // "name"  => $enrolmentFee->Name,
                ],
                "Qty"         => 1,
                "RatePercent" => null,
                "TaxCodeRef" => [
                    // "value" => "NON",
                    "value" => config('settings.taxrate'),
                ]
            ],
            "Description" => 'Early Bird Discount'
        ];
        // MISCELLANEOUS
        if($tuition->miscellaneous !== null) {
            if(count($tuition->miscellaneous) > 0) {
                foreach ($tuition->miscellaneous as $key => $miscellaneous) {
                    $qboLines[] = [
                        "Amount" => $miscellaneous->amount,
                        "DetailType" => "SalesItemLineDetail",
                        "SalesItemLineDetail" => [
                            "ItemRef"     => [
                                "value" => $miscellaneous->qb_map,
                                // "name"  => $enrolmentFee->Name,
                            ],
                            "Qty"         => 1,
                            "RatePercent" => null,
                            "TaxCodeRef" => [
                                // "value" => "NON",
                                "value" => config('settings.taxrate'),
                            ],
                        ],
                        "Description" => 'Miscellaneous: ' . $miscellaneous->code
                    ];
                }
            }
        }

        // ACTIVITIES
        if($tuition->activities_fee !== null) {
            if(count($tuition->activities_fee) > 0) {
                foreach ($tuition->activities_fee as $key => $activity) {
                    $qboLines[] = [
                        "Amount" => $activity->amount,
                        "DetailType" => "SalesItemLineDetail",
                        "SalesItemLineDetail" => [
                            "ItemRef"     => [
                                "value" => $activity->qb_map,
                                // "name"  => $enrolmentFee->Name,
                            ],
                            "Qty"         => 1,
                            "RatePercent" => null,
                            "TaxCodeRef" => [
                                // "value" => "NON",
                                "value" => config('settings.taxrate'),
                            ],
                        ],
                        "Description" => 'Activity: ' . $activity->code
                    ];
                }
            }
        }

        // OTHER FEES
        if($tuition->other_fees !== null) {
            if($tuition->other_fees) {
                foreach ($tuition->other_fees as $key => $other) {
                    $qboLines[] = [
                        "Amount" => $other->amount,
                        "DetailType" => "SalesItemLineDetail",
                        "SalesItemLineDetail" => [
                            "ItemRef"     => [
                                "value" => $other->qb_map,
                                // "name"  => $enrolmentFee->Name,
                            ],
                            "Qty"         => 1,
                            "RatePercent" => null,
                            "TaxCodeRef" => [
                                // "value" => "NON",
                                "value" => config('settings.taxrate'),
                            ],
                        ],
                        "Description" => 'Other: ' . $other->code
                    ];
                }
            }
        }

        if(\Config::get('settings.tuitionfeeinvoiceduedate') == null)
        {
            \Alert::warning("Please Set The Due Date Invoice From (System Management > Maintenance > Settings)")->flash();
            return redirect()->back();
        }

        // //Add a new Invoice
        $uponEnrollmentResourceObj = Invoice::create([
            "Line" => $qboLines ,
            "CustomerRef" => [
                "value" => (string)$student->qbo_customer_id
            ],
            // "DueDate" => \Config::get('settings.tuitionfeeinvoiceduedate'),
            "DueDate" => now()->format('Y-m-d'),
            "AutoDocNumber" => true,
            "CustomField" => [
                [
                    "DefinitionId" => "1", 
                    "StringValue" => $tuition->form_name, 
                    "Type" => "StringType", 
                    "Name" => "Tuition Name"
                ],
                [
                    "DefinitionId" => "2", 
                    "StringValue" => 'I-' . $student->studentnumber . '-' . $enrollment->id . '-01', 
                    "Type" => "StringType", 
                    "Name" => "Enrollment No"
                ],
                [
                    "DefinitionId" => "3", 
                    "StringValue" => $enrollment->schoolYear->schoolYear, 
                    "Type" => "StringType", 
                ]
            ],
            "SalesTermRef" => [
                "value" => $qbo_term
            ],
            "BillEmail" => [
                "Address" => $invoiceEmail
            ]
        ]);

        $batch = $this->qbo->dataService()->CreateNewBatch();
        $batch->AddEntity($uponEnrollmentResourceObj, "Invoice1-" . $uniqid, "Create");

        // Partial Payment
        // dd($enrollment->commitmentPayment->snake, $tuition->payment_scheme);
        // dd($tuition->payment_scheme);
        try {
            $counter = 2;
            foreach ($tuition->payment_scheme as $key => $paymentScheme) {
                if($paymentScheme->{$enrollment->commitmentPayment->snake . '_amount'} !== 0) {

                    $partialPaymentObjectLine = [
                        "Amount" => $paymentScheme->{$enrollment->commitmentPayment->snake . '_amount'},
                        "DetailType" => "SalesItemLineDetail",
                        "SalesItemLineDetail" => [
                            "ItemRef"     => [
                                "value" => $tuitionFees->qb_map,
                                // "name"  => $enrolmentFee->Name,
                            ],
                            "Qty"         => 1,
                            "RatePercent" => null,
                            "TaxCodeRef" => [
                                // "value" => "NON",
                                "value" => config('settings.taxrate'),
                            ]
                        ],
                        "Description" => 'Tuition Fee ' . $enrollment->schoolYear->schoolYear . ': Partial Payment #' . $counter
                    ];


                    // //Add a new Invoice
                    $partialPaymentObject = Invoice::create([
                        "Line" => $partialPaymentObjectLine ,
                        "CustomerRef" => [
                            "value" => (string)$student->qbo_customer_id
                        ],
                        // "DueDate" => \Config::get('settings.tuitionfeeinvoiceduedate'),
                        "DueDate" => $paymentScheme->scheme_date,
                        "AutoDocNumber" => true,
                        "CustomField" => [
                            [
                                "DefinitionId" => "1", 
                                "StringValue" => $tuition->form_name, 
                                "Type" => "StringType", 
                                "Name" => "Tuition Name"
                            ],
                            [
                                "DefinitionId" => "2", 
                                "StringValue" => 'I-' . $student->studentnumber . '-' . $enrollment->id . '-' . $counter, 
                                "Type" => "StringType", 
                                "Name" => "Enrollment No"
                            ],
                            [
                                "DefinitionId" => "3", 
                                "StringValue" => $enrollment->schoolYear->schoolYear, 
                                "Type" => "StringType", 
                                "Name" => "School Year"
                            ]
                        ],
                        "BillEmail" => [
                            "Address" => $invoiceEmail
                        ]
                    ]);

                    $batch->AddEntity($partialPaymentObject, "Invoice". $counter . "-" . $uniqid, "Create");
                    $counter++;
                }
            }
        } catch (\Exception $e) {
            abort(403, 'Unable To Set on QBO');
        }
        // dd($batch);
        // Execute Batch Invoices
        $batch->ExecuteWithRequestID($student->studentnumber . '-' . $enrollment->id);
        $error = $batch->getLastError();

        if ($error) {
            \Alert::warning($error->getResponseBody())->flash();
            return redirect()->back();
        }

        $errorCount = 0;
        // Check Each Response, Success or fail
        foreach ($batch->intuitBatchItemResponses as $key => $intuitBatchItemResponse) {
            if($intuitBatchItemResponse->isSuccess()){

                $createdInvoice                         = $intuitBatchItemResponse->getResult();

                $enrollmentQboInvoice                   = new EnrollmentQboInvoice;
                $enrollmentQboInvoice->uniqid           = $uniqid;
                $enrollmentQboInvoice->batch_item_id    = $key;
                $enrollmentQboInvoice->enrollment_id    = $enrollment->id;
                $enrollmentQboInvoice->qbo_id           = $createdInvoice->DocNumber;
                $enrollmentQboInvoice->items            = json_encode($createdInvoice);
                $enrollmentQboInvoice->save();

            } else {

                $result = $intuitBatchItemResponse->getError();

                $enrollmentQboInvoice                   = new EnrollmentQboInvoice;
                $enrollmentQboInvoice->uniqid           = $uniqid;
                $enrollmentQboInvoice->batch_item_id    = $key;
                $enrollmentQboInvoice->enrollment_id    = $enrollment->id;
                $enrollmentQboInvoice->items            = json_encode($result);
                $enrollmentQboInvoice->error            = 1;
                $enrollmentQboInvoice->save();

                $errorCount++;
            }
        }


        if($errorCount == 0) {
            $this->crud->model->where('id', $id)->update(['invoiced' => 1]);
            \Alert::success("Successfully Invoiced To QuickBooks")->flash();
        } 
        else if (count($batch->intuitBatchItemResponses) === $errorCount) {
            $errorMessages = [];
            foreach (collect($batch->intuitBatchItemResponses)->pluck('exception') as $value) {
                $errorMessages[] = $value->getMessage();
            }
            \Alert::warning(json_encode($errorMessages))->flash();
        }
        else {
            \Alert::warning("Successfully Invoiced To QuickBooks (" . $errorCount. " Error Invoice)")->flash();
        }
        return redirect()->back();
        // else {

        //     \Alert::success("Succsesfully Added Invoice To QuickBooks")->flash();

        //     $updateInvoiceNo = $this->crud->model->where('id', $id)->update([
        //         'invoice_no' => $resultingObj->DocNumber
        //     ]);
            
        //     if($updateInvoiceNo) {
        //         \Alert::success("Successfully Binding QuickBooks To SchoolMate Of The Student")->flash();
        //         return redirect()->to('admin/enrollment');
        //     } else {
        //         \Alert::warning("Error Tagging Invoice To Student, Please Try Again.")->flash();
        //     }

        //     return redirect()->to('admin/enrollment');
        // }
    }

    public function deleteInvoice ($invoice_no)
    {

        $enrollment = $this->crud->model::where('invoice_no', $invoice_no)->first();

        if($enrollment->invoice_no === null)
        {
            \Alert::warning("Error...");
            return redirect('/admin/enrollment');
        }

        $this->qboInvoice = $this->qbo->dataService()->Query("SELECT * FROM Invoice WHERE DocNumber = '" . $invoice_no ."'");

        if($this->qboInvoice === null)
        {
            \Alert::warning("Invoice Not Found")->flash();
            return redirect("/admin/enrollment");
        }

        $invoice        = Invoice::create(["Id" => $this->qboInvoice[0]->Id, "SyncToken" => "0"]);
        $resultingObj   = $this->qbo->dataService()->Delete($invoice);

        $error = $this->qbo->dataService()->getLastError();
        if ($error) {
            echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
            echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
            echo "The Response message is: " . $error->getResponseBody() . "\n";
        }

        $enrollment->invoice_no = null;
        $enrollment->save();

        \Alert::success("Successfully Deleted Invoice")->flash();
        return redirect('/admin/enrollment');
    }

    public function voidInvoice ($invoice_no)
    {

        $enrollment = $this->crud->model::where('invoice_no', $invoice_no)->first();

        if($enrollment->invoice_no === null)
        {
            \Alert::warning("Error...");
            return redirect()->back();
        }

        $this->qboInvoice = $this->qbo->dataService()->Query("SELECT * FROM Invoice WHERE DocNumber = '" . $invoice_no ."'");

        if($this->qboInvoice === null)
        {
            \Alert::warning("Invoice Not Found")->flash();
            return redirect()->back();
        }

        $invoice        = Invoice::create(["Id" => $this->qboInvoice[0]->Id, "SyncToken" => "0"]);
        $resultingObj   = $this->qbo->dataService()->Void($invoice);

        $error = $this->qbo->dataService()->getLastError();
        if ($error) {
            echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
            echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
            echo "The Response message is: " . $error->getResponseBody() . "\n";
        }

        $enrollment->invoice_no = null;
        $enrollment->save();

        \Alert::success("Successfully Voided Invoice")->flash();
        return redirect()->back();
    }

    public function getTuitions ()
    {
        $validator = \Validator::make(request()->all(), [
            'schoolyear_id' => 'required',
            'department_id' => 'required',
            'level_id'      => 'required',
            // 'track_id'      => 'required|nullable',
        ]);

        if($validator->fails()) { return []; }

        $tuitions   = Tuition::where([
                        'schoolyear_id' => request()->schoolyear_id,
                        'department_id' => request()->department_id,
                        'grade_level_id' => request()->level_id,
                        'track_id' => request()->track_id,
                        // 'active' => 1
                    ])->get()->pluck('form_name', 'id');

        return response()->json($tuitions);
    }

    private function getMonthsForPaymentScheme ()
    {
        $months         = null;
        $schoolYear     = SchoolYear::active()->first();
        if($schoolYear !== null)
        {
            $sy_start_date  = $schoolYear->start_date;
            $sy_end_date    = $schoolYear->end_date;
            
            if($sy_start_date !== null && $sy_end_date !== null)
            {
                do {
                    $start            = Carbon::parse($sy_start_date)->month;
                    $months[]         = Carbon::parse($sy_start_date)->format('F');
                    $sy_start_date    = Carbon::parse($sy_start_date)->addMonths(1);
                } while ($sy_start_date->lte($sy_end_date));
            }
        }
        return $months;
    }

    public function dropTransfer ($id)
    {

        $this->crud->removeAllFields();
        $this->crud->child_resource_included = ['select' => false, 'number' => false];

        $this->data['crud']           = $this->crud;
        $this->data['initial_months'] = self::getMonthsForPaymentScheme();

        $this->crud->hasAccessOrFail('update');
        $this->crud->setOperation('update');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id)->with(['student', 'tuition'])->first();
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['title'] = $this->crud->getTitle() ?? 'Drop/Transfer' . $this->crud->entity_name;
        $this->data['id'] = $id;

        $this->crud->addField([
            'label' => 'Type',
            'name' => 'type',
            'type' => 'select_from_array',
            'options' => ['Dropping' => 'Dropping', 'Transferring' => 'Transferring'],
        ]);

        $this->crud->addField([
            'label' => 'Reason For Dropping / Transferring',
            'name' => 'reason',
            'type' => 'textarea',
            'attributes' => [
                'rows' => 6,
                'class' => 'form-control'
            ]
        ]);

        // GET THE OLD VALUE OF TUITION FEES, MISC, ACTIVITY FEES, OTHER FEES.
        $tuition_fees = $this->data['entry']->tuition->tuition_fees[$this->data['entry']->commitment_payment_id - 1];

        if($this->data['entry']->commitment_payment_id > 1) {
            $attr_name  = null;
            switch ($this->data['entry']->commitment_payment_id) {
                case '2': $attr_name = 'semi_amount'; break;
                case '3': $attr_name = 'quarterly_amount'; break;
                case '4': $attr_name = 'monthly_amount'; break;
                default :
                    \Alert::warning("Error Tuition Payment Type")->flash(); 
                    return redirect()->back(); 
                    break;
            }

            $tuition_fees->tuition_fees = $tuition_fees->tuition_fees + collect($this->data['entry']->tuition->payment_scheme)->sum($attr_name);
            $tuition_fees->total = (string)($tuition_fees->tuition_fees - (float)$tuition_fees->discount);
        }

        $misc_fees      = $this->data['entry']->tuition->miscellaneous;
        $activities_fee = $this->data['entry']->tuition->activities_fee;
        $other_fees     = $this->data['entry']->tuition->other_fees;

        // TUITION FEES
        $this->crud->addField(
            [
                'name' => 'tuition_fees',
                'label' => '<h3 class="text-center" style="margin-bottom: 20px;">MANDATORY FEES UPON ENROLLMENT</h3>',
                'type' => 'tuition.child_tuition_payment_type',
                'entity_singular' => 'Mandatory Fee', // used on the "Add X" button
                'value' => json_encode([$tuition_fees]),
                'columns' => [
                    [
                        'label' => 'Payment Type',
                        'type' => 'child_select_from_array',
                        'name' => 'payment_type',
                        'options' => CommitmentPayment::where('id', $this->data['entry']->commitment_payment_id)->pluck('name', 'id')->toArray(),
                        'attributes' => [
                            'required' => 'required',
                            'readonly' => 'readonly'
                        ]
                    ],
                    [
                        'label' => 'Tuition Fees',
                        'type' => 'child_number',
                        'name' => 'tuition_fees',
                        'attributes' => [
                            'required' => 'required',
                            'readonly' => 'readonly'
                        ]
                    ],
                    [
                        'label' => 'Less : Early Bird Discount',
                        'type' => 'child_number',
                        'name' => 'discount',
                        'ng-model' => 'discount',
                        'attributes' => [
                            'id'        => 'payment_scheme_amount',
                            'required'  => 'required',
                            'readonly' => 'readonly'
                        ]
                    ],
                    [
                        'label' => '',
                        'type' => 'hidden',
                        'name' => 'qb_discount',
                        'attributes' => [
                            'required' => 'required',
                            'readonly' => 'readonly'
                        ]
                    ],
                    [
                        'label' => 'Total',
                        'type' => 'child_text',
                        'name' => 'total',
                        'attributes' => [
                            'id'        => 'total_payment',
                            'readonly'  => 'readonly',
                        ],
                    ],
                    [
                        'label' => '',
                        'type' => 'hidden',
                        'name' => 'qb_map',
                        'options' => [],
                        'attributes' => [
                            'required' => 'required',
                            'readonly' => 'readonly'
                        ]
                    ],
                    [
                        'label' => 'Refund',
                        'type' => 'child_number',
                        'name' => 'refund',
                        'attributes' => [
                            'required' => 'required',
                        ]
                    ],

                ],
                'max' => 1,
        ]);

        // MISCELLANEOUS FEE
        $this->crud->addField([
            'name' => 'miscellaneous',
            'label' => '<h3 class="text-center" style="margin-bottom: 20px;">MISCELLANEOUS</h3>',
            'type' => 'child_misc',
            'value' => json_encode($misc_fees),
            'entity_singular' => 'Add Misc', // used on the "Add X" button
            'columns' => [
                [
                    'label' => 'Code',
                    'type' => 'child_text',
                    'name' => 'code',
                    'attributes' => [
                        'required' => 'required',
                        'readonly' => 'readonly'
                    ]
                ],
                [
                    'label' => 'Description',
                    'type' => 'child_text',
                    'name' => 'description',
                    'attributes' => [
                        'required' => 'required',
                        'readonly' => 'readonly'
                    ]
                ],
                [
                    'label' => 'Amount',
                    'type' => 'child_number',
                    'name' => 'amount',
                    'attributes' => [
                        'name'      => 'currency',
                        'id'        => 'misc_amount',
                        'required'  => 'required',
                        'readonly' => 'readonly'
                    ]

                ],
                [
                    'label' => '',
                    'type' => 'hidden',
                    'name' => 'qb_map',
                    'options' => [],
                    'attributes' => [
                        'required' => 'required',
                        'readonly' => 'readonly'
                    ]

                ],
                [
                    'label' => 'Refund',
                    'type' => 'child_number',
                    'name' => 'refund',
                    'attributes' => [
                        'required' => 'required',
                    ]
                ],
            ],
            'max' => count($misc_fees),
            'min' => count($misc_fees),
        ]);

        // ACTIVITY FEES
        $this->crud->addField([
            'name' => 'activities_fee',
            'label' => '<h3 class="text-center" style="margin-bottom: 20px;">ACTIVITY FEE</h3>',
            'type' => 'child_activities_fee',
            'value' => json_encode($activities_fee),
            'entity_singular' => 'Add Misc', // used on the "Add X" button
            'columns' => [
                [
                    'label' => 'Code',
                    'type' => 'child_text',
                    'name' => 'code',
                    'attributes' => [
                        'required' => 'required',
                        'readonly' => 'readonly'
                    ]
                ],
                [
                    'label' => 'Description',
                    'type' => 'child_text',
                    'name' => 'description',
                    'attributes' => [
                        'required' => 'required',
                        'readonly' => 'readonly'
                    ]
                ],
                [
                    'label' => 'Amount',
                    'type' => 'child_number',
                    'name' => 'amount',
                    'attributes' => [
                        'name'      => 'currency',
                        'id'        => 'misc_amount',
                        'required'  => 'required',
                        'readonly' => 'readonly'
                    ]
                ],
                [
                    'label' => '',
                    'type' => 'hidden',
                    'name' => 'qb_map',
                    'options' => [],
                    'attributes' => [
                        'required'  => 'required',
                        'readonly' => 'readonly'
                    ]
                ],
                [
                    'label' => 'Refund',
                    'type' => 'child_number',
                    'name' => 'refund',
                    'attributes' => [
                        'required' => 'required',
                    ]
                ],
            ],
            'min' => count($activities_fee),
            'max' => count($activities_fee) 
        ]);

        // OTHER FEES
        $this->crud->addField([
            'name' => 'other_fees',
            'label' => '<h3 class="text-center" style="margin-bottom: 20px;">OTHER FEE</h3>',
            'type' => 'child_other_fee',
            'value' => json_encode($other_fees),
            'entity_singular' => 'Add Misc', // used on the "Add X" button
            'columns' => [
                [
                    'label' => 'Code',
                    'type' => 'child_text',
                    'name' => 'code',
                    'attributes' => [
                        'required' => 'required',
                        'readonly' => 'readonly'
                    ]
                ],
                [
                    'label' => 'Description',
                    'type' => 'child_text',
                    'name' => 'description',
                    'attributes' => [
                        'required' => 'required',
                        'readonly' => 'readonly'
                    ]
                ],
                [
                    'label' => 'Amount',
                    'type' => 'child_number',
                    'name' => 'amount',
                    'attributes' => [
                        'name'      => 'currency',
                        'id'        => 'misc_amount',
                        'required'  => 'required',
                        'readonly'  => 'readonly'
                    ]
                ],
                [
                    'label' => '',
                    'type' => 'hidden',
                    'name' => 'qb_map',
                    'options' => [],
                    'attributes' => [
                        'required' => 'required',
                        'readonly' => 'readonly'
                    ]
                ],
                [
                    'label' => 'Refund',
                    'type' => 'child_number',
                    'name' => 'refund',
                    'attributes' => [
                        'required' => 'required',
                    ]
                ],
            ],
            'max' => count($other_fees),
            'min' => count($other_fees)
        ]);

        return view('enrollment.drop_transfer', $this->data);
    }

    public function submitDropTransfer (EnrollmentDropTransferRequest $request)
    {
        dd($request);
    }

    public function print ($id)
    {
        $model = $this->crud->model::where('id', $id)->first();

        // Check If Not Exists
        if($model == null) { abort(404); }

        // Check If Is Applicant
        if($model->is_applicant) {
            \Alert::warning("Error, This enrollment is not enrolled")->flash();
            return redirect()->back();
        }

        $enrollment = $this->crud->model::where('id',$id)
                        ->with(['schoolYear', 'level', 'student', 'schoolYear', 'tuition', 'commitmentPayment', 'track'])
                        ->first();
                        
        $registrar  = Employee::where('position', 'Registrar')->first();
        $student    = $enrollment->student;
        $tuition    = $enrollment->tuition;

        $total_miscellaneous    = collect($tuition->miscellaneous)->sum('amount');
        $total_activities_fee   = collect($tuition->activities_fee ?? [])->sum('amount');
        $total_other_fees       = collect($tuition->other_fees ?? [])->sum('amount');
        $total_payment_scheme   = 0;
        $grand_total            = 0;
        $year                   = now()->format('Y');
        $student_section        = 'TBA';

        // Total Payment Scheme
        if($enrollment->commitment_payment_id == "2") {
            $total_payment_scheme = collect($tuition->payment_scheme)->sum('semi_amount');
        }
        elseif($enrollment->commitment_payment_id == "3") {
            $total_payment_scheme = collect($tuition->payment_scheme)->sum('quarterly_amount');
        }
        elseif($enrollment->commitment_payment_id == "4") {
            $total_payment_scheme = collect($tuition->payment_scheme)->sum('monthly_amount');
        }
        else {
            $total_payment_scheme = 0;
        }

        // Grand Total
        foreach($tuition->grand_total as $gTotal) {
            if($gTotal["payment_type"] == $enrollment->commitment_payment_id) {
                $grand_total = $gTotal["amount"];
            }
        }

        $other_programs     = SelectedOtherProgram::where('enrollment_id', $id)->get();
        $other_services     = SelectedOtherService::where('enrollment_id', $id)->get();
        $special_discounts  = SpecialDiscount::where('enrollment_id', $id)->get();


        if($enrollment == null) { abort(404); }

        // Get Student Section
        $sections                   =   SectionManagement::where('level_id', $enrollment->level_id)
                                                        ->where('track_id', $enrollment->track_id)
                                                        ->where('curriculum_id', $enrollment->curriculum_id)
                                                        ->get();

        $sections_ids               =   collect($sections)->pluck('id');

        $studentSectionAssignments  =   StudentSectionAssignment::whereIn('section_id', $sections_ids)
                                                        ->where('school_year_id', $enrollment->school_year_id)
                                                        ->get();

        foreach ($studentSectionAssignments as $key => $studentSectionAssignment) {

            $students   = Student::whereIn('studentnumber', json_decode($studentSectionAssignment->students))
                                ->where('studentnumber', $enrollment->studentnumber)
                                ->get();

            if(count($students) > 0){
                $student_section = $studentSectionAssignment;
            }
        }

        $subject_mappings   =   SubjectMapping::where('curriculum_id', $enrollment->curriculum_id)
                                    ->where('department_id', $enrollment->department_id)
                                    ->where('level_id', $enrollment->level_id)
                                    ->where('track_id', $enrollment->track_id)
                                    ->where('term_type', $enrollment->term_type)
                                    ->get();

        $subject_mapping    =   $subject_mappings->last();

        // Get Total Payment
        $payment_history    =   PaymentHistory::where('enrollment_id', $enrollment->id)->get();
        $total_payment      =   collect($payment_history)->sum('amount');

        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper(array(0, 0, 612, 936), 'portrait');

        $pdf->loadHTML( view('enrollment.printPDF', compact('enrollment', 'student', 'tuition', 'total_miscellaneous', 'total_activities_fee', 'total_other_fees', 'total_payment_scheme', 'grand_total', 'year', 'student_section', 'subject_mapping', 'total_payment', 'registrar')) );
        return $pdf->stream(config('settings.schoolabbr') . $enrollment->studentnumber . '.pdf');
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

    public function enroll ($id)
    {
        $model = $this->crud->model::where('id', $id)
                                ->with(['kioskEnrollment' => function ($query) {
                                    $query->with('student');
                                }])
                                ->first();

        if(!$model) {
            abort(404, 'No Enrollment Found');
        }

        if(!$model->is_applicant && $model->studentnumber == null) {
            \Alert::warning("This Enrollment Is Already Enrolled")->flash();
            return redirect('admin/enrollment');
        }

        $this->data['crud'] = $this->crud;
        $this->data['entry'] = $model;
        $this->data['levels'] = YearManagement::select('id', 'year', 'department_id')->get();
        $this->data['tracks'] = TrackManagement::select('id', 'code', 'level_id')->where('active', 1)->get();
        $this->data['terms'] = TermManagement::select('id', 'type', 'no_of_term', 'department_id')->get();

        $this->crud->removeFields(['searchStudent', 'enrollment_script']);
        $this->crud->addField([
            'label' => '',
            'type' => 'enrollment.scriptEnroll',
            'name' => 'enrollment_script'
        ])->afterField('studentnumber');

        return view('enrollment.enroll', $this->data);
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

    public function getEnrolled (Request $request) 
    {   
        $this->sy = $request->sy;
        $students = Enrollment::with('student')
                            ->join('students', function ($join) {
                                $join->on('students.studentnumber', 'enrollments.studentnumber')
                                ->where('enrollments.school_year_id', '=', $this->sy)
                                ->where('enrollments.is_applicant', '=', 0);
                            })->where('students.studentnumber', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('students.firstname', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('students.middlename', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('students.lastname', 'LIKE', '%' . $request->search . '%')
                            ->orderBy('students.lastname', 'ASC')
                            ->orderBy('students.firstname', 'ASC')
                            ->orderBy('students.middlename', 'ASC')
                            ->select('enrollments.*')
                            ->paginate(5);
        $students->setPath(url()->current());
        return response()->json($students);
    }

    public function showTuition ($enrollment_id)
    {
        // Get Data From EnrollmentTuitionController / Same Data That Shows in Student
        $enrollmentTuition = new EnrollmentTuitionController();
        $data = $enrollmentTuition->allTuitionFeeData($enrollment_id);
        $data = $data->original;
        
        $total_mandatory_fees_upon_enrollment = 0;
        $total_payable_upon_enrollment        = 0;

        // Get Total Mandatory Fees Upon Enrollment
        if($data['tuition']->total_mandatory_fees_upon_enrollment)
        {
          if(count($data['tuition']->total_mandatory_fees_upon_enrollment) > 0)
          {
              foreach ($data['tuition']->total_mandatory_fees_upon_enrollment as $key => $mandatory_fee) {
                  if($mandatory_fee['payment_type'] == $data['enrollment']->commitment_payment_id)
                  {
                      $total_mandatory_fees_upon_enrollment = $mandatory_fee['amount'];
                  }
              }
          }
        }

        // Get Total Payable Upon Enrollment
        if($data['tuition']->tuition_fees)
        {
            if(count($data['tuition']->tuition_fees) > 0)
            {
                foreach ($data['tuition']->tuition_fees as $key => $tuition_fee) {
                    if($tuition_fee->payment_type == $data['enrollment']->commitment_payment_id)
                    {
                        $total_payable_upon_enrollment = $tuition_fee->total;
                    }
                }
            }
        }

        $data['total_mandatory_fees_upon_enrollment']   = $total_mandatory_fees_upon_enrollment;
        $data['total_payable_upon_enrollment']          = $total_payable_upon_enrollment;
        
        return  view('enrollment.show_tuition', compact('data'));
    }

    public function changePaymentPlan($enrollment_id, Request $request) 
    {
        $enrollment = Enrollment::where('id', $enrollment_id)->first();
        $errorCount = 0;
        $errorMessages = [];

        if(!$enrollment)
        {
            \Alert::warning("Enrollment Not Found.");
            return redirect()->back();
        }

        $enrollment->commitment_payment_id = $request->commitment_payment_id;

        if($enrollment->invoice_no === null)
        {
            if(count($enrollment->QBOInvoices)>0) {
                foreach($enrollment->QBOInvoices as $enrollment_invoice) {

                    if($enrollment_invoice->qbo_id) {
                        $this->qboInvoice = $this->qbo->dataService()->Query("SELECT * FROM Invoice WHERE DocNumber = '" . $enrollment_invoice->qbo_id ."'");

                        if($this->qboInvoice === null){
                    
                        } else {
                            // $invoice        = Invoice::create(["Id" => $this->qboInvoice[0]->Id, "SyncToken" => "0"]);
                            // $resultingObj   = $this->qbo->dataService()->Void($invoice);
                            $resultingObj   = $this->qbo->dataService()->Void($this->qboInvoice[0]);

                            $error = $this->qbo->dataService()->getLastError();
                            if ($error) {
                                $errorCount++;
                                echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
                                echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                                echo "The Response message is: " . $error->getResponseBody() . "\n";
                            }
                        }
                    }

                }
            } 
        } else {
            $this->qboInvoice = $this->qbo->dataService()->Query("SELECT * FROM Invoice WHERE DocNumber = '" . $invoice_no ."'");

            if($this->qboInvoice === null)
            {
                \Alert::warning("Invoice Not Found")->flash();
                return redirect()->back();
            }

            $invoice        = Invoice::create(["Id" => $this->qboInvoice[0]->Id, "SyncToken" => "0"]);
            $resultingObj   = $this->qbo->dataService()->Void($invoice);

            $error = $this->qbo->dataService()->getLastError();
            if ($error) {
                $errorCount++;
                echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
                echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                echo "The Response message is: " . $error->getResponseBody() . "\n";
            }
        }

        if($errorCount == 0) {
            $enrollment->invoice_no = null;
            $enrollment->invoiced = 0;
            $enrollment->save();
            self::setInvoice($enrollment->id, $request);

            \Alert::success("Payment Plan has been updated successfully.")->flash();
            return redirect()->back();
        } else {
            \Alert::error("Payment Plan has been updated successfully.")->flash();
            return redirect()->back();
        }

    }

    public function generateQRCode($id) 
    {
        $enrollment = Enrollment::where('id', $id)->first();

        if(!$enrollment)
        {
            \Alert::warning("Enrollment Not Found.");
            return redirect()->back();
        }

        if($enrollment->qr_code)
        {
            \Alert::warning("This Enrollment QR Code has already been generated.");
            return redirect()->back();
        }

        if(!$enrollment->student)
        {
            \Alert::warning("Student Not Found.");
            return redirect()->back();
        }

        if(!$enrollment->student->studentnumber)
        {
            \Alert::warning("Studentnumber Not Found.");
            return redirect()->back();
        }

        $qr_code = Hash::make($enrollment->student->studentnumber . ' ' . $enrollment->student->birthdate);

        $enrollment->qr_code = $qr_code;
        $enrollment->save();

        \Alert::success("QR Code has been generated successfully.");
        return redirect()->back();
    }

    public function disableAccount($enrollment_id)
    {
        $enrollment = Enrollment::where('id', $enrollment_id)->first();

        if(!$enrollment) {
            \Alert::error("Enrollment Not Found.");
            return redirect()->back();
        }

        if(!$enrollment->studentCredential) {
            \Alert::error("No Student Portal Account");
            return redirect()->back();
        }

        $enrollment->studentCredential->is_disabled = 1;
        $enrollment->studentCredential->save();

        \Alert::success("Student Portal has been disabled successfully.");
        return redirect()->back();
    }

    public function enableAccount($enrollment_id)
    {
        $enrollment = Enrollment::where('id', $enrollment_id)->first();

        if(!$enrollment) {
            \Alert::error("Enrollment Not Found.");
            return redirect()->back();
        }

        if(!$enrollment->studentCredential) {
            \Alert::error("No Student Portal Account");
            return redirect()->back();
        }

        $enrollment->studentCredential->is_disabled = 0;
        $enrollment->studentCredential->save();

        \Alert::success("Student Portal has been enabled successfully.");
        return redirect()->back();
    }
}