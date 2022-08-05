<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EmployeeMandatorySSSRequest as StoreRequest;
use App\Http\Requests\EmployeeMandatorySSSRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class EmployeeMandatorySSSCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EmployeeMandatorySSSCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\EmployeeMandatorySSS');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/employee-mandatory-sss');
        $this->crud->setEntityNameStrings('SSS', 'Employee Mandatory SSS');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in EmployeeMandatorySSSRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');




        /**
        *    CUSTOMIZE THE FIELDS AND COLUMNS
        **/

        // FIELDS

        $this->crud->addField([
            'type' => 'employeeMandatorySSS.employee_mandatory_sss_scripts',
            'name' => 'employee_mandatory_sss_scripts'
        ]);

        $this->crud->addField([
            'value' => '<h3>Range Of Compensation</h3>',
            'name'  => 'range_of_compensation',
            'type'  => 'custom_html'
        ])->beforeField('range_of_compensation_min');

        $this->crud->addField([
            'label'             => 'Min',
            'name'              => 'range_of_compensation_min',
            'type'              => 'text',
            'wrapperAttributes' => 
                                [
                                   'class' => 'form-group col-md-6'
                                ]
        ]);

        $this->crud->addField([
            'label'             => 'Max',
            'name'              => 'range_of_compensation_max',
            'type'              => 'text',
            'wrapperAttributes' => 
                                [
                                   'class' => 'form-group col-md-6'
                                ]
        ]);

        $this->crud->addField([
            'value' => '<hr>',
            'name'  => 'hr1',
            'type'  => 'custom_html'
        ])->afterField('range_of_compensation_max');

        $this->crud->addField([
            'label'             => 'Monthly Salary Credit',
            'name'              => 'monthly_salary_credit',
            'type'              => 'text',
            'wrapperAttributes' => 
                                [
                                   'class' => 'form-group col-md-12'
                                ]
        ]);

        $this->crud->addField([
            'value' => '<hr>',
            'name'  => 'hr2',
            'type'  => 'custom_html'
        ])->afterField('monthly_salary_credit');


        // SOCIAL SECURITY (Employer - Employee)

        $this->crud->addField([
            'value' => '<h3 class="">Social Security (Employer - Empoloyee)</h3>',
            'name'  => 'social_security',
            'type'  => 'custom_html'
        ])->afterField('hr2');

        $this->crud->addField([
            'label'             => 'Employer',
            'name'              => 'social_security_er',
            'type'              => 'text',
            'attributes'        => 
                                [
                                    'id'   => 'social_security_er'
                                ],
            'wrapperAttributes' => 
                                [
                                   'class' => 'form-group col-md-6'
                                ]
        ]);

        $this->crud->addField([
            'label'             => 'Employee',
            'name'              => 'social_security_ee',
            'type'              => 'text',
            'attributes'        => 
                                [
                                    'id'   => 'social_security_ee'
                                ],
            'wrapperAttributes' => 
                                [
                                   'class' => 'form-group col-md-6'
                                ]
        ]);

        $this->crud->addField([
            'value' => '<hr>',
            'name'  => 'hr3',
            'type'  => 'custom_html'
        ])->afterField('social_security_ee');

        // EC

        $this->crud->addField([
            'value' => '<h3 class="">EC</h3>',
            'name'  => 'ec_hr',
            'type'  => 'custom_html'
        ])->afterField('hr3');

        $this->crud->addField([
            'label'             => 'Employer',
            'name'              => 'ec_er',
            'type'              => 'text',
            'attributes'        => 
                                [
                                    'id'   => 'ec_er'
                                ],
            'wrapperAttributes' => 
                                [
                                   'class' => 'form-group col-md-12'
                                ]
        ]);

        $this->crud->addField([
            'value' => '<hr>',
            'name'  => 'hr4',
            'type'  => 'custom_html'
        ])->afterField('ec_er');

        // TOTAL CONTRIBUTION

        $this->crud->addField([
            'value' => '<h3 class="">Total Contribution</h3>',
            'name'  => 'total_contribution_header',
            'type'  => 'custom_html'
        ])->afterField('hr4');

        $this->crud->addField([
            'label'             => 'Employer',
            'name'              => 'total_contribution_er',
            'type'              => 'text',
            'attributes'        => 
                                [
                                    'id'       => 'total_contribution_er',
                                    'disabled' => true
                                ],
            'wrapperAttributes' => 
                                [
                                   'class' => 'form-group col-md-6'
                                ]
        ]);

        $this->crud->addField([
            'label'             => 'Employee',
            'name'              => 'total_contribution_ee',
            'type'              => 'text',
            'attributes'        => 
                                [
                                    'id'       => 'total_contribution_ee',
                                    'disabled' => true
                                ],
            'wrapperAttributes' => 
                                [
                                   'class' => 'form-group col-md-6'
                                ]
        ]);

        // END OF FIELDS


        // COLUMNS

        $this->crud->addColumn([
            'label'             => 'Compensation Min',
            'name'              => 'range_of_compensation_min',
            'type'              => 'text',
        ]);

        $this->crud->addColumn([
            'label'             => 'Compensation Max',
            'name'              => 'range_of_compensation_max',
            'type'              => 'text',
        ]);

        $this->crud->addColumn([
            'label'             => 'Monthly Salary Credit',
            'name'              => 'monthly_salary_credit',
            'type'              => 'text',
        ]);


        // SOCIAL SECURITY (Employer - Employee)

        $this->crud->addColumn([
            'label'             => 'Social Security ER',
            'name'              => 'social_security_er',
            'type'              => 'text',
        ]);

        $this->crud->addColumn([
            'label'             => 'Social Security EE',
            'name'              => 'social_security_ee',
            'type'              => 'text',
        ]);

        $this->crud->addColumn([
            'label'             => 'EC Employer',
            'name'              => 'ec_er',
            'type'              => 'text',
        ]);

        $this->crud->addColumn([
            'label'             => 'Total Contribution Employer',
            'name'              => 'total_contribution_er',
            'type'              => 'text',
        ]);

        $this->crud->addColumn([
            'label'             => 'Total Contribution Employee',
            'name'              => 'total_contribution_ee',
            'type'              => 'text',
        ]);


        // END OF COLUMNS


    }



    public function showDetailsRow($id)
    {
        $this->crud->hasAccessOrFail('details_row');

        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('crud::employeeMandatorySSS.details_row', $this->data);
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
