<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EmailLogRequest as StoreRequest;
use App\Http\Requests\EmailLogRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class EmailLogCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EmailLogCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\EmailLog');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/email-log');
        $this->crud->setEntityNameStrings('Email Log', 'Email Logs');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();
        $this->crud->addColumn([
           'name' => "subject",
           'label' => "Subject", // Table column heading
           'type' => "text"
        ]);

        $this->crud->addColumn([
           'name' => "description",
           'label' => "Description", // Table column heading
           'type' => "text"
        ]);

        $this->crud->addColumn([
           'name' => "receiver",
           'label' => "Receiver", // Table column heading
           'type' => "text"
        ]);

        $this->crud->addColumn([
           'name' => "status",
           'label' => "Status", // Table column heading
           'type' => "model_function",
           'function_name' => 'getStatusWithBadge'
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => 'Date',
            'type' => 'datetime',
            'format' => 'MMM DD, YYYY - hh:mm A'
        ]);

        $this->crud->addFilter([ // dropdown filter
          'name' => 'status',
          'type' => 'dropdown',
          'label'=> 'Status'
        ], [
          'success' => 'Success',
          'error'   => 'Error',
        ], function($value) { // if the filter is active
            $this->crud->addClause('where', 'status', $value);
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

        // add asterisk for fields that are required in EmailLogRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->orderBy('created_at', 'DESC');

        $this->crud->denyAccess(['create', 'update', 'delete']);
        $this->crud->allowAccess('show');
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->addColumn([
           'name' => "subject",
           'label' => "Subject", // Table column heading
           'type' => "markdown"
        ]);

        $this->crud->addColumn([
           'name' => "description",
           'label' => "Description", // Table column heading
           'type' => "markdown"
        ]);

        $this->crud->addColumn([
           'name' => "receiver",
           'label' => "Receiver", // Table column heading
           'type' => "markdown"
        ]);

        $this->crud->addColumn([
           'name' => "status",
           'label' => "Status", // Table column heading
           'type' => "model_function",
           'function_name' => 'getStatusWithBadge'
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => 'Date',
            'type' => 'datetime',
            'format' => 'MMM DD, YYYY - hh:mm A'
        ]);

        return $content;
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
