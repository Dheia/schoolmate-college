<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GoalRequest as StoreRequest;
use App\Http\Requests\GoalRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class GoalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GoalCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Goal');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/my-goal');
        $this->crud->setEntityNameStrings('goal', 'goals');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        if(!backpack_auth()->user()->employee) {
             abort(403, 'Your User Account Is Not Yet Tag As Employee');
        }

        $this->crud->addClause('where', 'user_type', 'App\Models\Employee');
        $this->crud->addClause('where', 'user_id', backpack_auth()->user()->employee->id);

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        $this->crud->removeFields(['user_id', 'user_type']);
        $this->crud->removeColumns(['user_id', 'user_type']);

        // add asterisk for fields that are required in GoalRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        $request->request->set('user_id', backpack_auth()->user()->employee->id);
        $request->request->set('user_type', 'App\Models\Employee');

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
