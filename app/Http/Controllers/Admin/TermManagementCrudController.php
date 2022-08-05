<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TermManagementRequest as StoreRequest;
use App\Http\Requests\TermManagementRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

// MODELS
use App\Models\Department;

/**
 * Class TermManagementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TermManagementCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\TermManagement');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/term-management');
        $this->crud->setEntityNameStrings('Term', 'Terms');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in TermManagementRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->removeField('level_id');
        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
            'label' => 'Department',
            'type'  => 'select',
            'name'  => 'department_id',
            'entity' => 'department',
            'attribute' => 'name',
            'model' => 'App\Models\Department'
        ]);

        $this->crud->addColumn([
            'label' => 'Type',
            'type'  => 'select_from_array',
            'name'  => 'type',
            'options' => ['FullTerm' => 'Full Term', 'Semester' => 'Semester']
        ]);

        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([
            'type' => 'term.term_script',
            'name' => 'termScript'
        ]);

        $this->crud->addField([
            'label' => 'Department',
            'type'  => 'select2_from_array',
            'name'  => 'department_id',
            'options' => Department::active()->pluck('name', 'id'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
        ]);

        // $this->crud->addField([
        //     'label' => 'Level',
        //     'type'  => 'select_from_array',
        //     'options'   => [],
        //     'name'  => 'level_id',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-6'
        //     ]
        // ]);

        // $this->crud->addField([
        //     'label' => 'Name',
        //     'type'  => 'text',
        //     'name'  => 'name',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-6'
        //     ]
        // ]);

        $this->crud->addField([
            'label' => 'Type',
            'type'  => 'select_from_array',
            'name'  => 'type',
            'options' => ['FullTerm' => 'Full Term', 'Semester' => 'Semester'],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12',
                'id'    =>  'term_type'
            ]
        ]);

        $this->crud->addField([
            'label' => 'No of Term',
            'name' => 'no_of_term',
            'type' => 'number',
            'wrapperAttributes' => [
                'class' =>  'form-group col-md-12',
                'id'    =>  'no_of_term',
                'style' =>  'display: none;'
            ]
        ]);

        $this->crud->addField([
            'label' => '',
            'type' => 'department.script',
            'name' => 'department_script'
        ]);

        $this->crud->addClause('whereIn', 'department_id', Department::active()->get()->pluck('id'));
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $isDepartmentExists = $this->crud->model::where('department_id', $request->department_id)->exists();
        if($isDepartmentExists) {
            \Alert::warning("This Department has already been added")->flash();
            return redirect()->back();
        }

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
