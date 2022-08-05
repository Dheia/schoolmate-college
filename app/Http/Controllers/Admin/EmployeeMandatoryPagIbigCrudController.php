<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EmployeeMandatoryPagIbigRequest as StoreRequest;
use App\Http\Requests\EmployeeMandatoryPagIbigRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class EmployeeMandatoryPagIbigCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EmployeeMandatoryPagIbigCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\EmployeeMandatoryPagIbig');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/employee-mandatory-pag-ibig');
        $this->crud->setEntityNameStrings('Pag-Ibig', 'Pag-Ibig');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in EmployeeMandatoryPagIbigRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->addButtonFromView('line', 'setActive', 'active', 'end');

        $this->crud->allowAccess('show');

        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([
            'label' => 'Name',
            'name' => 'name',
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => 'Computation Salary Table',
            'name' => 'computation_salary_table',
            'type' => 'child',
            'columns' => [
                [
                    'label' => 'Salary Bracket',
                    'type' => 'child_number',
                    'name' => 'salary_bracket',
                    'attributes' => [ 'required' => 'required', ]
                ],
                [
                    'label' => 'Salary Per Month Min',
                    'type' => 'child_number',
                    'name' => 'salary_per_month_min',
                    'attributes' => [ 'required' => 'required', ]
                ],
                [
                    'label' => 'Salary Per Month Max',
                    'type' => 'child_number',
                    'name' => 'salary_per_month_max',
                    'attributes' => [ 'required' => 'required', ]
                ],
                [
                    'label' => 'Employee Share',
                    'type' => 'child_number',
                    'name' => 'employee_share',
                    'attributes' => [ 'required' => 'required', ]
                ],
                [
                    'label' => 'Employer Share',
                    'type' => 'child_number',
                    'name' => 'employer_share',
                    'attributes' => [ 'required' => 'required', ]
                ],
            ],
            'min' => '1',
        ]);

        // $this->crud->addField([
        //     'label' => 'Active',
        //     'name' => 'active',
        //     'type' => 'checkbox',
        // ]);

        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
            'label' => 'Name',
            'name' => 'name',
            'type' => 'text',
        ]);

        if($this->crud->getActionMethod() == 'show')
        {
            $this->crud->addColumn([
                'label' => 'Computation Salary Table',
                'name' => 'computation_salary_table',
                'type' => 'table',
                'columns' => [
                    'salary_bracket'    => 'Salary Bracket',
                    'salary_per_month_min'    => 'Salary Per Month Min',
                    'salary_per_month_max'    => 'Salary Per Month Max',
                    'employee_share'    => 'Employee Share',
                    'employer_share'    => 'Employer Share',
                ]
            ]);
        }

        $this->crud->addColumn([
            'label' => 'Active',
            'name' => 'active',
            'type' => 'check',
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

    public function setActive ($id, $status)
    {
        $entity = $this->crud->model::where('id', $id);

        if($entity->first()) {
            if($entity->first()->active !== 1) {
                $this->crud->model->update(['active' => 0]);
                $entity->update(['active' => 1]);
                \Alert::success("Successfully Activate")->flash();
                return redirect('admin/employee-mandatory-pag-ibig');
            }
            if($entity->first()->active !== 0) {
                $entity->update(['active' => 0]);
                \Alert::success("Successfully Deactivate")->flash();
                return redirect('admin/employee-mandatory-pag-ibig');
            }
        }
        abort(404);
    } 
}
