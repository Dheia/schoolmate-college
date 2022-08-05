<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\StudentRequest as StoreRequest;
use App\Http\Requests\StudentRequest as UpdateRequest;

use App\Http\Controllers\Cpanel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\DB;

// QUICKBOOKS
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;
use App\Http\Controllers\QuickBooks\QuickBooksOnline as QBO;


use App\Models\Student;
use App\Models\SchoolYear;
use App\Models\TrackManagement;
use App\Models\Department;
use App\Models\YearManagement;
use App\StudentCredential;

use App\Http\Controllers\Admin\Student\OrderFieldsController;
use App\Http\Controllers\Admin\Student\StudentInformationController;
use App\Http\Controllers\Admin\Student\FamilyBackgroundController;
use App\Http\Controllers\Admin\Student\MedicalHistoryController;
use App\Http\Controllers\Admin\Student\OtherInformationController;
use App\Http\Controllers\Admin\Student\NotificationController;

use Carbon\Carbon;

class StudentCrudController extends CrudController
{
    public $department_id  = null;
    public $school_year_id = null;
    public function setup()
    {
        $this->department_id     = request()->department;
        $this->school_year_id = request()->school_year_id;
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Student');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/student');
        $this->crud->setEntityNameStrings('student', 'students');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        if($this->crud->getActionMethod() === "edit") {
            if(! backpack_user()->hasRole('Admission')) {
                \Alert::warning("You don't have necessary permission to see this page!")->flash();
                abort(403, 'Unauthorize Access.');
            }
        }

        $this->crud->setFromDb();

        // CREATE VIEW
        $this->crud->setCreateView('student.create');


        // add asterisk for fields that are required in EnrollmentRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->enableAjaxTable();


        $this->crud->addButtonFromView('line', 'delete', 'student.delete', 'end'); // add a button whose 
        $this->crud->addButtonFromView('top', 'activateAllPortal', 'student.activateAllPortal', 'end'); // add a button whose 

        // ADD REDIRECT BUTTON TOP LEFT OF LIST (ABOVE datatable_info_stack)
        // BUTTON IN LIST = @include('crud::inc.button_stack', ['stack' => 'redirect'])
        $this->crud->allowAccess('redirectButton');
        $this->crud->addButtonFromView('redirect', 'Redirect', 'redirectButton', 'start'); 
        // $this->crud->data['content-header-style'] = "padding: 10px;";
        $this->crud->data['redirectButton']   =   [
            'route'         =>   $this->crud->route, //Button Route (Default Crud Route)
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
        $this->crud->removeButton('revisions');
        $this->crud->removeButton('update');
        $this->crud->removeButton('delete');
        
        $this->crud->allowAccess('revisions');
        $this->crud->addButtonFromView('line', 'Print', 'student.print_application', 'start');  
        $this->crud->addButtonFromView('line', 'View', 'student.update', 'start');  
        $this->crud->addButtonFromView('line', 'Revoke', 'student.portal', 'start');  
        $this->crud->addButtonFromView('line', 'Delete', 'student.delete', 'end');  
          
        $this->crud->data['dropdownButtons'] = [
            'student.create_webmail',
            'student.import_student_qbo',
            // 'student.portal',
            // 'divider',
            // 'student.revisions',
            // 'student.update',
            // 'student.delete'
        ];
        $this->crud->addButtonFromView('line', 'More', 'dropdownButton', 'end');


        // $this->crud->disableResponsiveTable();
        $this->crud->removeAllFields();



        $tabs = [
            'student_info'  => 'Student Information',
            'family_bg'     => 'Family Background',
            'medical'       => 'Medical History',
            'other_info'    => 'Other Information',
            'notifications'    => 'Notifications',
        ];

        $this->crud->addField([
            'name'              => 'student_scripts',
            'type'              => 'student_scripts',
            'wrapperAttributes' => [ 'class' => 'form-group col-md-12' ],
            'tab'               => $tabs['student_info'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | ADD FIELDS
        |--------------------------------------------------------------------------
        */

        new OrderFieldsController($this); // REORDER FIELDS
        new StudentInformationController($this, $tabs['student_info']); // STUDENT INFORMATION FIELDS
        new FamilyBackgroundController($this, $tabs['family_bg']); // FAMILY BACKGROUND FIELDS
        new MedicalHistoryController($this, $tabs['medical']); // MEDICAL HISTORY FIELDS
        new OtherInformationController($this, $tabs['other_info']); // OTHER INFORMATION FIELDS
        new NotificationController($this, $tabs['notifications']); // NOTIFICATIONS FIELDS

        $this->crud->removeButton('create');

        /*
        |--------------------------------------------------------------------------
        | SET COLUMN FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->setColumns(['studentnumber', 'firstname','lastname','middlename','gender']);

        $this->crud->setColumnDetails('studentnumber', [
            'label'  => 'Student No.',
            'type'   => 'text',
            'name'   => 'studentnumber',
            'prefix' => \Setting::get('schoolabbr') . '-',
        ]);

        /*
        |--------------------------------------------------------------------------
        | ADD COLUMNS
        |--------------------------------------------------------------------------
        */

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
            'type'   => 'text',
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

        $this->crud->addColumn([
            'label'         => 'Complete Name',
            'name'          => 'fullname',
            'type'          => 'text',
            'searchLogic'   => function ($query, $column, $searchTerm) {
                $query->orWhere('firstname', 'like', '%'.$searchTerm.'%');
                $query->orWhere('lastname', 'like', '%'.$searchTerm.'%');
            },
            'priority' => 1,
        ])->afterColumn('studentnumber');

        $this->crud->addColumn([  // Select
            'label' => "Email",
            'type' => 'text',
            'name' => 'email',
            'visibleInExport' => true,
            'visibleInTable' => true,
            'priority' => 1,
         ])->afterColumn('fullname');

        $this->crud->addColumn([   // Select
            'label' => "First Name",
            'type' => 'text',
            'name' => 'firstname',
            'visibleInExport' => true,
            'visibleInTable' => false
        ]);
        $this->crud->addColumn([   // Select
            'label' => "Last Name",
            'type' => 'text',
            'name' => 'lastname',
            'visibleInExport' => true,
            'visibleInTable' => false
        ]);

        $this->crud->addColumn([   // Select
            'label' => "Gender",
            'type' => 'text',
            'name' => 'gender',
            'visibleInExport' => true,
            'visibleInTable' => false
        ]);

        $this->crud->addColumn([  // Select
           'label' => "Department",
           'type' => 'select',
           'name' => 'department_id', // the db column for the foreign key
           'entity' => 'department', // the method that defines the relationship in your Model
           'attribute' => 'name', // foreign key attribute that is shown to user
           'model' => "App\Models\Department", // foreign key model
            'visibleInExport' => true,
            'visibleInTable' => false
        ]);

        $this->crud->addColumn([  // Select
           'label' => "Level",
           'type' => 'select',
           'name' => 'level_id', // the db column for the foreign key
           'entity' => 'level', // the method that defines the relationship in your Model
           'attribute' => 'year', // foreign key attribute that is shown to user
           'model' => "App\Models\YearManagement" // foreign key model
        ]);

        $this->crud->addColumn([
           'name' => "birthdate", // The db column name
           'label' => "Birth Date", // Table column heading
           'type' => "date",
            'format' => 'MMMM DD, YYYY', // use something else than the base.default_date_format config value
            'visibleInExport' => true,
            'visibleInTable' => false
        ])->afterColumn('gender');

        $this->crud->addColumn([
           'name' => "calculated_age", // The db column name
           'label' => "Age", // Table column heading
           'type' => "text",
           // 'suffix' => 'years old',
            'visibleInExport' => true,
            'visibleInTable' => false
        ])->afterColumn('birthdate');

        $this->crud->addColumn([
           'name' => "citizenship", // The db column name
           'label' => "Citizenship", // Table column heading
           'type' => "text",
           // 'suffix' => 'years old',
            'visibleInExport' => true,
            'visibleInTable' => false
        ])->afterColumn('calculated_age');

         $this->crud->addColumn([
           'name' => "religion", // The db column name
           'label' => "Religion", // Table column heading
           'type' => "text",
           // 'suffix' => 'years old',
            'visibleInExport' => true,
            'visibleInTable' => false
        ])->afterColumn('citizenship');

        $this->crud->addColumn([
           'name' => "birthplace", // The db column name
           'label' => "Birth Place", // Table column heading
           'type' => "text",
           // 'suffix' => 'years old',
            'visibleInExport' => true,
            'visibleInTable' => false
        ])->afterColumn('religion');

        $this->crud->addColumn([
           'name' => "residential_address", // The db column name
           'label' => "Address", // Table column heading
           'type' => "text",
           // 'suffix' => 'years old',
            'visibleInExport' => true,
            'visibleInTable' => false
        ])->afterColumn('birthplace');

        $this->crud->addColumn([
           'name' => "contactnumber", // The db column name
           'label' => "Contact Number", // Table column heading
           'type' => "text",
           // 'suffix' => 'years old',
            'visibleInExport' => true,
            'visibleInTable' => false
        ])->afterColumn('residential_address');

        $this->crud->addColumn([
           'name' => "father_full_name", // The db column name
           'label' => "Father", // Table column heading
           'type' => "text",
           // 'suffix' => 'years old',
            'visibleInExport' => true,
            'visibleInTable' => false
        ])->afterColumn('contactnumber');

        $this->crud->addColumn([
           'name' => "fatherMobileNumber", // The db column name
           'label' => "Father Number", // Table column heading
           'type' => "text",
           // 'suffix' => 'years old',
            'visibleInExport' => true,
            'visibleInTable' => false
        ])->afterColumn('father_full_name');

        $this->crud->addColumn([
           'name' => "mother_full_name", // The db column name
           'label' => "Mother", // Table column heading
           'type' => "text",
           // 'suffix' => 'years old',
            'visibleInExport' => true,
            'visibleInTable' => false
        ])->afterColumn('fatherMobileNumber');

        $this->crud->addColumn([
           'name' => "mothernumber", // The db column name
           'label' => "Mother Number", // Table column heading
           'type' => "text",
           // 'suffix' => 'years old',
            'visibleInExport' => true,
            'visibleInTable' => false
        ])->afterColumn('mother_full_name');

        $this->crud->addColumn([
           'name' => "legal_guardian_full_name", // The db column name
           'label' => "Guardian", // Table column heading
           'type' => "text",
           // 'suffix' => 'years old',
            'visibleInExport' => true,
            'visibleInTable' => false
        ])->afterColumn('mothernumber');

        $this->crud->addColumn([
           'name' => "legal_guardian_contact_number", // The db column name
           'label' => "Guardian Number", // Table column heading
           'type' => "text",
           // 'suffix' => 'years old',
            'visibleInExport' => true,
            'visibleInTable' => false
        ])->afterColumn('legal_guardian_full_name');

        if(request()->department){
            $department  = Department::with('term')->where('id', request()->department)->first();
            if($department){
                if($department->with_track == '1'){
                    $this->crud->addColumn([
                        'label' => 'Strand',
                        'type'  => 'select',
                        'name'  => 'track_id',
                        'entity'    => 'track',
                        'attribute' => 'code',
                        'model' => 'App\Models\TrackManagement'
                    ]);
                }
            }
        }

        $this->crud->addColumn([
            'label'  => 'Status',
            'name'   => 'is_enrolled',
            'type'   => 'text'
        ]);

        
        $this->crud->removeColumns(['middlename']);
        $this->crud->enableExportButtons();
  
        $this->crud->with('studentCredential');
        $this->crud->orderBy('level_id','asc');
        $this->crud->orderBy('track_id','asc');
        $this->crud->orderBy('lastname','asc');
        $this->crud->orderBy('created_at', 'DESC');

        // Adding Filters to Student

        // if($this->crud->request->schoolyear == null){
        //     $school_year = SchoolYear::where('isActive',1)->first();
        //     if($school_year !== null) {
        //         $this->crud->addClause('where', 'schoolyear', $school_year->id);
        //     }
        // }

        /*
        |--------------------------------------------------------------------------
        | FILTERS
        |--------------------------------------------------------------------------
        */

        // $this->crud->addFilter([ // select2 filter
        //   'name' => 'schoolyear',
        //   'type' => 'select2',
        //   'label'=> 'School Year'
        // ], function() {
        //     return SchoolYear::all()->keyBy('id')->pluck('schoolYear', 'id')->toArray();
        // }, function($value) { // if the filter is active
        //     $this->crud->addClause('where', 'students.schoolyear', $value);
        // });

        // $this->crud->addFilter([ // select2 filter
        //   'name' => 'department_id',
        //   'type' => 'select2',
        //   'label'=> 'Department'
        // ], function() {
        //     return Department::all()->keyBy('id')->pluck('name', 'id')->toArray();
        // }, function($value) { // if the filter is active
        //     $this->crud->addClause('where', 'students.department_id', $value);
        // });

        $this->crud->addFilter([ // select2 filter
          'name' => 'level_id',
          // 'type' => 'level_ajax_track_select2',
          'type' => 'select2',
          'label'=> 'Level'
        ], function() {
            return YearManagement::all()->keyBy('id')->pluck('year', 'id')->toArray();
        }, function($value) { // if the filter is active
            $this->crud->addClause('where', 'students.level_id', $value);
        });
        if(request()->department){
            $department  = Department::with('term')->where('id', request()->department)->first();
            if($department){
                if($department->with_track == '1'){
                    $this->crud->addFilter([ // select2 filter
                      'name' => 'track_id',
                      'type' => 'select2',
                      'label'=> 'Track'
                    ], function() {
                        return TrackManagement::distinct()->pluck('code', 'code')->toArray();
                    }, function($value) { // if the filter is active
                        $this->crud->addClause('trackCode', $value);
                    });
                }
            }
        }

        $this->crud->addFilter([ // select2 filter
          'name' => 'is_enrolled',
          'type' => 'select2',
          'label'=> 'Status'
        ], function() {
            return ['Enrolled' => 'Enrolled', 'Applicant' => 'Applicant'];
        }, function($value) {
            if($value == 'Enrolled'){
                $this->crud->addClause('isEnrolled');
            }
            else if ($value == 'Applicant'){
                $this->crud->addClause('Applicant');
            }
            
        });
        // DEPARTMENT FILTER SCRIPT
         $this->crud->addFilter([   // Select
            'label' => "",
            'type' => 'student_department_script',
            'name' => 'scipt'
        ]);
        /*
        |--------------------------------------------------------------------------
        | PERMISSIONS
        |--------------------------------------------------------------------------
        */

        if(env('APP_DEBUG') == false) {
            $pathRoute = str_replace("admin/", "", $this->crud->route);
            $user = backpack_auth()->user();

            $permissions = collect($user->getAllPermissions());
            $allowed_method_access = $user->getAllPermissions()->pluck('name')->toArray();

            foreach ($permissions as $permission) {
                if($permission->page_name == $pathRoute) {
                    $methodName = strtolower( explode(' ', $permission->name)[0] );
                    array_push($allowed_method_access, $methodName);
                }
            }

            $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone', 'show']);
            $this->crud->allowAccess($allowed_method_access);
        }

        $this->data['schoolYears']  = SchoolYear::with(['students', 'students.department'])
                                                    ->withCount('students')
                                                    ->get();
        $employee_departments = DB::table('employee_departments')
                                        ->where('employee_id', backpack_auth()->user()->employee_id)
                                        ->get();
        if(!backpack_auth()->user()->hasRole('Administrator'))
        {
            $this->data['departments']  = Department::with('students')
                                            ->whereIn('id',  $employee_departments->pluck('department_id'))
                                            ->active()
                                            ->get();
        }
        else{
            $this->data['departments']  = Department::with('students')->active()->get();
        }
        // IF Parameters is Present Go TO Student List
        if($this->department_id){

            $employee_department = DB::table('employee_departments')
                                        ->where('employee_id', backpack_auth()->user()->employee_id)
                                        ->where('department_id', $this->department_id)
                                        ->first();

            if(!backpack_auth()->user()->hasRole('Administrator') && !$employee_department)
            {
                abort(403, 'No designated department. Contact your administrator for more information.');
            }
            $this->crud->removeColumns(['department_id','school_year_id']);
            $this->data['department']   = Department::where('id', $this->department_id)->first();
            $this->crud->addClause('where', 'department_id', '=', $this->department_id);
            $this->crud->setHeading('Student List'); 
            $this->crud->setListView('student.list');
        }
        // DASHBOARD
        else {
            $this->data['active_sy']    = SchoolYear::active()->first();
            $this->data['students']     = $this->crud->model::with('level', 'track', 'department', 'schoolYear')
                                                            ->get();
            $this->crud->setHeading('Student Records'); 
            $this->crud->setListView('student.dashboard');
        }
        $this->crud->disablePersistentTable();
    }

    private function getNextStudentUserId ($schoolYear) {
        
        $extracted     = preg_replace('/-(\d+)/', '', $schoolYear);
        $lastTwoDigits = substr($extracted, -2); 

        $studentId = Student::where('studentnumber', 'LIKE', (int)$lastTwoDigits . '%')
                               ->orderBy('studentnumber', 'DESC')
                               ->pluck('studentnumber')
                               ->first();

        if($studentId == null) {
            $studentId = $lastTwoDigits . "0001";
            return (int)$studentId;
        }                       

        return (int)$studentId + 1;
    }

    public function registerStudentQBO ($id)
    {
        $results = [];

        // GET STUDENT DATA FROM DATABASE
        $student     = $this->crud->model->where('id', $id);
        $studentData = $student->first();

        // VALIDATION
        if($studentData == null) {
            \Alert::success('Student Data Not Found')->flash();
            return redirect()->back();
        }

        if ($studentData !== null && $studentData->qbo_customer_id !== null) {
            \Alert::success('This Student Is Already Registered')->flash();
            return redirect()->back();
        }


        //  STARTING TO COPYING STUDENT DATA AND REGISTER IT ON QUICKBOOKS
        $qbo =  new QBO;
        $qbo->initialize();

        if($qbo->dataService() === null)
        {
            $status  = "ERROR";
            $message = "Unauthorized QuickBooks";

            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
        }

        $theResourceObj = Customer::create([
            "BillAddr"           => [ "Line1" => $studentData->residentialaddress, ],
            "GivenName"          => $studentData->firstname,
            "MiddleName"         => $studentData->middlename,
            "FamilyName"         => $studentData->lastname,
            "FullyQualifiedName" => $studentData->firstname . ' ' . $studentData->middlename . ' ' . $studentData->lastname,
            "CompanyName"        => \Config::get('settings.schoolname'),
            "DisplayName"        => $studentData->firstname . ' ' . $studentData->middlename . ' ' . $studentData->lastname,
            "PrimaryEmailAddr"   => [ "Address" => $studentData->email ],  
        ]);


        $resultingObj = $qbo->dataService()->Add($theResourceObj);
        $error = $qbo->dataService()->getLastError();

        if ($error) {
            $status  = "ERROR";
            $message = $error->getResponseBody(); 
            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
        }
        else {
            $student->update([ 'qbo_customer_id' => $resultingObj->Id ]);

            \Alert::success('Successfully Added To QuickBooks')->flash();
            return redirect()->back();
        }
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        // if($request->studentnumber === null || $request->studentnumber === '') {
        $schoolYear = SchoolYear::where('id', $request->schoolyear)->pluck('schoolYear'); 

        if(count($schoolYear) == 0) {
            \Alert::warning('Invalid School Year');
            return redirect()->back();
        }

        $request->request->set("studentnumber", self::getNextStudentUserId($schoolYear[0]));
        $request->request->set("age", Carbon::parse($request->birthdate)->age);

        $redirect_location = parent::storeCrud($request);
        
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // dd($request);
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function delete ($id)
    {   
        $response = ['error' => true, 'message' => 'Unknown Error.', 'data' => null];

        if(!$this->crud->hasAccess('delete')) {
            $response['message'] = 'Unauthorized Access.';
            return $response;
        }

        $student = Student::where('id', $id)->first();

        if($student->qbo_customer_id == null)
        {
            if($student->delete()) { 
                $student->update(array("studentnumber" => null));
                $response['error'] = false;
                $response['message'] = 'The item has been deleted successfully.';
            } else {
                $response['message'] = 'Error Deleting Student.';
                return $response;
            }   
        }   
        
        return $response;
    }
    
    public function smsConnect(Request $request) 
    {
        $code = $request->input('code');
        $app_id = env('SMS_APP_ID');
        $app_secret = env('SMS_APP_SECRET');

        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://developer.globelabs.com.ph/oauth/access_token',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'code' => $code,
                'app_id' => $app_id,
                'app_secret' => $app_secret
            )
        ));



        $resp = curl_exec($ch);

        curl_close($ch);

        $resp = json_decode($resp);

        $check_subs_number = Sms::where('subscriber_number', $resp->subscriber_number);


        $student = new Sms;
        $student->access_token = $resp->access_token;
        $student->subscriber_number = $resp->subscriber_number;
        $student->is_registered = 1;

        if($student->save()) {
            // return redirect()
        }

        if( count($check_subs_number) > 0) {

        }

    }   

    public function activateAllStudentPortal (Request $request)
    {
        try {
            $excludes = StudentCredential::get()->pluck('studentnumber')->toArray(); 
            $students = $this->crud->model->whereNotIn('studentnumber', $excludes)->get();

            if(count($students) === 0) {
                \Alert::warning("All Student Portal Is Already Activated")->flash();
                return redirect()->back();
            }

            foreach ($students as $student) {
                self::portalAuthorization($student->id, 'enable', $request);
            }
            \Alert::success("Successfully Activated All Student Portal")->flash();
            return redirect()->back();
        } catch (Exception $e) {
            \Alert::warning("Error Activating All Student Portal")->flash();
            return redirect()->back();
        }
    }

    public function portalAuthorization ($id, $action, Request $request)
    {
        // GIVE ACCESS PORTAL
        if ($action === 'enable')
        {
            $model  = $this->crud->model::find($id);
            $client = Client::where('password_client', 1)->first();

            if($client === null) {
                \Alert::warning('Error, No Passport Is Set')->flash();
                return back();
            }

            if($model == null)
            {
                \Alert::warning("Student Not Found")->flash();
                return redirect()->back();
            }

            if(StudentCredential::where('studentnumber', $model->studentnumber)->exists()) {
                \Alert::warning("This Student Is Already Registered")->flash();
                return redirect()->back();
            }

            $user = StudentCredential::create([
                'studentnumber' => $model->studentnumber,
                'password'      => bcrypt('PORTAL' . $model->studentnumber),
            ]);

            $request->request->add([
                'grant_type'    => 'password',
                'client_id'     => $client->id,
                'client_secret' => $client->secret,
                'username'      => $model->studentnumber,
                'password'      => 'PORTAL' . $model->studentnumber,
                'is_first_time_login'         => '0',
                'scope'         => '*',
            ]);

            $token = $request->create('oauth/token', 'POST');

            \Route::dispatch($token);
            \Alert::success("Successfully Enable Portal")->flash();
            return redirect()->back();
        }

        // REVOKE ACCESS PORTAL
        if ($action === 'disable')
        {
            $token      = new \Laravel\Passport\Token;
            $model      = $this->crud->model::find($id);
            $credential = StudentCredential::where('studentnumber', $model->studentnumber);

            if($model == null)
            {
                \Alert::warning("Student Not Found")->flash();
                return redirect()->back();
            }

            if($credential->first() === null) {
                \Alert::warning("Student Credentials Not Found")->flash();
                return redirect()->back();
            }

            // FIRST, REVOKED ALL ACCESS TOKEN
            $access_tokens = $token->where('user_id', $credential->first()->id);

            // SECOND, REVOKED ALL REFRESH TOKEN BINDED TO ACCESS TOKEN
            DB::table('oauth_refresh_tokens')
            ->whereIn('access_token_id', $access_tokens->get()->pluck('id')->toArray())
            ->delete();
            $access_tokens->delete();

            $credential->delete() ? \Alert::success('Sucessfully Disabled Portal')->flash() : \Alert::warning('Error Disabling Portal')->flash();
            return redirect()->back();
        }
    }


    public function print ($id)
    {
        $student    = $this->crud->model::where('id',$id)->with(['schoolYear', 'yearManagement', 'track', 'enrollments'])->first();
        $schoolyear = SchoolYear::where('isActive',1)->first();

        if($student == null || $schoolyear == null)
        {
            abort(404);
        }

        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper(array(0, 0, 612, 936), 'portrait');

        $pdf->loadHTML( view('student.print.print', compact('student')) );
        return $pdf->stream(config('settings.schoolabbr') . $student->studentnumber . '.pdf');
    }

    public function getTrack ()
    {
        $tracks = TrackManagement::where('level_id', request()->level_id)->active()->get();
        return response()->json($tracks);
    }

    public function getStudents (Request $request) 
    {   
        $students = $this->crud->model::where('studentnumber', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('firstname', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('middlename', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('lastname', 'LIKE', '%' . $request->search . '%')
                            ->paginate(5);
        $students->setPath(url()->current());
        return response()->json($students);
    }

    // public function getStudent($studentnumber) {
    //     $student = $this->crud->model::where('studentnumber', $studentnumber)->first();

    //     return response($student);
    // }
    public function getStudent($id) {
        $student = $this->crud->model::where('id', $id)->first();

        return response($student);
    }


    public function createEmail ($id)
    {
        // CHECK IF ENV IS NOT SET 
        if(env('WHM_HOST') === null || env('WHM_USER') === null || env('WHM_HASH') === null || env('CPANEL_USER') === null || env('CPANEL_DOMAIN') === null) 
        {
            \Alert::warning("Please Configure Your Credentials For WHM And CPanel");
            return redirect('admin/student'); 
        }
 

        $model = $this->crud->model::where('id', $id);
        // Check Exists
        if(!$model->exists()) {
            \Alert::warning("Employee Does Not Exists")->flash();
            return redirect('admin/student');
        }

        $student = $model->with('webmail')->first();

        // Check If Email Has Already Been Created
        if($student->webmail != null) {
            \Alert::warning("This Has Already Been Added")->flash();
            return back();
        }

        // Check If Their Value Is Null Terminate Creating Email
        if($student->firstname === '' || $student->firstname === null) 
        {
            \Alert::warning("Please Add Firstname Value")->flash();
            return redirect('admin/student/' . $id . '/edit');
        }

        if($student->lastname === '' || $student->lastname === null) 
        {
            \Alert::warning("Please Add Lastname Value")->flash();
            return redirect('admin/student/' . $id . '/edit');
        }

        if($student->studentnumber === '' || $student->studentnumber === null) 
        {
            \Alert::warning("Student Number Required")->flash();
            return redirect('admin/student/' . $id . '/edit');
        }

        if($student->birthdate === null)
        {
            \Alert::warning("Birth Date Required")->flash();
            return redirect('admin/student/' . $id . '/edit');
        }
        
        // First Name: John, Last Name: Doe, STUDENT ID: 1234, email output: jdoe-1234@domainname.com
        $email      = strtolower($student->firstname[0] . $student->lastname . '-' . $student->studentnumber); 
        $email      = str_replace(' ', '', $email); // Remove Whitespace(s) 
        $password   = strtolower(Carbon::parse($student->birthdate)->format('FdY')); // password example output: april151993 (April 15, 1993)
        
        $cpanel = new Cpanel([
            'host'        =>  env('WHM_HOST'), // ip or domain complete with its protocol and port
            'username'    =>  env('WHM_USER'), // username of your server, it usually root.
            'password'    =>  env('WHM_HASH'), // long hash or your user's password
            'auth_type'   =>  'hash', // set 'hash' or 'password'
        ]);

        $result = $cpanel->run('3', 'cpanel', [
            'module'    => 'Email',
            'function'  => 'add_pop',
            'username'  => env('CPANEL_USER'),
            'email'     => $email, 
            'password'  => $password, 
            'domain'    => env('CPANEL_DOMAIN'), 
            'quota'     => 512
        ]);

        $result = json_decode($result, true);

        // If Error
        if(!$result["result"]["status"] == 1) {
            \Alert::warning($result["result"]["errors"][0])->flash();
            return redirect('admin/student/');
        }

        // If No Error Found
        // $model->update([ 'has_webmail' => 1 ]);
        $model->update([ 'email' => $email."@".env('CPANEL_DOMAIN')]);

        \Alert::success($email."@".env('CPANEL_DOMAIN'). " Email Has Been Succesfully Created For Webmail")->flash();

        // $userExists = User::where('email', $email."@".env('CPANEL_DOMAIN'))->exists();
        // if(!$userExists) {
        //     $newUser                = new User;
        //     $newUser->name          = $employee->full_name;
        //     $newUser->employee_id   = $employee->id;
        //     $newUser->email         = $email."@".env('CPANEL_DOMAIN');
        //     $newUser->password      = bcrypt($password);

        //     if($newUser->save()) {
        //         \Alert::success($email."@".env('CPANEL_DOMAIN'). ' Email User Successfully Created.')->flash();

        //         // Add Default Employee After Creating User
        //         $role = Role::findOrCreate('Employee');
        //         backpack_user()->where('id', $newUser->id)->first()->assignRole($role->name);

        //     } else {
        //         \Alert::fbsql_warnings()('Error Creating ', $email."@".env('CPANEL_DOMAIN'). ' Email User For SchoolMATE')->flash();
        //     }
        // }


        return redirect('admin/student/');
    } 


    public function getRecord($id) 
    {
        $student        =   $this->crud->model::with('enrollments')->where('id', $id)->first();
        $schooltable    =   [];

        if(!$student){ abort(404, 'Student not found.'); }

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

        return view('student.record', ['student' => $student]);
    }

    public function disableAccount($student_id)
    {
        $student = Student::where('id', $student_id)->first();

        if(!$student) {
            \Alert::error("Student Not Found.");
            return redirect()->back();
        }

        if(!$student->studentCredential) {
            \Alert::error("No Student Portal Account");
            return redirect()->back();
        }

        $student->studentCredential->is_disabled = 1;
        $student->studentCredential->save();

        \Alert::success("Student Portal has been disabled successfully.");
        return redirect()->back();
    }

    public function enableAccount($student_id)
    {
        $student = Student::where('id', $student_id)->first();

        if(!$student) {
            \Alert::error("Student Not Found.");
            return redirect()->back();
        }

        if(!$student->studentCredential) {
            \Alert::error("No Student Portal Account");
            return redirect()->back();
        }

        $student->studentCredential->is_disabled = 0;
        $student->studentCredential->save();

        \Alert::success("Student Portal has been enabled successfully.");
        return redirect()->back();
    }
}