<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ScheduleTemplateRequest as StoreRequest;
use App\Http\Requests\ScheduleTemplateRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class ScheduleTemplateCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ScheduleTemplateCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ScheduleTemplate');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/schedule-template');
        $this->crud->setEntityNameStrings('Schedule Template', 'schedule Templates');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in ScheduleTemplateRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');


        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('schedule_template');

        $this->crud->removeAllFields();
        $this->crud->setColumns(['name']);
        $this->crud->addColumn([
            'label'     => 'Total Weekly Hours',
            'type'      => 'number',
            'name'      => 'total_weekly_hours',
            'suffix'    => ' Hours'
        ]);

        $this->crud->addField([
            'label' => 'Template Name*',
            'type'  => 'text',
            'name'  => 'name',
            'wrapperAttributes'  => [
                'class' => 'col-md-6 form-group'
            ]
        ]);

        $this->crud->addField([  
            'label' => 'Hours Per Week*',
            'name'  => 'total_weekly_hours',
            'type'  => 'number',
            'wrapperAttributes'  => [
                'class' => 'col-md-6 form-group'
            ]    
        ]);

        $this->crud->addField([  
            'label' => 'Schedule*',
            'name'  => 'schedule',
            'type'  => 'scheduleTemplate.table_schedule'    
        ]);
    }

    public function appliedDays ()
    {
        return $this->crud->model::all();
    }

    public function showDetailsRow ($id)
    {
        $unique_id = (int)$id;
        $crud      = $this->crud;
        $entry     = $this->crud->getEntries();
        
        return view('scheduleTemplate.details_row', compact('entry', 'crud', 'unique_id'));

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
