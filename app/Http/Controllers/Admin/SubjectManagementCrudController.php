<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SubjectManagementRequest as StoreRequest;
use App\Http\Requests\SubjectManagementRequest as UpdateRequest;

// MODELS
use App\Models\CurriculumManagement;

class SubjectManagementCrudController extends CrudController
{
    public function setup()
    {
        // $user = \Auth::user();
        // $permissions = collect($user->getAllPermissions());

        // $plucked = $permissions->pluck('name');
        // $this->allowed_method_access = $plucked->all();

        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);
        // $this->crud->allowAccess($this->allowed_method_access);
        
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SubjectManagement');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/subject_management');
        $this->crud->setEntityNameStrings('Subject Management', 'Subject Management');

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setFromDb();

        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
            'label' => 'Percentage',
            'type'  => 'number',
            'name'  => 'text'
        ]);

        $this->crud->addColumn([
            'label' => "Price",
           'type' => "text",
           'name' => 'price',
        ]);

        $this->crud->addColumn([
            'label' => "Parent",
            'type' => 'select',
            'name' => 'parent_id',
            'entity' => 'subject',
            'attribute' => 'subject_code',
            'model' => 'App\Models\SubjectManagement'
        ]);


        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([
            'label' => 'Percentage',
            'type'  => 'number',
            'name'  => 'percent',
            'default' => 100
        ]);

        $this->crud->addField([
            // 1-n relationship
           'label' => "Price", // Table column heading
           'type' => "number",
           'name' => 'price', // the column that contains the ID of that connected entity;
        //    'attributes' => ['placeholder' => '(optional)']
        ]);

        $this->crud->addField([
            'label' => 'No. Unit',
            'type' => 'number',
            'name' => 'no_unit',
            'attributes' => ["step" => "any", "max" => 100],
            'default' => 1
        ]);

        $this->crud->addField([
            'label' => 'Parent',
            'name' => 'parent_id',
            'type' => 'select2',
            'entity' => 'subject',
            'attribute' => 'subject_title',
            'model' => 'App\Models\SubjectManagement'
        ]);

        // FILTTERS
        // $this->crud->addFilter([ // dropdown filter
        //   'name' => 'curriculum',
        //   'type' => 'dropdown',
        //   'label'=> 'Curriculum'
        // ], CurriculumManagement::all()->pluck('curriculum_name', 'id')->toArray() , function($value) { // if the filter is active
        //     $this->crud->addClause('where', 'curriculum_id', $value);
        // });
        
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $searchTerm = strtolower($request->subject_code);
        $isExists = $this->crud->model::whereRaw('lower(subject_code) = (?)', [$searchTerm])->exists();
        // dd($isExists);
        if($isExists) {
            \Alert::warning("Subject Code Name Is Already Taken")->flash();
            return redirect()->back()->withInput();
        }

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

    public function subject ($id)
    {
      $model = $this->crud->model::findOrFail($id);
      return $model;
    }
}
