<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EmployeeMandatoryPhilHealthRequest as StoreRequest;
use App\Http\Requests\EmployeeMandatoryPhilHealthRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class EmployeeMandatoryPhilHealthCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EmployeeMandatoryPhilHealthCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\EmployeeMandatoryPhilHealth');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/employee-mandatory-phil-health');
        $this->crud->setEntityNameStrings('PhilHealth', 'Employee PhilHealth');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in EmployeeMandatoryPhilHealthRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->addColumn([
            'label' => 'Monthly Basic Salary Rate',
            'type' => 'text',
            'name' => 'monthly_basic_salary_rate',
            'suffix' => '%'
        ]);

        $this->crud->addColumn([
            'label' => 'Active',
            'type' => 'check',
            'name' => 'active',
        ]);

        $this->crud->removeAllFields();

        $this->crud->addField([
            'label' => 'Monthly Basic Salary Rate (%)',
            'name' => 'monthly_basic_salary_rate',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
       
       $this->crud->addField([
            'label' => 'Computation Salary Table',
            'type' => 'table',
            'name' => 'computation_salary_table',
            'entity_singular' => 'option',
            'columns' => [
                'salary_min' => 'Salary Min',
                'salary_max' => 'Salary Max',
                'monthly_premium' => 'Monthly Premium',
                'employee_share' => 'Employee Share',
                'employer_share' => 'Employer Share',
            ],   
            'max' => 2, // maximum rows allowed in the table
            'min' => 2, // minimum rows allowed in the table
       ]);

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
