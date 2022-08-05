<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CourseManagementRequest as StoreRequest;
use App\Http\Requests\CourseManagementRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\YearManagement;

/**
 * Class CourseManagementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CourseManagementCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\CourseManagement');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/course_management');
        $this->crud->setEntityNameStrings('coursemanagement', 'course_managements');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in CourseManagementRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $levels = YearManagement::join('departments', 'departments.id', 'year_managements.department_id')
                                ->where('departments.course', 1)
                                ->select('year_managements.id', 'year_managements.year')
                                ->get()
                                ->pluck('year', 'id')
                                ->toArray(); 

        $this->crud->addField([
            'label' => 'Number of Years',
            'name' => 'level_id',
            'type' => 'select2_from_array',
            'options' => $levels
        ]);


        $this->crud->addColumn([
            'label' => 'Level',
            'name' => 'level_id',
            'type' => 'select_from_array',
            'options' => $levels
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
