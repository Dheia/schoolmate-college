<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EmployeeAttendanceRuleRequest as StoreRequest;
use App\Http\Requests\EmployeeAttendanceRuleRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\SchoolYear;

/**
 * Class EmployeeAttendanceRuleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EmployeeAttendanceRuleCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\EmployeeAttendanceRule');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/attendance-rule');
        $this->crud->setEntityNameStrings('Rule', 'Attendance Rules');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in EmployeeAttendanceRuleRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');


        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([
            'label' => 'Rule Name',
            'type'  => 'text',
            'name'  => 'rule_name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
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
            'label' => 'Applied In Pre-Time <small style="font-weight: 200 !important;"> (Minutes) </small>',
            'type'  => 'number',
            'name'  => 'applied_in_pretime',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Applied In Post-Time <small style="font-weight: 200 !important;"> (Minutes) </small>',
            'type'  => 'number',
            'name'  => 'applied_in_posttime',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Total Working Hours Week',
            'type'  => 'number',
            'name'  => 'total_working_hours_week',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Allowed Overtime',
            'type'  => 'checkbox',
            'name'  => 'allowed_overtime',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
            'label'     => 'School Year',
            'type'      => 'select',
            'name'      => 'school_year_id',
            'entity'    => 'schoolYear', 
            'attribute' => 'schoolYear',
            'model'     => 'App\Models\SchoolYear',
        ]); 

        $this->crud->addColumn([
            'label'  => 'Applied In Pre-Time',
            'type'   => 'text',
            'name'   => 'applied_in_pretime',
            'suffix' => ' minute(s)'
        ]);

        $this->crud->addColumn([
            'label'  => 'Applied In Post-Time',
            'type'   => 'text',
            'name'   => 'applied_in_posttime',
            'suffix' => ' minute(s)'
        ]);

        $this->crud->addColumn([
            'label'  => 'Total Working Hours Week',
            'type'   => 'text',
            'name'   => 'total_working_hours_week',
            'suffix' => ' hour(s)'
        ]);

        $this->crud->addColumn([
            'label'  => 'Allowed Overtime',
            'type'   => 'check',
            'name'   => 'allowed_overtime',
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
