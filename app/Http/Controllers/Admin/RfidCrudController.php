<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\RfidRequest as StoreRequest;
use App\Http\Requests\RfidRequest as UpdateRequest;

use App\Models\Student;
use App\Models\Rfid;
use App\Models\SchoolYear;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Collection;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use App\User;

use Illuminate\Support\Facades\DB;

use App\Models\Announcement;

use Carbon\Carbon;
/**
 * Class RfidCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class RfidCrudController extends CrudController
{   

    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Rfid');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/rfid-connect');
        $this->crud->setEntityNameStrings('RFID', 'RFID tagging');

        $this->crud->setCreateView('create');
        $this->crud->setEditView('edit');

        // $pathRoute = str_replace("admin/", "", $this->crud->route);
        // $user = backpack_auth()->user();
        

        // $permissions = collect($user->getAllPermissions());

        // $allowed_method_access = array();

        // foreach ($permissions as $permission) {
            
        //     if($permission->page_name == $pathRoute) {

        //         $methodName = strtolower( explode(' ', $permission->name)[0] );
        //         array_push($allowed_method_access, $methodName);
        //     }
        // }

        // $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone', 'show']);
        // $this->crud->allowAccess($allowed_method_access);

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        $this->crud->addField([
            'label' => '',
            'type' => 'search_student_or_employee',
            'name' => 'search_student_or_employee'
        ])->beforeField('studentnumber'); 


        $student_or_employee = isset($_GET['searchFor']) ? $_GET['searchFor'] : 'student';
        in_array($student_or_employee, ['student', 'employee', 'visitor']) ? '' : $student_or_employee = 'student';

        $this->crud->addField([
            'name' => 'searchInput',
            'type' => 'search' . title_case($student_or_employee),
            'label' => 'Search',
            'attributes' => [
                'id' => 'searchInput',
                'placeholder' => 'Search For Name or ID Number (ex. 1100224)'
            ]
        ])->beforeField('studentnumber');

        $this->crud->addField([
            'name' => 'studentnumber',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'studentNumber'
            ]
        ]);

        $this->crud->addField([
            'label' => "RFID Scan",
            'type' => 'rfid',
            'name' => 'rfid'
        ]);

        // $this->crud->addField([
        //     'label'     => "School Year",
        //     'type'      => 'select2',
        //     'name'      => 'school_year_id',
        //     'entity'    => 'schoolYear',
        //     'attribute' => 'schoolYear'
        // ]);       
        
        $this->crud->addField([
            'label'     => "School Year",
            'type'      => 'select_from_array',
            'name'      => 'school_year_id',
            'options'   => SchoolYear::active()->pluck('schoolYear', 'id'),
        ]);       

        $this->crud->addField([

            'label' => "",
            'type' => 'hidden',
            'name' => 'user_type'
        ]);

        $this->crud->addField([
            'name' => 'is_active',
            'label' => 'Active',
            'type' => 'checkbox',
            'default' => 'true'
        ]);

        $this->crud->addField([
            'name' => 'start_date',
            'label' => 'Start Date',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name' => 'end_date',
            'label' => 'End Date',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addColumn([
            'name' => 'studentnumber',
            'type' => 'text',
            'label' => 'ID No.',
            'prefix' => \Setting::get('schoolabbr') . '-',
        ]);

        $this->crud->addColumn([
            'name' => 'fullname',
            'label' => 'Full Name',
            'type' => 'text'
        ])->afterColumn('studentnumber');      

        $this->crud->addColumn([
            'label'     => "School Year",
            'type'      => 'select',
            'name'      => 'school_year_id',
            'entity'    => 'schoolYear',
            'attribute' => 'schoolYear'
        ])->afterColumn('fullname'); 

        $this->crud->addColumn([
            'name' => 'is_active',
            'label' => 'Active',
            'type' => 'check'
        ]);  

        $this->crud->addColumn([
            'label' => 'Start Date',
            'name' => 'start_date',
            'type' => 'date'
        ])->afterColumn('fullname');

        $this->crud->removeColumns(['user_type','rfid']);

        // add asterisk for fields that are required in RfidRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->allowAccess('update_redis_in');

        // ------ CRUD BUTTONS
        // possible positions: 'beginning' and 'end'; defaults to 'beginning' for the 'line' stack, 'end' for the others;
        $this->crud->addButtonFromView('top', 'btnUpdate', 'update_redis', 'end'); // add a button; possible types are: view, model_function
        $this->crud->addButtonFromView('line', 'update', 'rfid_edit', 'beginning'); 
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $school_year = SchoolYear::where('isActive', 1)->first();
        
        $isRfidExist = $this->crud->model::where([
                                                    'studentnumber'     => $request->studentnumber,
                                                    'user_type'         => $request->user_type,
                                                    'school_year_id'    => $request->school_year_id 
                                                ])->exists();

        if($isRfidExist) {
            \Alert::warning('This User Is Already Exists')->flash();
            return redirect()->back()->withInput($request->input());
        }

        if($request->user_type === "student") {
            $student = Student::where('studentnumber', $request->studentnumber)->exists();

            if(!$student) {
                \Alert::warning("No Existing " . $request->studentnumber . " Found")->flash();
                return back();
            }
        }

        if($request->user_type === "employee") {
            $employee = Employee::where('employee_id', $request->studentnumber)->exists();

            if(!$employee) {
                \Alert::warning("No Existing " . $request->studentnumber . " Found")->flash();
                return back();
            }
        }

        $redirect_location = parent::storeCrud($request);
        $rfid = $request->input('rfid');
        $data = Rfid::with(['student.level'])
                    ->where('school_year_id', $school_year->id)
                    ->where('rfid',$rfid)->first();

        Redis::set($rfid, 
             json_encode(
                [
                    "studentnumber"     => $data->studentnumber ?? null,
                    "user_type"         => $data->user_type,
                    "is_enrolled"       => $this->student->is_enrolled ?? null,
                    "is_active"         => $data->is_active,
                    "is_in"             => "0",
                    "timein"            => $data->student->level->time_in ?? "6:00:00",
                    "timeout"           => $data->student->level->time_out ?? "18:00:00"
                ]
            )
        );

        \Alert::success('Save Successfully')->flash();
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        $rfid = $request->input('rfid');

        $data = Rfid::with(['student.level'])->where('rfid',$rfid)->first();
        Redis::set($rfid, 
             json_encode(
                [
                    "studentnumber"     => $data->studentnumber ?? null,
                    "user_type"         => $data->user_type,
                    "is_enrolled"       => $this->student->is_enrolled ?? null,
                    "is_active"         => $data->is_active,
                    "is_in"             => "0",
                    "timein"            => $data->student->level->time_in ?? "6:00:00",
                    "timeout"           => $data->student->level->time_out ?? "18:00:00"
                ]
            )
        );
        return $redirect_location;
    }

    public function destroy($id)
    {
        $rfid = Rfid::where('id',$id)->first();

        Rfid::where('id',$id)->update(['rfid' => null, 'is_active' => 0]);
        Redis::del($rfid->rfid);
    
        $model = $this->crud->model::findOrFail($id);
        $model->delete();
    }

    public function updateRedis(){
       
        $data = Rfid::with(['student.level'])->where("is_active","=",1)->get();        
        foreach($data as $item)
        {
            if($item)
            {
                $array_data = [
                "studentnumber"     => $item->studentnumber ?? null,
                "user_type"          => $item->user_type,
                "is_enrolled"       => $this->student->is_enrolled ?? null,
                "is_active"         => $item->is_active,
                "is_in"             => "0",
                "timein"            => $item->student->level->time_in ?? "6:00:00",
                "timeout"           => $item->student->level->time_out ?? "18:00:00"

                ];
                Redis::set($item->rfid, json_encode($array_data));
            }
        }

        \Alert::success("Successful!")->flash();
        return "Updated successfully";
    }

    public function updateRedisAllowIn()
    {
        $data = Rfid::where("is_active","=",1)->get();

        foreach($data as $item)
        {
            if($item){
                // dd($item->student->level);
                $array_data = [
                "studentnumber"     => $item->studentnumber ?? null,
                "user_type"          => $item->user_type,
                "is_enrolled"       => $this->student->is_enrolled ?? null,
                "is_active"         => $item->is_active,
                "is_in"             => "0",
                "timein"            => $item->student->level->time_in ?? "6:00:00",
                "timeout"           => $item->student->level->time_out ?? "18:00:00"

                ];
                Redis::set($item->rfid, json_encode($array_data));
            }
            
        }

        \Alert::success("Successful!")->flash();
        return "Success";
    }

    public function rfidlogs()
    {
        $announcements = Announcement::get();
        return view('rfidlogs', compact(['announcements']));
    }

    public function getRedisData()
    {
        $data = Rfid::with(['student.level'])->where("is_active","=",1)->get();
        $rfids = [];
        
        foreach($data as $item)
        {   
            if($item)
            {
                $array_data = [
                    "rfid"              => $item->rfid ?? null,
                    "studentnumber"     => $item->studentnumber ?? null,
                    "user_type"          => $item->user_type,
                    "is_enrolled"       => $this->student->is_enrolled ?? null,
                    "is_active"         => $item->is_active,
                    "is_in"             => "0",
                    "timein"            => $item->student->level->time_in ?? "6:00:00",
                    "timeout"           => $item->student->level->time_out ?? "18:00:00"
                ];
                array_push($rfids, $array_data);
            }
        }
        return ["rfids" => $rfids];
    }

}
