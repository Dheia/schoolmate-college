<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AssignTurnstileSmsReceiverRequest as StoreRequest;
use App\Http\Requests\AssignTurnstileSmsReceiverRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\StudentSmsTagging;

/**
 * Class AssignTurnstileSmsReceiverCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AssignTurnstileSmsReceiverCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\AssignTurnstileSmsReceiver');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/turnstile-sms-receipent');
        $this->crud->setEntityNameStrings('Receipent', 'Assign Turnstile Receipents');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in AssignTurnstileSmsReceiverRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->removeColumn('student_sms_tagging_id');


        $this->crud->addColumn([
            'label' => 'Department',
            'type' => 'select',
            'name' => 'department_id',
            'entity' => 'department',
            'attribute' => 'name',
            'model' => 'App\Models\NonAcademicDepartment',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addColumn([
            'label' => 'Employee',
            'type' => 'select_from_array',
            'name' => 'student_sms_tagging_id',
            'options' => StudentSmsTagging::where('user_type', 'employee')->get()->pluck('full_name_with_employee_id', 'id'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-8'
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
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Employee',
            'type' => 'select2_from_array',
            'name' => 'student_sms_tagging_id',
            'options' => StudentSmsTagging::where('user_type', 'employee')->get()->pluck('full_name_with_employee_id', 'id'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-8'
            ]
        ]);
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
}
