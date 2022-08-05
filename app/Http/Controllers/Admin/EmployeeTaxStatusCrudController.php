<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EmployeeTaxStatusRequest as StoreRequest;
use App\Http\Requests\EmployeeTaxStatusRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class EmployeeTaxStatusCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EmployeeTaxStatusCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\EmployeeTaxStatus');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/employee-tax-status');
        $this->crud->setEntityNameStrings('Tax Status', 'Employee Tax Statuses');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in EmployeeTaxStatusRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->allowAccess('show');

        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([
            'label'         => 'Employee ID',
            'name'          => 'employee_id',
            'type'          => 'hidden',
            'attributes'    => [ 'id' => 'studentNumber' ]
        ]);

        $this->crud->addField([
            'name' => 'searchEmployee',
            'type' => 'searchEmployee',
            'label' => '',
            'attributes' => [
                'id' => 'searchInput',
                'placeholder' => 'Search For Name or ID Number (ex. 1100224)',
            ],
        ]);

        $this->crud->addField([
            'label'         => 'Marital Status',
            'name'          => 'marital_status',
            'type'          => 'togglewithheader',
            'header'        => null,
            'default'       => 'SINGLE',
            'options'       => [
                'SINGLE'    => 'Single',
                'MARRIED'   => 'Married',
            ],
            'hide_when'         => [ 'SINGLE' => ['monthly_gross_income_partner'] ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
            'inline'        => true,
        ]);

        $this->crud->addField([
            'label'         => 'Monthly Gross Income Of Your Partner',
            'name'          => 'monthly_gross_income_partner',
            'type'          => 'text',
            'default'       => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
        ]);

        $this->crud->addField([
            'label'         => 'Dependents',
            'name'          => 'dependents',
            'type'          => 'radio',
            'options'       => [0, 1, 2, 3, 4],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12 clear'
            ],
            'inline'        => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'label'         => 'Employee ID',
            'name'          => 'employee_id',
            'type'          => 'text',
            'prefix'        => env('SCHOOL_NAME') . ' - '
        ]);

        $this->crud->addColumn([
            // 1-n relationship
            'label' => "Full Name", // Table column heading
            'type' => "select",
            'name' => 'full_name', // the column that contains the ID of that connected entity;
            'entity' => 'employee', // the method that defines the relationship in your Model
            'attribute' => "full_name", // foreign key attribute that is shown to user
            'model' => "App\Models\Employee", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('employee', function ($q) use ($column, $searchTerm) {
                    $q->where('firstname', 'like', '%'.$searchTerm.'%')
                      ->orWhere('lastname', 'like', '%'.$searchTerm.'%')
                      ->orWhere('employee_id', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);

        $this->crud->addColumn([
            'label'         => 'Marital Status',
            'name'          => 'marital_status',
            'type'          => 'text',
        ]);

        $this->crud->addColumn([
            'label'         => 'Dependents',
            'name'          => 'dependents',
            'type'          => 'text',
        ]);

        if($this->crud->getActionMethod() == 'show')
        {
            $this->crud->addColumn([
                'label' => 'Partner Monthly Gross Income',
                'name'  => 'monthly_gross_income_partner',
                'type'  => 'text',
            ]);
        }
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
