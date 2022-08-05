<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TaxCodeRequest as StoreRequest;
use App\Http\Requests\TaxCodeRequest as UpdateRequest;

class TaxCodeCrudController extends CrudController
{
    public function setup()
    {

        $user = \Auth::user();
        $permissions = collect($user->getAllPermissions());

        $plucked = $permissions->pluck('name');
        $this->allowed_method_access = $plucked->all();

        $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);
        $this->crud->allowAccess($this->allowed_method_access);
        
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\TaxCode');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/taxcode');
        $this->crud->setEntityNameStrings('taxcode', 'tax_codes');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();
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
