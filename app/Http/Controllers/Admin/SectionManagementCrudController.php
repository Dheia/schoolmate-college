<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;

use App\Models\SubjectManagement;
use App\Models\SectionManagement;
use App\Models\TrackManagement;
use App\Models\YearManagement;
use App\Models\Department;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SectionManagementRequest as StoreRequest;
use App\Http\Requests\SectionManagementRequest as UpdateRequest;

class SectionManagementCrudController extends CrudController
{   
    public function setup()
    {  
    $this->crud->setDefaultPageLength(10); 
        // $user = \Auth::user();
        // $permissions = collect($user->getAllPermissions());

        // $plucked = $permissions->pluck('name');
        // $this->allowed_method_access = $plucked->all();

        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);
        // $this->crud->allowAccess($this->allowed_method_access);
        
         // $this->crud->hasAccessOrFail('section');
        
        $subjMngmnt = SubjectManagement::all();
        $this->data['subject_management'] = $subjMngmnt;
        $this->crud->subject_management = $subjMngmnt;
        // dd($this->crud);
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SectionManagement');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/section_management');
        $this->crud->setEntityNameStrings('Section', 'Section Management');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
         $this->crud->addField([
            'name' => 'department_id',
            'type' => 'select_from_array',
            'label' => 'Select Department',
            'options' => Department::active()->pluck('name', 'id'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
            'attributes' => [
                'id' => 'searchDepartment',
            ],
        ]);

        $this->crud->addColumn([
            'label'             => 'Department',
            'type'              => 'text',
            'name'              => 'department',
        ]);

        $this->crud->setFromDb();
        // $this->crud->setListView('custom.section');
        // 
        $this->crud->enableBulkActions();
        $this->crud->addButtonFromView('line', 'clone', 'clone', 'beginning');
        $this->crud->addButton('bottom', 'bulk_clone', 'view', 'crud::buttons.bulk_clone', 'beginning');


        $this->crud->child_resource_included = ['select' => false, 'number' => false];

        $this->crud->addField([
                'label' => 'Curriculum',
                'type' => 'select',
                'name' => 'curriculum_id',
                // 'store_in' => 'subject_details',
                'entity' => 'curriculum',
                'attribute' => 'curriculum_name',
                'model' => 'App\Models\CurriculumManagement',
                // 'attributes' => ['ng-model' => 'changeSubject()']
        ]);

        // $this->crud->addField(
        //     [
        //         'label' => 'Level',
        //         'type' => 'select',
        //         'name' => 'level_id',
        //         'entity' => 'level',
        //         'attribute' => 'year',
        //         'model' => 'App\Models\YearManagement'
        //     ]
        // );

        $this->crud->addField(
            [
                'label' => 'Level',
                'type' => 'select_from_array',
                'name' => 'level_id',
                // 'options'           => YearManagement::whereIn('department_id', Department::active()->pluck('id'))->pluck('year', 'id')
                'options' => []
            ]
        );

        $this->crud->addField([
            'label' => 'Track',
            'type' => 'select_from_array',
            'name' => 'track_id',
            'options' => []
        ]);

                // SCRIPT
        $this->crud->addField([
            'label' => 'script',
            'type' => 'sectionManagement.script',
            'name' => 'sectionManagementScript',
        ]);
        // END OF SCRIPT


        // ------ CRUD COLUMNS
        $this->crud->removeColumn('subject_details');

        $this->crud->addColumn(
            [
               'label' => "Curriculum", // Table column heading
               'type' => "select",
               'name' => 'curriculum_id', // the column that contains the ID of that connected entity;
               'entity' => 'curriculum', // the method that defines the relationship in your Model
               'attribute' => "curriculum_name", // foreign key attribute that is shown to user
               'model' => "App\Models\CurriculumManagement", // foreign key model
            ]
        );

        $this->crud->addColumn(
            [
                'label' => 'Level',
                'type' => 'select',
                'name' => 'level_id',
                'entity' => 'level',
                'attribute' => 'year',
                'model' => 'App\Models\YearManagement'
            ]
        );

        $this->crud->addColumn(
            [
                'label' => 'Level',
                'type' => 'select',
                'name' => 'level_id',
                'entity' => 'level',
                'attribute' => 'year',
                'model' => 'App\Models\YearManagement'
            ]
        );

        $this->crud->addColumn([
            'label' => 'Track',
            'type' => 'select',
            'name' => 'track_id',
            'entity' => 'track',
            'attribute' => 'code',
            'model' => 'App\Models\TrackManagement'
        ]);

        $this->crud->addFilter([ // select2_multiple filter
          'name' => 'track_id',
          'type' => 'select2',
          'label'=> 'Level/Track'
        ], function() { // the options that show up in the select2
            return TrackManagement::all()->pluck('description_formatted', 'id')->toArray();
        }, function($value) { // if the filter is active
            // foreach (json_decode($values) as $key => $value) {
                $this->crud->addClause('where', 'track_id', $value);
            // }
        });
    }

    public function clone($id)
    {
        $this->crud->hasAccessOrFail('create');
        $clonedEntry = $this->crud->model->findOrFail($id)->replicate();
        return (string) $clonedEntry->push();
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

    public function View ($id) {
        $data = SectionManagement::find($id);
        // dd($data);

        return view('custom.view_section', compact('data'));
    }

    public function getTracks () {
        $tracks = TrackManagement::where('level_id', request()->level_id)->active()->get();
        return $tracks;
    }
}