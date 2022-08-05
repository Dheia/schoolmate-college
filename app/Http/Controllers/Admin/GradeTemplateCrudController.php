<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GradeTemplateRequest as StoreRequest;
use App\Http\Requests\GradeTemplateRequest as UpdateRequest;

use App\Models\SchoolYear;

/**
 * Class GradeTemplateCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GradeTemplateCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\GradeTemplate');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/grade-template');
        $this->crud->setEntityNameStrings('Template', 'Grade Templates');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in GradeTemplateRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $schoolyearid = null;

        // $this->crud->addField([
        //     'name' => 'schoolyear_id',
        //     'label' => 'School Year',
        //     'type' => 'select',
        //     'attribute' => 'schoolYear',
        //     'entity' => 'schoolyear',
        //     'model' => 'App\Models\SchoolYear',
        //     'options'   => (function ($query) {

        //         return $query->orderBy('isActive', 'DESC')->get();
                
        //     }),
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-6'
        //     ]
        // ]);

        $this->crud->addField([
            'label' => 'Name',
            'type' => 'text',
            'name' => 'name',
        ]);

        $this->crud->addField([
            'name' => 'school_year_id',
            'label' => 'School Year',
            'type' => 'select_from_array',
            'options' => SchoolYear::active()->pluck('schoolYear', 'id'),
            // 'attribute' => 'schoolYear',
            // 'entity' => 'schoolYear',
            // 'model' => 'App\Models\SchoolYear',
        ]);

        $this->crud->addColumn([
            'name' => 'school_year_id',
            'label' => 'School Year',
            'type' => 'select',
            'attribute' => 'schoolYear',
            'entity' => 'schoolYear',
            'model' => 'App\Models\SchoolYear',
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

    public function destroy ($id)
    {
        $model = $this->crud->model::findOrFail($id);
        $model->delete();
    }
}
