<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OnlineClassAttendanceRequest as StoreRequest;
use App\Http\Requests\OnlineClassAttendanceRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\Student;
use App\Models\Employee;
use App\Models\OnlineClass;
use App\Models\OnlineClassAttendance;

use App\Http\Controllers\Admin\OnlineClassCrudController;

use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class OnlineClassAttendanceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OnlineClassAttendanceCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\OnlineClassAttendance');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/online-class/attendance');
        $this->crud->setEntityNameStrings('Employee Class Attendance', 'Employee Class Attendance');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in StudentOnlineClassAttendanceRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

         $this->crud->addField([
            'name'  => 'searchInput',
            'type'  => 'searchEmployee',
            'label' => 'Search',
            'attributes' => [
                'id'           => 'searchInput',
                'placeholder'  => 'Search For Name or ID Number (ex. 1100224)',
                'autocomplete' => 'off' 
            ]
        ])->beforeField('employee_id');

        $this->crud->addField([
            'name' => 'employee_id',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'employee_id'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Select Period',
            'name' => 'daterange',
            'type' => 'onlineClass.attendance_date_range',
            'attributes' => [
                'id' => 'daterange'
            ],
            'wrapperAttributes' => [
               'class' => 'form-group col-md-6 col-xs-12',
               'id' => 'daterange-form-group'
             ]
        ]);

        // BUTTON RUN
        $this->crud->addField([
            'value' => '<button id="btn-run" class="btn btn-success w-100"><i class="fa fa-eye"></i> Run</button>',
            'type'  => 'custom_html',
            'name'  => 'button_run',
            'wrapperAttributes' => [
               'class' => 'form-group col-md-3 col-xs-12 p-t-25 btn-form-group',
             ]
        ]);

        // BUTTON DOWNLOAD
        $this->crud->addField([
            'value' => '<button id="btn-download" class="btn btn-default w-100"><i class="fa fa-download"></i> Download</button>',
            'type'  => 'custom_html',
            'name'  => 'button_download',
            'wrapperAttributes' => [
               'class' => 'form-group col-md-3 col-xs-12 p-t-25 btn-form-group',
             ]
        ]);

        $this->crud->setListView('onlineAttendance.list');
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

    public function employeeAttendanceLogs($employee_id)
    {
        $response = [
            'error'     => false,
            'message'   => null,
            'data'      => null
        ];

        $startDate  = request()->date_from;
        $endDate    = request()->date_to;
        $employee   = Employee::where('id', $employee_id)->with(['onlineClassAttendances', 'onlineClassAttendances.onlineClass'])->first();

        if(!$employee)
        {
            $response['error']   = true;
            $response['title']   = 'Error';
            $response['message'] = 'Employee Not Found.';
            return $response;
        }

        $attendance =   $employee->onlineClassAttendances
                            ->where('created_at', '>=', $startDate)
                            ->where('created_at', '<=', $endDate . ' 23:59:59');

        $response['data']   =   $attendance;
        return $response;
    }

    public function downloadEmployeeAttendance($employee_id, Request $request)
    {
        $startDate  = $request->date_from;
        $endDate    = $request->date_to;

        $employee   = Employee::where('id', $employee_id)->where('id', $request->employee_id)->first();
        abort_if(!$employee, 404, 'Employee Not Found.');

        $employeeAttendances    =   OnlineClassAttendance::where('user_id', $employee->id)
                                        ->where('user_type', 'App\Models\Employee')
                                        ->where('created_at', '>=', $startDate)
                                        ->where('created_at', '<=', $endDate . ' 23:59:59')
                                        ->get();

        $schoollogo             =   config('settings.schoollogo') 
                                        ? (string)\Image::make(config('settings.schoollogo'))->encode('data-url') 
                                        : null;
        $schoolmate_logo        =   (string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url');

        return view('employeeClassAttendance.generateReport', compact('employee', 'employeeAttendances', 'schoollogo', 'schoolmate_logo', 'startDate', 'endDate'));
    }

    public function submitEmployeeAttendance($class_code, $employee_id)
    {
        $this->data['user'] =   $user   =   backpack_user();

        abort_if(!request()->class_code, 404, 'Class Code Not Found.');
        abort_if(!$user->employee, 404, 'Your User Account Is Not Yet Tag As Employee.');
        abort_if($user->employee->id != $employee_id, 401);

        $currentDate     =  Carbon::now()->toDateString();
        $currentTime     =  Carbon::now()->toTimeString();
        $currentDateTime =  Carbon::now();

        $online_class   =   OnlineClass::where('code', $class_code)->first();

        abort_if(!$online_class, 404, 'Online Class Not Found.');

        $userAttendance     =   OnlineClassAttendance::where('user_id', $user->employee->id)
                                    ->where('user_type', 'App\Models\Employee')
                                    ->where('created_at', '>=', $currentDate)
                                    ->where('created_at', '<=', $currentDate . ' 23:59:59')
                                    ->where('online_class_id', $online_class->id)
                                    ->first();

        if($userAttendance) {
            $userAttendance->time_out = $currentDateTime;
            $userAttendance->update();
            
            \Alert::success("Successfully Tap Out.")->flash();
            return redirect()->back();
        }

        $userAttendance     =   OnlineClassAttendance::create([
                                    'user_id'   => $user->employee->id,
                                    'user_type' => 'App\Models\Employee',
                                    'online_class_id' => $online_class->id,
                                    'time_in'   => $currentDateTime,
                                ]);

        \Alert::success("Successfully Tap In.")->flash();
        return redirect()->back();
    }

    public function submitClassAttendance($qrcode)
    {
        $this->data['user'] =   $user   =   backpack_user();

        abort_if(!request()->class_code, 404, 'Class Code Not Found.');
        abort_if(!$user->employee, 404, 'Your User Account Is Not Yet Tag As Employee.');
        abort_if($user->employee->qr_code != $qrcode, 401);

        $currentDate     =  Carbon::now()->toDateString();
        $currentTime     =  Carbon::now()->toTimeString();
        $currentDateTime =  Carbon::now();

        $online_class   =   OnlineClass::where('code', request()->class_code)->first();

        abort_if(!$online_class, 404, 'Online Class Not Found.');

        $userAttendance     =   OnlineClassAttendance::where('user_id', $user->employee->id)
                                    ->where('user_type', 'App\Models\Employee')
                                    ->where('created_at', '>=', $currentDate)
                                    ->where('created_at', '<=', $currentDate . ' 23:59:59')
                                    ->where('online_class_id', $online_class->id)
                                    ->first();

        if($userAttendance) {
            $userAttendance->time_out = $currentDateTime;
            $userAttendance->update();
            
            \Alert::success("Successfully Tap Out.")->flash();
            return redirect()->back();
        }

        $userAttendance     =   OnlineClassAttendance::create([
                                    'user_id'   => $user->employee->id,
                                    'user_type' => 'App\Models\Employee',
                                    'online_class_id' => $online_class->id,
                                    'time_in'   => $currentDateTime,
                                ]);

        \Alert::success("Successfully Tap In.")->flash();
        return redirect()->back();
    }

    public function getStudentsAttendanceLogs($class_code)
    {
        $response = [
            'error'     => false,
            'message'   => null,
            'data'      => null
        ];
        $onlineClass    = new OnlineClassCrudController();
        $class          = $onlineClass->getClass($class_code);
        $startDate      = request()->startDate;
        $endDate        = request()->endDate;

        if(!$class)
        {
            $response['error']   = true;
            $response['title']   = 'Error';
            $response['message'] = 'Class Not Found.';
            return $response;
        }
        if($class->teacher_id != backpack_auth()->user()->employee_id && !backpack_user()->hasRole('School Head'))
        {
            if($class->substitute_teachers){
                if(count($class->substitute_teachers)>0){
                    if(!in_array(backpack_auth()->user()->employee_id, $class->substitute_teachers)){
                        $response['error']   = true;
                        $response['title']   = 'Error';
                        $response['message'] = 'Unauthorized access.';
                        return $response;
                    } else {
                        $class->isTeacherSubstitute = 1;
                    }
                } else{
                    $response['error']   = true;
                    $response['title']   = 'Error';
                    $response['message'] = 'Unauthorized access.';
                    return $response;
                }
            } else {
                $response['error']   = true;
                $response['title']   = 'Error';
                $response['message'] = 'Unauthorized access.';
                return $response;
            }
        }

        if($class->activeStudentSectionAssignment)
        {
            if(count(json_decode($class->activeStudentSectionAssignment->students))>0)
            {
                $student_list       =   Student::whereIn('studentnumber', json_decode($class->activeStudentSectionAssignment->students))
                                            ->orderBy('gender', 'ASC')
                                            ->orderBy('lastname', 'ASC')
                                            ->orderBy('firstname', 'ASC')
                                            ->orderBy('middlename', 'ASC')
                                            ->get();

                $response['data']   =   $student_list->map(function ($item, $key) use($class, $startDate, $endDate) {
                                            $attendance =   $item->onlineClassAttendances->where('online_class_id', $class->id)
                                                                ->where('created_at', '>=', $startDate)
                                                                ->where('created_at', '<=', $endDate . ' 23:59:59');

                                            $item->filteredAttendance = $attendance;
                                            return $item;
                                        });
            }
        }

        return $response;
    }
}
