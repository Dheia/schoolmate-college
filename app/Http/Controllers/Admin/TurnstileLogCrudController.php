<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TurnstileLogRequest as StoreRequest;
use App\Http\Requests\TurnstileLogRequest as UpdateRequest;
use Carbon\Carbon;
use App\Models\TurnstileLog;
use App\Models\Student;

/**
 * Class TurnstileLogCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TurnstileLogCrudController extends CrudController
{   
    public function setup()
    {
    $this->crud->setDefaultPageLength(10);   
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\TurnstileLog');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/turnstile-log');
        $this->crud->setEntityNameStrings('turnstilelog', 'turnstile logs');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();        

        $student = $this->crud->model::with('rfid')->first();


        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->orderBy('created_at', 'DESC');

        $this->crud->enableAjaxTable();

        // Columns        
        $this->crud->addColumn([
            'name'  => 'student_name',
            'type'  => 'text',
            'label' => 'Full Name',
        ])->afterColumn("rfid");

        $this->crud->addColumn([
            'name'  => 'user_type',
            'type'  => 'text',
            'label' => 'User Type',
        ])->afterColumn("student_name");

        $this->crud->addColumn([
            'label'     => 'Time In',
            'type'      => 'datetime',
            'name'      => 'timein',
            'format'    => 'h:mm a'
        ]);

        $this->crud->addColumn([
            'label'     => 'Time Out',
            'type'      => 'datetime',
            'name'      => 'timeout',
            'format'    => 'h:mm a'
        ]);

        $this->crud->addColumn([
            'label'     => 'Date & Time',
            'type'      => 'datetime',
            'name'      => 'created_at',
            'format'    => 'MMMM DD YYYY, hh:mm a'
        ]);

        $this->crud->addColumn([
            'label' => 'Is Logged In',
            'type'  => 'check',
            'name'  => 'is_logged_in'
        ]);


        $this->crud->addFilter([ // dropdown filter
          'name' => 'daily',
          'type' => 'dropdown',
          'label'=> 'Daily'
        ], [
          1 => 'Today',
        ], function($value) { // if the filter is active
            if($value == 1) {
                $this->crud->addClause('whereDate', 'created_at', Carbon::today());
            }
        });

        $this->crud->addFilter([ // daterange filter
           'type' => 'date_range',
           'name' => 'from_to',
           'label'=> 'Date range'
        ],
        false,
        function($value) { // if the filter is active, apply these constraints
           $dates = json_decode($value);
           $this->crud->addClause('whereDate', 'created_at', '>=', Carbon::parse($dates->from . ' 00:00:00'));
           $this->crud->addClause('whereDate', 'created_at', '<=', Carbon::parse($dates->to . ' 23:59:59'));
        });

        $this->crud->denyAccess(['create','update','delete']);
        $this->crud->removeColumns(['rfid']);

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
