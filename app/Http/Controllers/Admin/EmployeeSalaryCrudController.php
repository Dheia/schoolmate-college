<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EmployeeSalaryRequest as StoreRequest;
use App\Http\Requests\EmployeeSalaryRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use App\Models\EmployeeSalary;

/**
 * Class EmployeeSalaryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EmployeeSalaryCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\EmployeeSalary');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/employee-salary-management');
        $this->crud->setEntityNameStrings('Salaries', 'Salaries');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->allowAccess('show');
        $this->crud->addButtonFromView('top', 'Print', 'employeeSalary.print', 'end');

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();


        // add asterisk for fields that are required in EmployeeSalaryRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([
            'name' => 'employee_id',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'studentNumber'
            ]
        ]);

        $this->crud->addField([
            'name' => 'searchInput',
            'type' => 'searchEmployee',
            'label' => 'Search',
            'attributes' => [
                'id' => 'searchInput',
                'placeholder' => 'Search For Name or ID Number (ex. 1100224)'
            ]
        ]);

        $this->crud->addField([
            'name'  => 'salary',
            'type'  => 'number',
            'label' => 'Salary',
            'attributes' => [
                'step' => 'any'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-xs-12'
            ]
        ]);

        $this->crud->addField([
            'name'  => 'salary_type',
            'type'  => 'select_from_array',
            'label' => 'Salary On 15th',
            'options' => [
                'every_30th' => 'Every 30th', 
                'every_15th_and_30th' => 'Every 15th and 30th', 
                'every_day'=> 'Every day'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-xs-12'
            ]
        ]);

        $this->crud->addField([
            'name'  => 'admin_pay',
            'type'  => 'number',
            'label' => 'Admin Pay',
            'default' => '0.00',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-xs-12'
            ]
        ]);

        $this->crud->addField([
            'name'  => 'other_pay',
            'type'  => 'number',
            'label' => 'Other Pay',
            'default' => '0.00',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-xs-12'
            ]
        ]);

        $this->crud->addField([
            'name'  => 'description',
            'type'  => 'text',
            'label' => 'Description',
            'wrapperAttributes' => [
                'class' => 'form-group col-xs-12'
            ]
        ]);

        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */
        $this->crud->setColumnDetails('employee_id', [
            'label'  => 'Employee No.',
            'type'   => 'text',
            'name'   => 'employee_id',
            'prefix' => 'WIS-'
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
            'name'  => 'salary',
            'type'  => 'text',
            'label' => 'Salary',
        ]);

        $this->crud->addColumn([
            'name'  => 'salary_type',
            'type'  => 'select_from_array',
            'label' => 'Salary Type',
            'options' => [
                'every_30th' => 'Every 30th', 
                'every_15th_and_30th' => 'Every 15th and 30th', 
                'every_day'=> 'Every day'
            ]
        ]);

        $this->crud->addColumn([
            'name'  => 'admin_pay',
            'type'  => 'number',
            'label' => 'Admin Pay',
        ]);

        $this->crud->addColumn([
            'name'  => 'other_pay',
            'type'  => 'number',
            'label' => 'Other Pay',
        ]);

        $this->crud->addColumn([
            'name'  => 'description',
            'type'  => 'textarea',
            'label' => 'Description',
        ]);

        /*
        |--------------------------------------------------------------------------
        | FILTERS
        |--------------------------------------------------------------------------
        */
        $this->crud->addFilter([ // select2 filter
            'name' => 'salary_type',
            'type' => 'select2',
            'label'=> 'Salary Type'
          ], function() {
            $salary_type = array(
                [
                    'id' => 'every_30th',
                    'name' => 'Every 30th',
                ],
                [
                    'id' => 'every_15th_and_30th',
                    'name' => 'Every 15th and 30th',
                ],
                [
                    'id' => 'every_day',
                    'name' => 'Every day',
                ]
            );
            return collect($salary_type)->pluck('name', 'id')->toArray();
        }, function($value) { // if the filter is active
            $this->crud->addClause('where', 'salary_type', $value);
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

    public function destroy ($id)
    {
        $model = $this->crud->model::findOrFail($id);
        $model->delete();
    }
    public function print(){

        $salarys = EmployeeSalary::all();

        $schoollogo      = config('settings.schoollogo') ? (string)\Image::make(config('settings.schoollogo'))->encode('data-url') : null;
        $schoolmate_logo = (string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url');

        if(count($salarys) == 0) {
            \Alert::warning('Employee Salary is empty.')->flash();
            return redirect()->back();
        }
    return view('reports.employeeSalary.generateReport',compact('salarys','schoollogo','schoolmate_logo'));
    }
}
