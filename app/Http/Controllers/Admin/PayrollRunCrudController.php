<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PayrollRunRequest as StoreRequest;
use App\Http\Requests\PayrollRunRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class PayrollRunCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PayrollRunCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PayrollRun');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/payroll-run');
        $this->crud->setEntityNameStrings('Payroll Run', 'Payroll Runs');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in PayrollRunRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->allowAccess('view');
        $this->crud->data['dropdownButtons'] = [
            'view',
            'payrollRun.publish'
        ];
        $this->crud->addButtonFromView('line', 'More', 'dropdownButton', 'end');

        $this->crud->addColumn([
            'label' => 'Run By',
            'name'  => 'run_by',
            'type'  => 'select',
            'entity' => 'user',
            'attribute' => 'name',
            'model' => 'App\Models\User'
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

    public function displayPayroll ($id)
    {
        // return view();
    }

    public function publish ($id)
    {   
        $this->crud->model::find($id)->update(['status' => 'PUBLISHED']);
        \Alert::success('Successfully Published Payroll')->flash();
        return redirect()->back();
    }
}
