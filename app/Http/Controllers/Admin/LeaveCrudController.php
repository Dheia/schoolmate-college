<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LeaveRequest as StoreRequest;
use App\Http\Requests\LeaveRequest as UpdateRequest;

/**
 * Class LeaveCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LeaveCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Leave');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/leave');
        $this->crud->setEntityNameStrings('leave', 'leaves');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in LeaveRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->allowAccess('clone');
        // $this->crud->removeColumns(['updated_by']);

        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([
            'label' => 'Name',
            'type'  => 'text',
            'name'  => 'name', 
        ]);

        $this->crud->addField([
            'label' => 'Convertable To Cash',
            'type'  => 'checkbox',
            'name'  => 'convertable_to_cash', 
        ]);

        $this->crud->addField([
            'name' => 'updated_by',
            'type' => 'hidden',
            'value' => backpack_auth()->user()->id
        ]);

        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
            'label' => 'Name',
            'type'  => 'text',
            'name'  => 'name', 
        ]);

        $this->crud->addColumn([
            'label' => 'Convertable To Cash',
            'type'  => 'check',
            'name'  => 'convertable_to_cash', 
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

    public function destroy ($id)
    {
        $model = $this->crud->model::findOrFail($id);
        $model->delete();
    }

}
