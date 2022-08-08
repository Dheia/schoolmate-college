<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\BlockSectionRequest as StoreRequest;
use App\Http\Requests\BlockSectionRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

// Models
use App\Models\SchoolYear;
use App\Models\Department;

/**
 * Class BlockSectionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class BlockSectionCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\BlockSection');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/block-section');
        $this->crud->setEntityNameStrings('block section', 'block sections');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();
        self::addFields();

        // add asterisk for fields that are required in BlockSectionRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
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

    /*
    |--------------------------------------------------------------------------
    | COLUMNS
    |--------------------------------------------------------------------------
    */
    public function addColumns()
    {
        $this->crud->addColumn([
            'label' => 'Name',
            'type'  => 'text',
            'name'  => 'name'
        ]);
        
    }

    /*
    |--------------------------------------------------------------------------
    | FIELDS
    |--------------------------------------------------------------------------
    */
    public function addFields()
    {
        $this->crud->addField([
            'label' => 'Name',
            'type'  => 'text',
            'name'  => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Code',
            'type'  => 'text',
            'name'  => 'code',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'label' => 'School Year',
            'type' => 'select2',
            'name' => 'school_year_id',
            'entity' => 'schoolYear',
            'attribute' => 'schoolYear',
            'model' => 'App\Models\SchoolYear',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Department',
            'type' => 'select2_from_array',
            'name' => 'department_id',
            'options' => Department::active()->get()->pluck('name', 'id'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([   // Select
            'label' => "Level",
            'type' => 'select_from_array',
            'name' => 'level_id', 
            'options' => [],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([   // Select
            'label' => "Section",
            'type' => 'select_from_array',
            'name' => 'section_id', 
            'options' => [],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([   // Select
            'label' => "Term",
            'type' => 'select_from_array',
            'name' => 'term_type', 
            'options' => [],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Adviser',
            'type' => 'select2',
            'name' => 'employee_id',
            'entity' => 'adviser',
            'attribute' => 'full_name',
            'model' => 'App\Models\Employee',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
        ]);

        $this->crud->addField([   // Select
            'label' => "Script",
            'type' => 'blockSection.script',
            'name' => 'blockscript'
        ]);
    }
}