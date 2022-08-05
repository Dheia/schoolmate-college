<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EmployeeRequest as StoreRequest;
use App\Http\Requests\EmployeeRequest as UpdateRequest;
use App\Models\Employee;

// FACADES
use QuickBooksOnline\API\Facades\Employee as QBOEmployee;
use QuickBooksOnline\API\Data\IPPEmployee;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\QuickBooks\QuickBooksOnline;
use Carbon\Carbon;

use App\Http\Controllers\Cpanel;

// MODELS
use App\Models\EmploymentStatusHistory;
use App\Models\EmploymentStatus;
use App\Models\User;
use App\Models\Role;
use App\Models\EmployeeLeave;
use App\Models\EmployeeSalary;

// TRAITS
use \App\Http\Traits\Fields\EmployeeFieldsTrait;

use DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class EmployeeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EmployeeCrudController extends CrudController
{
    /**
     * Trait EmployeeFieldsTrait
     */
    use EmployeeFieldsTrait;

    public function setup()
    {
        // $user = \Auth::user();
        // $permissions = collect($user->getAllPermissions());

        // $plucked = $permissions->pluck('name');
        // $this->allowed_method_access = $plucked->all();

        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);
        // $this->crud->allowAccess($this->allowed_method_access);
        // $this->crud->denyAccess('clone');
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Employee');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/employee');
        $this->crud->setEntityNameStrings('employee', 'employees');

        // $this->crud->setEditView('edit');
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        // $this->crud->setFromDb();

        $personal_background = "Personal Background";
        $domestic_profile = "Domestic Profile";
        $references = "References";
        $government = "Government";
        $medical = "Medical Information";
        $parents_information = "Parents Information";
        $referral = "Referral";
        // $payroll = "Payroll and Taxes";

        // ------------------------------------- //
        // ------ PAYROLL INFO ------ // 
        // _____________________________________ //

        // ------------------------------------- //
        // ------ PERSONAL BACKGROUND TAB ------ // 
        // _____________________________________ //
        self::addPersonalBackgroundTabFields($personal_background);

        // ------------------------------------ //
        //  ------ MEDICAL TAB FIELDS --------- //
        // ____________________________________ //
        self::addMedicalTabFields($medical);

        // ---------------------------------------------- //
        // ------- PARENTS INFORMATION TAB FIELDS ------- // 
        // ______________________________________________ //
        self::addParentsTabFields($parents_information);

        // ------------------------------------------ //
        // ------- DOMESTIC PROFILE TAB FIELDS ------ // 
        // __________________________________________ //
        self::addDomesticTabFields($domestic_profile);

        // ------------------------------------ //
        //  ------ GOVERNMENT TAB FIELDS ------ //
        // ____________________________________ //
        self::addGovernmentTabFields($government);

        // ----------------------------------- //
        // ------ REFERENCES TAB FIELDS ------ //
        // ___________________________________ //
        self::addReferencesTabFields($references);

        // --------------------------------- //
        // ------ REFERRAL TAB FIELDS ------ //
        // _________________________________ //
        self::addReferralTabFields($referral);


        // ----------------------------- //
        //  ------ ADD COLUMNS --------- //
        // _____________________________ //
        $this->crud->addColumn([
            'label' => 'Employee No.',
            'type'  => 'text',
            'name'  => 'employee_id',
            'prefix' => config('settings.schoolabbr') . ' - '
        ]);

        $this->crud->addColumn([
            'label'  => 'Fullname',
            'name'   => 'fullname',
            'type'   => 'text',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('firstname', 'like', '%'.$searchTerm.'%');
                $query->orWhere('lastname', 'like', '%'.$searchTerm.'%');
            },
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->orderBy('firstname', $columnDirection)->select('employees.*');
            }
        ]);

        $this->crud->addColumn([
            'label' => 'Email',
            'type'  => 'text',
            'name'  => 'email',
        ]);

        $this->crud->addColumn([
            'label' => 'Position',
            'type'  => 'text',
            'name'  => 'position',
        ]);

        $this->crud->addColumn([
            'label' => 'Status',
            'type'  => 'text',
            'name'  => 'employment_status',
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->orderBy('status_id', $columnDirection)->select('employees.*');
            }
        ]);

        $this->crud->addColumn([
            'label' => 'Date Hired',
            'type'  => 'date',
            'name'  => 'date_hired',
        ]);

        $this->crud->addColumn([
            'label' => 'Years of Service',
            'type'  => 'text',
            'name'  => 'total_years_of_service',
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->orderBy('date_hired', $columnDirection)->select('employees.*');
            },
            'visibleInShow' => false,
        ]);

        $this->crud->addColumn([
            'label' => 'Birthday',
            'type'  => 'text',
            'name'  => 'birthday',
        ]);

        // ------ CRUD FIELDS
        // $this->crud->addField($options, 'update/create/both');
        // $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');

        // add asterisk for fields that are required in EmployeeRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->allowAccess(['more']);

        $this->crud->data['dropdownButtons'] = [
            'employee.generateQRCode',
            'employee.add_leave',
            'employee.view_status',
            'employee.generate_coe',
            'divider',
            'employee.add_email_account',
            'employee.reset_password'
        ];
        $this->crud->addButtonFromView('line', 'More', 'dropdownButton', 'end');
        $this->crud->enableExportButtons();

        // $this->crud->addButtonFromView('line', 'btnEmployeeConnect', 'employee.connect', 'beginning');

        // Looking for a way to add a more button so it will minimize the column size of Actions.
        // $this->crud->addButtonFromView('line', 'btnMore', 'more', 'end');

        $this->crud->addFilter([ // select2 filter
            'name' => 'department_id',
            'type' => 'select2',
            'label'=> 'Department'
        ], function() {
            return \App\Models\Department::all()->pluck('name', 'id')->toArray();
        }, function($value) { // if the filter is active
            $employee_departments = DB::table('employee_departments')->where('department_id', $value)->get();
            $this->crud->addClause('whereIn', 'id', $employee_departments->pluck('employee_id'));
        });

        $this->crud->addFilter([ // select2 filter
            'name' => 'non_academic_department_id',
            'type' => 'select2',
            'label'=> 'Non-Academic Department'
        ], function() {
            return \App\Models\NonAcademicDepartment::all()->pluck('name', 'id')->toArray();
        }, function($value) { // if the filter is active
            $employee_non_academic_departments = DB::table('employee_non_academic_departments')->where('non_academic_department_id', $value)->get();
            $this->crud->addClause('whereIn', 'id', $employee_non_academic_departments->pluck('employee_id'));
        });

        $this->crud->removeField('qr_code');

        $this->crud->setDefaultPageLength(10);
        $this->crud->setListView('employee.list');

        $this->crud->addButtonFromView('top', 'Print', 'employee.print', 'end');

    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH EMPLOYEE
    |--------------------------------------------------------------------------
    */
    public function searchEmployee ($string)
    {
        $employees = Employee::where('employee_id', 'LIKE', '%' . $string . '%')
                           ->orWhere('firstname',   'LIKE', '%' . $string . '%')
                           ->orWhere('middlename',  'LIKE', '%' . $string . '%')
                           ->orWhere('lastname',    'LIKE', '%' . $string . '%')
                           ->select('id', 'employee_id', 'firstname', 'middlename', 'lastname', 'position')
                           ->paginate(5);
        $employees->setPath(url()->current());
        return response()->json($employees);
    }

    /*
    |--------------------------------------------------------------------------
    | GET EMPLOYEE
    |--------------------------------------------------------------------------
    */
    public function getEmployee ($employeeNumber)
    {
        $employees = Employee::where('employee_id', $employeeNumber)->first();
        return response()->json($employees);
    }

    public function QBORegisterEmployee ($id)
    {   
        $employee = $this->crud->model::findOrFail($id);

        // VALIDATION
        if($employee == null) {
            \Alert::success('Employee Data Not Found')->flash();
            return redirect()->back();
        }

        if ($employee->qbo_id !== null) {
            \Alert::success('This Employee Is Already Registered')->flash();
            return redirect()->back();
        }

        $qbo      = new QuickBooksOnline;
        $qboInit  = $qbo->initialize();

        $theResourceObj = QBOEmployee::create([
            "EmployeeNumber"   => $employee->employee_id,
            "GivenName"        => $employee->firstname, 
            "FamilyName"       => $employee->lastname,
            "MiddleName"       => $employee->middlename,
            "BirthDate"        => Carbon::parse($employee->date_of_birth),
            "Gender"           => $employee->gender,
            "HiredDate"        => Carbon::parse($employee->date_hired),
            "PrimaryPhone"   => 
            [
                "FreeFormNumber" => $employee->mobile
            ]
            // "PrimaryEmailAddr" => 
            // [ 
            //     "Address" => $employee->email 
            // ], 
        ]);

        $resultingObj = $qboInit->Add($theResourceObj);
        $error        = $qboInit->getLastError();

        if ($error) {
            echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
            echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
            echo "The Response message is: " . $error->getResponseBody() . "\n";
        }
        else {
            Employee::where('id', $id)->update([ 'qbo_id' => $resultingObj->Id ]);
            \Alert::success("Successfully Added To QuickBooks")->flash();
            return redirect()->back();
        }
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        // return $request;
        $request->request->set("age", Carbon::parse($request->date_of_birth)->age);
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

    /*
    |--------------------------------------------------------------------------
    | PASSWORD RESET
    |--------------------------------------------------------------------------
    */
    public function resetPassword($id){
        // CHECK IF ENV IS NOT SET 
        if(env('WHM_HOST') === null || env('WHM_USER') === null || env('WHM_HASH') === null || env('CPANEL_USER') === null || env('CPANEL_DOMAIN') === null) 
        {
            \Alert::warning("Please Configure Your Credentials For WHM And CPanel");
            return redirect('admin/employee'); 
        }

        $model = $this->crud->model::where('id', $id);

        // Check Exists
        if(!$model->exists()) {
            \Alert::warning("Employee Does Not Exists")->flash();
            return redirect('admin/employee');
        }

        $employee = $model->first();

        // Check If Email Has Already Been Created
        if($employee->has_webmail == 0 && $employee->email === null) {
            \Alert::warning("Employee doesnt have an email address yet. Kindly generate in the employee list.")->flash();
            return back();
        }

        $cpanel     =   new Cpanel([
            'host'        =>  env('WHM_HOST'), // ip or domain complete with its protocol and port
            'username'    =>  env('WHM_USER'), // username of your server, it usually root.
            'password'    =>  env('WHM_HASH'), // long hash or your user's password
            'auth_type'   =>  'hash', // set 'hash' or 'password'
        ]);

        $password   = strtolower(Carbon::parse($employee->date_of_birth)->format('FdY'));

        $result = $cpanel->run('3', 'cpanel', [
            'module' => 'Email',
            'function' => 'passwd_pop',
            'username' => env('CPANEL_USER'),
            'email'=> $employee->email, 
            'password' => $password, 
            'domain' => env('CPANEL_DOMAIN')
        ]);

        $user = User::where('email',  $employee->email);
        
        $user->update(['password' => bcrypt($password)]);
        $user->update(['first_time_login' => 0]);
        $user->update(['remember_token' => null]);

        \Alert::success("Successfully changed password.")->flash();

        return redirect('admin/employee/');


    }

    private function defaultPassword ($employee)
    {

    }

    public function createEmail ($id)
    {
        // CHECK IF ENV IS NOT SET 
        if(env('WHM_HOST') === null || env('WHM_USER') === null || env('WHM_HASH') === null || env('CPANEL_USER') === null || env('CPANEL_DOMAIN') === null) 
        {
            \Alert::warning("Please Configure Your Credentials For WHM And CPanel");
            return redirect('admin/employee'); 
        }
 

        $model = $this->crud->model::where('id', $id);
        // Check Exists
        if(!$model->exists()) {
            \Alert::warning("Employee Does Not Exists")->flash();
            return redirect('admin/employee');
        }

        $employee = $model->first();

        // Check If Email Has Already Been Created
        if($employee->has_webmail) {
            \Alert::warning("This Has Already Been Added")->flash();
            return back();
        }

        // Check If Their Value Is Null Terminate Creating Email
        if($employee->firstname === '' || $employee->firstname === null) 
        {
            \Alert::warning("Please Add Firstname Value")->flash();
            return redirect('admin/employee/' . $id . '/edit');
        }

        if($employee->lastname === '' || $employee->lastname === null) 
        {
            \Alert::warning("Please Add Lastname Value")->flash();
            return redirect('admin/employee/' . $id . '/edit');
        }

        if($employee->employee_id === '' || $employee->employee_id === null) 
        {
            \Alert::warning("Employee Number Required")->flash();
            return redirect('admin/employee/' . $id . '/edit');
        }

        if($employee->date_of_birth === null)
        {
            \Alert::warning("Birth Date Required")->flash();
            return redirect('admin/employee/' . $id . '/edit');
        }

        // First Name: John, Last Name: Doe, Employee ID: 1234, email output: jdoe1234@domainname.com
        $email      = strtolower($employee->firstname[0] . $employee->lastname . $employee->employee_id); 
        $email      = str_replace(' ', '', $email); // Remove Whitespace(s) 
        $password   = strtolower(Carbon::parse($employee->date_of_birth)->format('FdY')); // password example output: april151993 (April 15, 1993)

        $cpanel     =   new Cpanel([
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
            return redirect('admin/employee/');
        }

        // If No Error Found
        $model->update([ 'has_webmail' => 1 ]);
        $model->update([ 'email' => $email."@".env('CPANEL_DOMAIN')]);

        // \Alert::success($email."@".env('CPANEL_DOMAIN'). " Email Has Been Succesfully Created For Webmaili")->flash();

        $userExists = User::where('email', $email."@".env('CPANEL_DOMAIN'))->exists();
        if(!$userExists) {
            $newUser                = new User;
            $newUser->name          = $employee->full_name;
            $newUser->employee_id   = $employee->id;
            $newUser->email         = $email."@".env('CPANEL_DOMAIN');
            $newUser->password      = bcrypt($password);

            if($newUser->save()) {
                \Alert::success($email."@".env('CPANEL_DOMAIN'). ' Email User Successfully Created.')->flash();

                // Add Default Employee After Creating User
                $role = Role::findOrCreate('Employee');
                backpack_user()->where('id', $newUser->id)->first()->assignRole($role->name);

            } else {
                \Alert::fbsql_warnings()('Error Creating ', $email."@".env('CPANEL_DOMAIN'). ' Email User For SchoolMATE')->flash();
            }
        }


        return redirect('admin/employee/');
    } 

    public function reviewStatus ($id)
    {
        $this->crud->setEntityNameStrings('Review Status', 'Review Status');
        $this->data['crud'] = $this->crud;
        $this->data['id'] = $id;
        $this->data['employmentStatuses'] = EmploymentStatus::get();
        $this->data['employmentHistories'] = EmploymentStatusHistory::where('employee_id', $id)->with('employmentStatus')->orderBy('created_at', 'desc')->get();
        return view('employee.reviewStatus', $this->data);
    }

    public function updateStatus ($id)
    {

        // Check If Employee Exists
        $employee = $this->crud->model::where('id', $id);
        if(!$employee->exists()) {
            return response()->json(['error' => true, 'message' => 'User Not Found', 'data' => null]);
        }

        // Check If Employment Status Exists
        $employmentStatus = EmploymentStatus::where('id', request()->status_id);
        if(!$employmentStatus->exists()) {
            return response()->json(['error' => true, 'message' => 'Employement Status Not Found', 'data' => null]);
        }

        // dd();
        $statusHistory                          = new EmploymentStatusHistory;
        $statusHistory->employee_id             = $id;
        $statusHistory->employment_status_id    = request()->status_id;
        $statusHistory->status_change_date      = Carbon::parse(request()->status_change_date)->format('Y-m-d');
        $statusHistory->updated_by              = backpack_auth()->user()->id;

        if($statusHistory->save()) {
            $status = EmploymentStatusHistory::where('id', $statusHistory->id)->with('employmentStatus')->first();
            return response()->json(['error' => false, 'message' => 'Successfully Updated Status', 'data' => $status]);
        }

        return response()->json(['error' => true, 'message' => 'Error Updating Status', 'data' => null]);
    }

    public function addLeave ($id)
    {

        $employee = $this->crud->model::where('id', $id)->first();

        if(!$employee) {
            return response()->json(['error' => true, 'message' => 'Employee Is Not Exists', 'data' => null]);
        }

        $validator = Validator::make(request()->all(), [
            'leave_id'      => 'required|exists:leaves,id',
            'days'          => 'required|numeric',
            'start_date'    => 'required|date|date_format:Y-m-d|before_or_equal:end_date',
            'end_date'      => 'required|date|date_format:Y-m-d|after_or_equal:start_date'
        ]);

        if($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->messages(), 'data' => null]);
        }

        $employeeLeave = new EmployeeLeave;
        $employeeLeave->employee_id = $employee->employee_id;
        $employeeLeave->leave_id    = request()->leave_id;
        $employeeLeave->days        = (int)request()->days;
        $employeeLeave->start_date  = request()->start_date;
        $employeeLeave->end_date    = request()->end_date;
        $employeeLeave->description = isset(request()->description) ? request()->description : null;
        $employeeLeave->updated_by  = backpack_auth()->user()->id;

        if($employeeLeave->save()) {
            return response()->json(['error' => false, 'message' => 'Successfully Submitted Leave', 'data' => $employeeLeave]);
        }

        return response()->json(['error' => true, 'message' => 'Error Saving.. Something Went Wrong.', 'data' => null]);
    }

    public function generateQRCode($id) 
    {
        $employee = Employee::findOrFail($id);

        if($employee->qr_code)
        {
            \Alert::warning("Employee's QR Code has already been generated.");
            return redirect()->back();
        }

        $qr_code = Hash::make($employee->employee_id . ' ' . $employee->date_of_birth);

        $employee->qr_code = $qr_code;
        $employee->save();

        \Alert::success("QR Code has been generated successfully.");
        return redirect()->back();
    }
    public function print(){

        $employees = Employee::all();

        $schoollogo      = config('settings.schoollogo') ? (string)\Image::make(config('settings.schoollogo'))->encode('data-url') : null;
        $schoolmate_logo = (string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url');

        if(count($employees) == 0) {
            \Alert::warning('Tangible Asset is empty.')->flash();
            return redirect()->back();
        }

        return view('employee.generateReport',compact('employees','schoollogo','schoolmate_logo'));
    }
    public function printcoe($id){
       
        $employee = $this->crud->model::findOrFail($id);
        $salary   = EmployeeSalary::where('employee_id',$employee->employee_id)->first();

        $schoollogo      = config('settings.schoollogo') ? (string)\Image::make(config('settings.schoollogo'))->encode('data-url') : null;
        $schoolmate_logo = (string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url');
       
     
        return view('employee.generateCOE',compact('salary','employee','schoollogo','schoolmate_logo'));
    }
    
}
