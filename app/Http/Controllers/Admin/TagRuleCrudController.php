<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TagRuleRequest as StoreRequest;
use App\Http\Requests\TagRuleRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class TagRuleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TagRuleCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\TagRule');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/tag-rule');
        $this->crud->setEntityNameStrings('Tag Rule', 'Tag Rules');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in TagRuleRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->addField([
            'label'         => 'Employee Number',
            'name'          => 'employee_number',
            'type'          => 'hidden',
            'attributes'    => [
                'id' => 'studentNumber'
            ]
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
            'label'     => 'Rule',
            'type'      => 'select',
            'name'      => 'rule_id',
            'entity'    => 'rule',
            'attribute' => 'rule_name',
            'model'     => 'App\Models\EmployeeAttendanceRule'
        ])->afterField('employee_number');

        // COLUMNS

        $this->crud->addColumn([
            'label'     => 'Employee Number',
            'type'      => 'text',
            'name'      => 'employee_number',
            'prefix' => \Setting::get('schoolabbr') . '-',
        ]);

        $this->crud->addColumn([
            'label'     => 'Rule',
            'type'      => 'select',
            'name'      => 'rule_id',
            'entity'    => 'rule',
            'attribute' => 'rule_name',
            'model'     => 'App\Models\EmployeeAttendanceRule'
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
