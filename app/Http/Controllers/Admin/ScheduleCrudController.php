<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ScheduleRequest as StoreRequest;
use App\Http\Requests\ScheduleRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ScheduleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ScheduleCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Schedule');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/schedule-tagging');
        $this->crud->setEntityNameStrings('schedule', 'schedules');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in ScheduleRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');



        // FIELDS
        $this->crud->addField([
            'name'  => 'employee_no',
            'type'  => 'hidden',
            'attributes'    => [
                'id' => 'studentNumber'
            ]
        ]);
        
        $this->crud->addField([
            'name' => 'searchEmployee',
            'type' => 'searchEmployee',
            'label' => '',
            'attributes' => [
                'id' => 'searchInput',
                'placeholder' => 'Search For Name or ID Number (ex. 1100224)',
            ],
        ])->beforeField('employee_no');

        $this->crud->addField([
            'label' => 'Schedule',
            'type'  => 'select2',
            'name'  => 'schedule_template_id',
            'entity' => 'scheduleTemplate',
            'attribute' => 'name',
            'models' => 'App\Models\ScheduleTemplate'
        ]);

        // COLUMNS
        $this->crud->addColumn([
            'name' => 'employee_no',
            'type' => 'text',
            'label' => 'Employee Number',
            'prefix' => 'WIS - '
        ]);

        $this->crud->addColumn([
            'name' => 'full_name',
            'type' => 'text',
            'label' => 'Full Name',
        ]);

        $this->crud->addColumn([
            'label' => 'Schedule',
            'type'  => 'select',
            'name'  => 'schedule_template_id',
            'entity' => 'scheduleTemplate',
            'attribute' => 'name',
            'models' => 'App\Models\ScheduleTemplate'
        ])->afterColumn('employee_no');
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
