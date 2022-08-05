<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AdminSmsTaggingRequest as StoreRequest;
use App\Http\Requests\AdminSmsTaggingRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\AdminSmsTagging as SMS;
/**
 * Class AdminSmsTaggingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AdminSmsTaggingCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\AdminSmsTagging');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin-sms-register');
        $this->crud->setEntityNameStrings('Admin SMS Taggings', 'Admin SMS Taggings');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in AdminSmsTaggingRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->removeFields(['access_token','is_registered', 'subscriber_id', 'total_sms', 'total_paid']);

        // COLUMNS

        $this->crud->addColumn([
            'label' => 'Department',
            'type' => 'select',
            'name' => 'department_id',
            'entity' => 'department',
            'attribute' => 'name',
        ])->afterColumn('employee_id');

        $this->crud->addColumn([
            'label' => 'Registered',
            'type' => 'check',
            'name' => 'is_registered'
        ]);



        // FIELDS

        $this->crud->addField([
            'name' => 'searchInput',
            'type' => 'searchEmployee',
            'label' => 'Search',
            'attributes' => [
                'id' => 'searchInput',
                'placeholder' => 'Search For Name or ID Number (ex. 1100224)'
            ]
        ])->beforeField('department_id');

        $this->crud->addField([
            'label' => 'Employee',
            'type' => 'hidden',
            'name' => 'employee_id',
            'attributes' => [
                'id' => 'studentNumber'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Department',
            'type' => 'select2',
            'name' => 'department_id',
            'entity' => 'department',
            'attribute' => 'name',
            'model' => 'App\Models\NonAcademicDepartment',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Subscriber Number',
            'type' => 'number',
            'name' => 'subscriber_number',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here

        $request->request->set('user_type', 'employee');
        $request->request->set('studentnumber', $request->employee_id);

        // ADD CONTACT TO SMART MESSAGING
        $smartJWT       = new SmartJwtCredentialCrudController;
        $subscriber_id  = $smartJWT->addSubscriber($request);


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

        $redirect_location = parent::storeCrud($request);

        // your additional operations after save here
        $this->crud->model::where([ 'employee_id' => $request->employee_id, 'subscriber_number' => $request->subscriber_number])
                         ->update([ 'subscriber_id' => $subscriber_id, 'is_registered' => 1]);
        
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
}
