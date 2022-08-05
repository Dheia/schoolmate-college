<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\MyPayrollRequest as StoreRequest;
use App\Http\Requests\MyPayrollRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class MyPayrollCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MyPayrollCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\MyPayroll');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/my-payroll');
        $this->crud->setEntityNameStrings('My Payroll', 'My Payroll');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in MyPayrollRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->allowAccess('show');
        $this->crud->denyAccess(['create', 'update', 'delete']);
        $this->crud->removeColumns(['payroll', '']);

        if(backpack_user()->employee()->first()) {
            $this->crud->addClause('where', 'employee_id', '=', backpack_user()->employee()->first()->employee_id);
        }
        $this->crud->addClause('published');
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

    public function show($id)
    {
        $this->crud->hasAccessOrFail('show');
        $this->crud->setOperation('show');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // set columns from db
        $this->crud->setFromDb();

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.preview').' '.$this->crud->entity_name;

        // remove preview button from stack:line
        $this->crud->removeButton('show');
        $this->crud->removeButton('delete');

        // remove bulk actions colums
        $this->crud->removeColumns(['blank_first_column', 'bulk_actions']);

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('myPayroll.show', $this->data);
    }
}
