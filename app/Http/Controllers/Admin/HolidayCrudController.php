<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\HolidayRequest as StoreRequest;
use App\Http\Requests\HolidayRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\SchoolYear;

/**
 * Class HolidayCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class HolidayCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Holiday');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/holiday');
        $this->crud->setEntityNameStrings('holiday', 'holidays');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in HolidayRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');


        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([
            'label'     => 'Name',
            'type'      => 'text',
            'name'      => 'name',
            'wrapperAttributes' => [
                'class' => 'col-md-12 form-group'
            ]
        ]); 

        $this->crud->addField([
            'label'     => 'Description',
            'type'      => 'textarea',
            'name'      => 'description',
            'wrapperAttributes' => [
                'class' => 'col-md-12 form-group'
            ]
        ]); 

        if($this->crud->getActionMethod() === 'create')
        {
            $this->crud->addField([
                'label'     => 'School Year',
                'type'      => 'select_from_array',
                'name'      => 'school_year_id',
                'options'   => SchoolYear::active()->pluck('schoolYear', 'id'),
                'wrapperAttributes' => [
                    'class' => 'col-md-6 form-group'
                ]
            ]); 
        } else 
        {
            $this->crud->addField([
                'label'     => 'School Year',
                'type'      => 'select_from_array',
                'name'      => 'school_year_id',
                'options'   => SchoolYear::pluck('schoolYear', 'id'),
                'wrapperAttributes' => [
                    'class' => 'col-md-6 form-group'
                ]
            ]); 
        }

        $this->crud->addField([
            'label'     => 'Date',
            'type'      => 'date',
            'name'      => 'date',
            'wrapperAttributes' => [
                'class' => 'col-md-6 form-group'
            ]
        ]);

        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
            'label'     => 'Name',
            'type'      => 'text',
            'name'      => 'name'
        ]);
        
        $this->crud->addColumn([
            'label'     => 'Description',
            'type'      => 'textarea',
            'name'      => 'description'
        ]);

        $this->crud->addColumn([
            'label'     => 'School Year',
            'type'      => 'select',
            'name'      => 'school_year_id',
            'entity'    => 'schoolYear', 
            'attribute' => 'schoolYear',
            'model'     => 'App\Models\SchoolYear',
        ]); 

        $this->crud->addColumn([
            'label'     => 'Date',
            'type'      => 'date',
            'name'      => 'date',
            'format'    => 'MMMM DD, YYYY',
            'wrapperAttributes' => [
                'class' => 'col-md-6 form-group'
            ]
        ]);

        $this->crud->addFilter([ // select2 filter
            'name' => 'schoolyear_id',
            'type' => 'select2',
            'label'=> 'School Year'
        ], function() {
            return SchoolYear::all()->pluck('schoolYear', 'id')->toArray();
        }, function($value) { // if the filter is active
            $this->crud->addClause('where', 'school_year_id', $value);
        });
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
