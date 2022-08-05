<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EmployeeAttendanceLogRequest as StoreRequest;
use App\Http\Requests\EmployeeAttendanceLogRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class EmployeeAttendanceLogCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EmployeeAttendanceLogCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | Has Role Of `Employee`
        |--------------------------------------------------------------------------
        */
        if(!backpack_user()->hasRole('Employee')) { abort(403); }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\EmployeeAttendanceLog');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/attendance-log');
        $this->crud->setEntityNameStrings('Turnstile Tap Logs', 'Turnstile Tap Logs');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
        $this->crud->denyAccess(['create', 'update', 'delete', 'reorder']);
        // add asterisk for fields that are required in EmployeeAttendanceLogRequest
        // $this->crud->setRequiredFields(StoreRequest::class, 'create');
        // $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $employee = backpack_user()->employee()->first();

        $this->crud->addColumn([
            'label' => "Date",
            'type'  => "text",
            'name'  => "entry_date"
        ])->afterColumn('rfid');

        $this->crud->addColumn([
            'label' => "In",
            'type'  => "check",
            'name'  => "is_logged_in"
        ]);


        $this->crud->removeColumn(['rfid']);
        
        $this->crud->orderBy('created_at', 'desc');

        $employee ? $this->crud->addClause('where', 'rfid', '=', $employee->rfid) : $this->crud->addClause('where', 'rfid', '=', null);


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
