<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AuthenticationLogRequest as StoreRequest;
use App\Http\Requests\AuthenticationLogRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class AuthenticationLogCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AuthenticationLogCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\AuthenticationLog');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/authentication-log');
        $this->crud->setEntityNameStrings('Authentication Log', 'Authentication Logs');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in AuthenticationLogRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->denyAccess(['create', 'update', 'delete']);

        // $this->crud->addColumn([
        //     'label' => 'User',
        //     'type' => 'select',
        //     'name' => 'user_id',
        //     'entity' => 'user',
        //     'attribute' => 'full_name',
        //     'model' => 'App\Models\User'
        // ]);
        $this->crud->addColumn([
            'label' => 'User',
            'type' => 'text',
            'name' => 'full_name'
        ]);

        $this->crud->addColumn([
            'label' => 'Login Type',
            'type' => 'text',
            'name' => 'login_type'
        ]);

        $this->crud->addColumn([
            'label' => 'Login',
            'type' => 'date',
            'name' => 'login_at',
            'format' => 'hh:mm A'
        ]);

        $this->crud->addColumn([
            'label' => 'Logout',
            'type' => 'date',
            'name' => 'logout_at',
            'format' => 'hh:mm A'
        ]);

        $this->crud->addColumn([
            'label' => 'Date',
            'type' => 'date',
            'name' => 'created_at',
            'format' => 'MMMM DD, YYYY'
        ]);


        if(!backpack_auth()->user()->hasRole('Administrator')) {
            $this->crud->denyAccess('details_row');
            $this->crud->addClause('where', 'user_id', backpack_auth()->user()->id);
            $this->crud->addClause('where', 'user_type', 'App\User');
        } else {
            $this->crud->addFilter([ 
              'type' => 'simple',
              'name' => 'show_my_logs_only',
              'label'=> 'Show My Logs Only'
            ],
            false, // the simple filter has no values, just the "Draft" label specified above
            function() { // if the filter is active (the GET parameter "draft" exits)
                $this->crud->addClause('where', 'user_id', backpack_auth()->user()->id);
                $this->crud->addClause('where', 'user_type', 'App\User');
            });

            $this->crud->addFilter([
              'name'  => 'user_type',
              'type'  => 'dropdown',
              'label' => 'User Type'
            ], [
              1 => 'Admin',
              2 => 'Student',
              3 => 'Parent'
            ], function($value) { // if the filter is active
                if($value == 1) {
                    $this->crud->addClause('where', 'user_type', 'App\User');
                } else if($value == 2) {
                    $this->crud->addClause('where', 'user_type', 'App\Models\Student');
                } else if($value == 3) {
                    $this->crud->addClause('where', 'user_type', 'App\Models\ParentUser');
                }
            });

            // daterange filter
            $this->crud->addFilter([
              'type'  => 'date_range',
              'name'  => 'from_to',
              'label' => 'Date range'
            ],
            false,
            function ($value) { // if the filter is active, apply these constraints
              $dates = json_decode($value);
              $this->crud->addClause('where', 'created_at', '>=', $dates->from);
              $this->crud->addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');
            });
        }

        $this->crud->orderBy('created_at', 'DESC');
        $this->crud->enableExportButtons();
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
