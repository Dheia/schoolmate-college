<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EmploymentStatusHistoryRequest as StoreRequest;
use App\Http\Requests\EmploymentStatusHistoryRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class EmploymentStatusHistoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EmploymentStatusHistoryCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\EmploymentStatusHistory');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/employment-status-history');
        $this->crud->setEntityNameStrings('Employment Status History', 'Employment Status Histories');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in EmploymentStatusHistoryRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->removeAllButtons();
        $this->crud->denyAccess(['create', 'edit', 'update', 'delete']);

        $this->crud->addColumn([
            'label' => 'Employee',
            'type' => 'select',
            'name' => 'employee_id',
            'entity' => 'employee',
            'attribute' => 'full_name',
            'model' => 'App\Models\Employee'
        ]);

        $this->crud->addColumn([
            'label' => 'Employee Status',
            'type' => 'select',
            'name' => 'employment_status_id',
            'entity' => 'employmentStatus',
            'attribute' => 'name',
            'model' => 'App\Models\EmploymentStatus'
        ]);

        $this->crud->addColumn([
            'label' => 'Updated By',
            'type' => 'select',
            'name' => 'updated_by',
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
}
