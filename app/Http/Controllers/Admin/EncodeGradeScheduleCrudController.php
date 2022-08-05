<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EncodeGradeScheduleRequest as StoreRequest;
use App\Http\Requests\EncodeGradeScheduleRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\SchoolYear;
use App\Models\Department;
use App\Models\TermManagement;

use Carbon\Carbon;

/**
 * Class EncodeGradeScheduleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EncodeGradeScheduleCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\EncodeGradeSchedule');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/encode-grade-schedule');
        $this->crud->setEntityNameStrings('Grade Encoding Schedule', 'Grade Encoding Schedules');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->data['terms'] = TermManagement::all();

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
           // 1-n relationship
           'label' => "School Year", // Table column heading
           'type' => "select",
           'name' => 'school_year_id', // the column that contains the ID of that connected entity;
           'entity' => 'schoolYear', // the method that defines the relationship in your Model
           'attribute' => "schoolYear", // foreign key attribute that is shown to user
           'model' => "App\Models\SchoolYear", // foreign key model
        ]);

        $this->crud->addColumn([
           // 1-n relationship
           'label' => "Department", // Table column heading
           'type' => "select",
           'name' => 'department_id', // the column that contains the ID of that connected entity;
           'entity' => 'department', // the method that defines the relationship in your Model
           'attribute' => "name", // foreign key attribute that is shown to user
           'model' => "App\Models\Department", // foreign key model
        ]);

        $this->crud->addColumn([
           'label' => "Start",
           'type' => "date",
           'name' => 'start_at',
           'format' => 'MMMM DD, YYYY'
        ]);

        $this->crud->addColumn([
           'label' => "End",
           'type' => "date",
           'name' => 'end_at',
           'format' => 'MMMM DD, YYYY'
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */

        $this->crud->removeFields(['start_at', 'end_at']);

        $this->crud->addField([  // Select
           'label' => "School Year",
           'type' => 'select',
           'name' => 'school_year_id', // the db column for the foreign key
           'entity' => 'schoolYear', // the method that defines the relationship in your Model
           'attribute' => 'schoolYear', // foreign key attribute that is shown to user
           'model' => "App\Models\SchoolYear",
           // optional
           'options'   => (function ($query) {
                return $query->orderBy('sequence', 'ASC')->get();
            }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
           'wrapperAttributes' => [ 'class' => 'form-group col-md-4' ],
        ]);

        $this->crud->addField([  // Select
           'label' => "Department",
           'type' => 'select',
           'name' => 'department_id', // the db column for the foreign key
           'entity' => 'department', // the method that defines the relationship in your Model
           'attribute' => 'name', // foreign key attribute that is shown to user
           'model' => "App\Models\Department",
           // optional
           'options'   => (function ($query) {
                return $query->active()->get();
            }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
           'wrapperAttributes' => [ 'class' => 'form-group col-md-4' ],
        ]);

        $this->crud->addField([
            'name' => 'term_type',
            'type' => 'select_from_array',
            'label' => 'Term Type',
            'options' => [],
            'wrapperAttributes' => [ 'class' => 'form-group col-md-4' ],
        ]);

        $this->crud->addField([
            'name' => 'event_date_range', // a unique name for this field
            'start_name' => 'start_at', // the db column that holds the start_date
            'end_name' => 'end_at', // the db column that holds the end_date
            'label' => 'Date Range',
            'type' => 'date_range',
            // OPTIONALS
            'start_default' => Carbon::now(), // default value for start_date
            'end_default' => Carbon::now(), // default value for end_date
            'date_range_options' => [ // options sent to daterangepicker.js
                'timePicker' => false,
                'locale' => ['format' => 'DD/MM/YYYY']
            ]
        ]);

        $this->crud->addField([
            'name' => 'script',
            'type' => 'encodeGradeSchedule.script',
            'label' => ''
        ]);

        // add asterisk for fields that are required in EncodeGradeScheduleRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // Check if existing row data
        $model = $this->crud->model::where([
            // 'teacher_id' => $request->teacher_id,
            'school_year_id' => $request->school_year_id,
            'department_id'  => $request->department_id,
            'term_type'      => $request->term_type
        ]);

        if($model->exists()) {
            \Alert::warning("This data is already exists.")->flash();
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
