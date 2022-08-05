<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ScheduleTaggingRequest as StoreRequest;
use App\Http\Requests\ScheduleTaggingRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ScheduleTaggingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ScheduleTaggingCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ScheduleTagging');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/schedule-tagging');
        $this->crud->setEntityNameStrings('Schedule Tagging', 'Schedule Tags');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in ScheduleTaggingRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        /*
        |--------------------------------------------------------------------------
        | Add Column
        |--------------------------------------------------------------------------
        */
        


        $this->crud->addColumn([
            'label' => 'Employee ID',
            'name' => 'employee_id',
            'type' => 'select',
            'model' => 'App\Models\Employee',
            'attribute' => 'employee_id',
            'entity' => 'employee'
        ])->afterColumn('id');

        $this->crud->addColumn([
            'label' => 'Employee Name',
            'name' => 'employee_name',
            'type' => 'text'
        ])->afterColumn('employee_id');

        $this->crud->addColumn([
            'label' => 'Schedule Template',
            'name' => 'schedule_template_id',
            'type' => 'select',
            'entity' => 'scheduleTemplate',
            'attribute' => 'name',
            'model' => 'App\Models\ScheduleTemplate',
        ]);

         $this->crud->addColumn([
            'label' => 'Deduction Type',
            'name' => 'deduction_type',
            'type' => 'text',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Add Field
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([
            'name' => 'searchInput',
            'type' => 'searchEmployee',
            'label' => 'Search',
            'attributes' => [
                'id' => 'searchInput',
                'placeholder' => 'Search For Name or ID Number (ex. 1100224)'
            ]
        ])->beforeField('employee_id');

        $this->crud->addField([
            'name' => 'employee_id',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'studentNumber'
            ]
        ]);

         $this->crud->addField([
            'label' => 'Schedule Template',
            'name' => 'schedule_template_id',
            'type' => 'select2',
            'entity' => 'scheduleTemplate',
            'attribute' => 'name',
            'model' => 'App\Models\ScheduleTemplate',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

         $this->crud->addField([
            'label' => 'Deduction Type',
            'name' => 'deduction_type',
            'type' => 'radio',
            'options' => ['Based On Schedule' => 'Based On Schedule', 'Based On Hours Per Week' => 'Based On Hours Per Week'],
            'inline' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
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
