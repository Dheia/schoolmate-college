<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SchoolCalendarRequest as StoreRequest;
use App\Http\Requests\SchoolCalendarRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use Carbon\Carbon;
use App\Models\SchoolCalendar;

/**
 * Class SchoolCalendarCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SchoolCalendarCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SchoolCalendar');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/school-calendar');
        $this->crud->setEntityNameStrings('school calendar', 'school calendars');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
           'label' => "Title",
           'type' => "text",
           'name' => 'title'
        ]);

        $this->crud->addColumn([
           'label' => "Description",
           'type' => "markdown",
           'name' => 'description'
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

        $this->crud->addField([
            'name' => 'title',
            'type' => 'text',
            'label' => 'Title'
        ]);

        $this->crud->addField([
            'name' => 'description',
            'type' => 'textarea',
            'label' => 'Description'
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

        // add asterisk for fields that are required in SchoolCalendarRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    
        $this->crud->addButtonFromView('top', 'Print', 'schoolCalendar_print', 'end');

    }

    public function store(StoreRequest $request)
    {
        $request->request->set('created_by', backpack_auth()->user()->employee_id);

        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $request->request->set('updated_by', backpack_auth()->user()->employee_id);
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
    
    public function print(){
        $school_calendars = SchoolCalendar::all();

        $schoollogo      = config('settings.schoollogo') ? (string)\Image::make(config('settings.schoollogo'))->encode('data-url') : null;
        $schoolmate_logo = (string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url');

        if(count($school_calendars) == 0) {
            \Alert::warning('School Calendar is Empty.')->flash();
            return redirect()->back();
        }
        
        return view('schoolCalendar.generateReport',compact('school_calendars','schoollogo','schoolmate_logo'));
    }
}
