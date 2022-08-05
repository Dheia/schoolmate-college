<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\DB;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SectionBuilderRequest as StoreRequest;
use App\Http\Requests\SectionBuilderRequest as UpdateRequest;
use App\Models\SectionManagement;
use App\Models\SubjectManagement;

/**
 * Class SectionBuilderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SectionBuilderCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SectionBuilder');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/section-builder');
        $this->crud->setEntityNameStrings('sectionbuilder', 'section_builders');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in SectionBuilderRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        // FIELD
        $this->crud->addField([
            'label'     => 'Section',
            'type'      => 'select',
            'name'      => 'section_id',
            'entity'    => 'section',
            'attribute' => 'name',
            'model'     => 'App\Models\SectionManagement'
        ]);

        $this->crud->addField([
            // n-n relationship (with pivot table)
            'label'     => "Subjects", // Table column heading
            'type'      => "sectionBuilder.checklist",
            'name'      => 'subjects', // the method that defines the relationship in your Model
            'entity'    => 'subjects', // the method that defines the relationship in your Model
            'attribute' => "name_and_percent", // foreign key attribute that is shown to user
            'model'     => "App\Models\SubjectManagement", // foreign key model
            'pivot'     => true,
            'select_name' => 'section_id',
            'wrapperAttributes' => [
                'class' => 'subjects form-group col-xs-12'
            ]
        ]);


        // COLUMN
        $this->crud->addColumn([
            'label'     => 'Section',
            'type'      => 'select',
            'name'      => 'section_id',
            'entity'    => 'section',
            'attribute' => 'name',
            'model'     => 'App\Models\SectionManagement'
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


    public function destroy($id)
    {
        $this->crud->model::where('id', $id)->delete();

        $model = $this->crud->model::findOrFail($id);
        DB::table('section_builder_subject_management')->where('section_builder_id', $id)->delete();
    }


    public function getSectionSubjects($id)
    {
        $section = SectionManagement::where('id', $id)->first();
        abort(404);
        // dd($section);
        $subject_ids = array_pluck($section->subject_details, 'subject_id');
        $subjects = SubjectManagement::findMany($subject_ids);
        $selected_subjects = $this->crud->model::where('section_id', $id)->with('subjects')->get();
        return response()->json(["subjects" => $subjects, "selected_subjects" => $selected_subjects]);
    }
}
