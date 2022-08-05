<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\StudentSmsTaggingRequest as StoreRequest;
use App\Http\Requests\StudentSmsTaggingRequest as UpdateRequest;

use Illuminate\Http\Request;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Str;

use App\Models\Employee;

use App\Models\StudentSmsTagging as SMS;
/**
 * Class StudentSmsTaggingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StudentSmsTaggingCrudController extends CrudController
{



    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\StudentSmsTagging');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/sms');
        $this->crud->setEntityNameStrings('Register', 'SMS Registration');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in StudentSmsTaggingRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        // $this->crud->denyAccess(['create']);
        $this->crud->removeColumns(['user_type']);
        $this->crud->removeFields(['position_type']);



        /*
        |--------------------------------------------------------------------------
        | ADD COLUMNS
        |--------------------------------------------------------------------------
        */


        $this->crud->addColumn([
            'name' => 'studentnumber',
            'type' => 'text',
            'label' => 'ID No.',
            'prefix' => \Setting::get('schoolabbr') . '-',
        ]);

        $this->crud->addColumn([
            'label' => 'Full Name',
            'type' => 'text',
            'name' => 'full_name',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('students', function ($q) use ($column, $searchTerm) {
                    $q->where('firstname', 'like', '%'.$searchTerm.'%')
                        ->orWhere('lastname', 'like', '%'.$searchTerm.'%')
                        ->orWhere('studentnumber', 'like', '%'.$searchTerm.'%');
                });
                $query->orWhereHas('employees', function ($q) use ($column, $searchTerm) {
                    $q->where('firstname', 'like', '%'.$searchTerm.'%')
                        ->orWhere('lastname', 'like', '%'.$searchTerm.'%')
                        ->orWhere('employee_id', 'like', '%'.$searchTerm.'%');
                });
             
            },
        ])->afterColumn('studentnumber');

        $this->crud->addColumn([
            'label' => "User Type",
            'name' => 'user_type_title_case',
            'type' => 'text',
        ])->afterColumn('full_name');

        $this->crud->addColumn([
            'type'  => 'check',
            'name'  => 'is_registered'
        ]);



        /*
        |--------------------------------------------------------------------------
        | ADD FIELDS
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([
            'label' => '',
            'type' => 'search_student_or_employee',
            'name' => 'search_student_or_employee'
        ])->beforeField('studentnumber'); 


        $student_or_employee = isset($_GET['searchFor']) ? $_GET['searchFor'] : 'student';
        // str_contains($student_or_employee, ['student', 'employee']) ? '' : $student_or_employee = 'student';

        if($student_or_employee === "student") {
            $this->crud->addField([
                'name' => 'user_type',
                'type' => 'hidden',
                'value' => 'student',
            ]);
        } else {
            $this->crud->addField([
                'name' => 'user_type',
                'type' => 'hidden',
                'value' => 'employee',
            ]);
        }

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

        

        $this->crud->removeFields(['access_token','is_registered', 'total_sms', 'total_paid']);
        $this->crud->addButtonFromView('top', 'set_sms_group', 'studentSmsTagging.setSmsGroup', 'end');


        $this->crud->removeColumns(['access_token','total_sms','total_paid']);
    }


    public function store(StoreRequest $request)
    {
        // ADD CONTACT TO SMART MESSAGING
        $smartJWT   = new SmartJwtCredentialCrudController;
        $subscriber_id = $smartJWT->addSubscriber($request);

        // IF EMPLOYEE TYPE GET THE POSITION
        $user_type = 'student';
        $position_type = null;
        if($request->user_type === 'employee') {
            $employee = Employee::where('employee_id', $request->studentnumber)->first();
            if($employee !== null) {
                $position_type = $employee->type;
                $user_type = 'employee';
            }
        }

        if(isset($subscriber_id['error'])) {

            // If subscriber exist in Suite it will get the subscriber id in the existing record.
            $msg = isset($subscriber_id['data']->data) ? $subscriber_id['data']->data : $subscriber_id['data']->getData();

            if($msg->statusCode > 202) {

                if($msg->statusText === "Conflict") {
                    $existing_number = SMS::where('subscriber_number', $request->subscriber_number)->first();

                    if($existing_number !== null) {
                        $subscriber_id = $existing_number->subscriber_id;

                        if($subscriber_id == null) {
                            \Alert::warning("Error: subscriber id is null")->flash();
                            return redirect()->back()->withInput();
                        }

                        $this->data['subscriber_id'] = $subscriber_id;
                    } else {
                        \Alert::warning("No Existing Subscriber Number Found")->flash();
                        return redirect()->back()->withInput();
                    }
                } else {
                    \Alert::warning('Error: ' . $msg->errorDescription)->flash();
                    return redirect()->back()->withInput();
                }
            }
        }

        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);

        $this->crud->model::where([
                                'studentnumber'     => $request->studentnumber,
                                'subscriber_number' => $request->subscriber_number,
                            ])->update(['subscriber_id' => $subscriber_id, 'is_registered' => 1, 'position_type' => $position_type, 'user_type' => $user_type]);

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

    // public function destroy ($id)
    // {
    //     $model = $this->crud->model::findOrFail($id);
    //     $model->delete();
    // }
    
     public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        $model = $this->crud->model::findOrFail($id);
        $smartJWT = new SmartJwtCredentialCrudController;
        // dd($model->subscriber_id);
        $isDelete = $smartJWT->deleteSubscriber($model->subscriber_id);
        // dd($isDelete);
        // if(isset($subscriber_id['error'])) {
        //     // dd($subscriber_id);
        //     $msg = $subscriber_id['data']->data;
        //     \Alert::warning($msg->statusText . ' :' . " Duplicated contact record")->flash();
        //     return redirect()->back()->withInput();
        // }

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $model->delete();
    }

}
