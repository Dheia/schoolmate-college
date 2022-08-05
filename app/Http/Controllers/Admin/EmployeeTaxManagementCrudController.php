<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EmployeeTaxManagementRequest as StoreRequest;
use App\Http\Requests\EmployeeTaxManagementRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class EmployeeTaxManagementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EmployeeTaxManagementCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\EmployeeTaxManagement');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/employee-tax-management');
        $this->crud->setEntityNameStrings('Tax', 'Tax Managements');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in EmployeeTaxManagementRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->allowAccess('show');

        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([
            'label' => 'Name',
            'type' => 'text',
            'name' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Description',
            'type' => 'text',
            'name' => 'description',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-8'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Annual Tax Table',
            'type' => 'table',
            'name' => 'tax_table',
            'columns' => [
                'salary_min'    => 'Min',
                'salary_max'    => 'Max',
                'basic_amount'  => 'Basic Amount',
                'rate'          => 'Additional Rate',
                'excess'        => 'Excess Over',
            ],
            'min' => 1
        ]);

        $this->crud->addField([
            'label' => 'Active',
            'type' => 'checkbox',
            'name' => 'active'
        ]);

        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
            'label' => 'Name',
            'type' => 'text',
            'name' => 'name'
        ]);

        $this->crud->addColumn([
            'label' => 'Description',
            'type' => 'textarea',
            'name' => 'description'
        ]);

        if($this->crud->getActionMethod() == 'show')
        {
            $this->crud->addColumn([
                'label' => 'Annual Tax Table',
                'type' => 'table',
                'name' => 'tax_table',
                'columns' => [
                    'salary_min'    => 'Min',
                    'salary_max'    => 'Max',
                    'basic_amount'  => 'Basic Amount',
                    'rate'          => 'Additional Rate',
                    'excess'        => 'Excess Over',
                ]
            ]);
        }
        
        $this->crud->addColumn([
            'label' => 'Active',
            'type' => 'check',
            'name' => 'active'
        ]);

        // $this->crud->removeFields(['active']);
        // $this->crud->removeColumns(['tax_table']);
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
