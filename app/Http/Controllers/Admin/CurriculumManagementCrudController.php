<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CurriculumManagementRequest as StoreRequest;
use App\Http\Requests\CurriculumManagementRequest as UpdateRequest;

class CurriculumManagementCrudController extends CrudController
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
        $this->crud->setModel('App\Models\CurriculumManagement');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/curriculum_management');
        $this->crud->setEntityNameStrings('Curriculum', 'Curriculums');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();
        $this->crud->child_resource_included = ['select' => false, 'number' => false];

        // $this->crud->enableDetailsRow();
        // $this->crud->allowAccess('details_row');
        $this->crud->removeButton('update');
        $this->crud->removeButton('delete');
        $this->crud->addButtonFromView('line', 'Update', 'curriculum.update', 'start');
        $this->crud->addButtonFromView('line', 'Delete', 'curriculum.delete', 'end');
        $this->crud->addButtonFromView('line', 'Print', 'curriculum.print', 'end');
        $this->crud->addButtonFromView('line', 'Active', 'curriculum.active', 'end');
       
        

        $this->crud->addColumn([
            'label'         => "Total Subject Mapped",
            'type'          => 'model_function',
            'name'          => 'total_subject_map',
            'function_name' => 'getTotalSubjectMapSlugWithLink',
            'limit'         => '200',
        ]);

        $this->crud->addColumn([
            'label'         => "Active",
            'type'          => 'check',
            'name'          => 'is_active',
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
                return response()->json($this->crud->model->all());

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

    public function showDetailsRow($id)
    {
        $this->crud->hasAccessOrFail('details_row');

        $this->data['entry'] = $this->crud->getEntry($id)->with('subjectMappings')->first();
        // dd($this->data['entry']->subjectMappings[0]->subjects);
        $this->data['crud'] = $this->crud;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('crud::curriculum.details_row', $this->data);
    }

    public function print ($id)
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', -1);
        // $curriculum_subjects = $this->crud->model::find($id)->with(['subjectMappings' => function ($query) {
        //                                                         $query->with(['curriculum', 'department', 'level', 'term', 'track']);
        //                                                     }])->first();

        $curriculum_subjects = $this->crud->model::where('id', $id)->with(['subjectMappings' => function ($query) {
                                                                $query->with(['curriculum', 'department', 'level', 'term', 'track']);
                                                            }])->first();

        // if($curriculum_subjects == null)
        // {
        //     abort(404);
        // }
        // // dd($curriculum_subjects);
        // $pdf = \App::make('dompdf.wrapper');
        // // $pdf->setPaper(array(0, 0, 612, 936), 'portrait');

        // $pdf->loadHTML( view('curriculum.print', compact('curriculum_subjects')) );
        // return $pdf->stream();

        
        $count_subject = $curriculum_subjects->subjectMappings->count();
        $schoollogo      = config('settings.schoollogo') ? (string)\Image::make(config('settings.schoollogo'))->encode('data-url') : null;
        $schoolmate_logo = (string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url');
        
        return view('curriculum.new_print', compact('curriculum_subjects','count_subject','schoollogo','schoolmate_logo'));
    }

    public function getCurriculumWithSubjects ($id)
    {
        $this->data['curriculum']   =   $this->crud->model::where('id', $id)->with(['subjectMappings' => function ($query) {
                                                                $query->with(['curriculum', 'department', 'level', 'term', 'track']);
                                                            }])->first();
        $this->data['crud']         = $this->crud;
        // return $this->data['curriculum'];
        return view('curriculum.curriculum_subjects', $this->data);
    }

    public function setActive ($id) 
    {
        $this->crud->model::where('id', $id)->update(['is_active' => 1]);
        \Alert::success("The item has been set to active successfully.")->flash();
        return redirect()->back();
    }

    public function setDeactive ($id) 
    {
        $this->crud->model::where('id', $id)->update(['is_active' => 0]);
        \Alert::success("The item has been set to deactive successfully.")->flash();
        return redirect()->back();
    }
}
