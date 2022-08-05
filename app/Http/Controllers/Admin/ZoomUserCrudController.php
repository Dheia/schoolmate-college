<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ZoomUserRequest as StoreRequest;
use App\Http\Requests\ZoomUserRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\ZoomUser;

/**
 * Class ZoomUserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ZoomUserCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ZoomUser');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/zoom-user');
        $this->crud->setEntityNameStrings('Zoom User', 'zoom users');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        // $this->crud->denyAccess('create');
        // $this->crud->denyAccess('edit');
        if(backpack_auth()->user()->email != 'dev@schoolmate-online.net'){
            abort(403);
        }

        $zoom_users = ZoomUser::count();
        if($zoom_users >= env('ZOOM_MAX_USER')) {
            $this->crud->denyAccess('create');
        }

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        $this->crud->removeFields(['type', 'active']);

        // add asterisk for fields that are required in ZoomUserRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        $zoom_users = ZoomUser::count();
        if($zoom_users >= env('ZOOM_MAX_USER')) {
            \Alert::warning("Maximum Zoom User has been reached!")->flash();
            return redirect()->back();
        }

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
