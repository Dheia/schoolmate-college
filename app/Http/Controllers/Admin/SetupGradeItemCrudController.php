<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SetupGradeItemRequest as StoreRequest;
use App\Http\Requests\SetupGradeItemRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use App\Models\SetupGrade;

/**
 * Class SetupGradeItemCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SetupGradeItemCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SetupGradeItem');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/setup-grade-item');
        $this->crud->setEntityNameStrings('setupgradeitem', 'setup_grade_items');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in SetupGradeItemRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->denyAccess(['list', 'create']);
        
        $this->crud->addField([
            'label' => 'Setup Grade',
            'type' => 'hidden',
            'name' => 'setup_grade_id',
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
        $setupGrade = SetupGrade::where('id', $request->setup_grade_id)->first();

        if($setupGrade !== null) {
            if($setupGrade->teacher_id != backpack_auth()->user()->id) {
                \Alert::warning("Error, You Do Not Own This Item")->flash();
                return redirect()->back();
            }
        } else {
            \Alert::warning("Parent Of This Item Is Not Found")->flash();
            return redirect()->back();
        }

        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
