<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\DepartmentRequest as StoreRequest;
use App\Http\Requests\DepartmentRequest as UpdateRequest;

use App\Models\TermManagement;

/**
 * Class DepartmentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class DepartmentCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Department');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/department');
        $this->crud->setEntityNameStrings('department', 'department management');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in DepartmentRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->addField([
            'label' => 'Name',
            'type' => 'text',
            'name' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Term Type',
            'name' => 'term_type',
            'type' => 'select_from_array',
            'options' => ['FullTerm' => 'Full Term', 'Semester' => 'Semester'],
            'wrapperAttributes' => [
                'class' =>  'form-group col-md-6',
                'id'    =>  'term_type'
            ]
        ])->afterField('name');

        $this->crud->addField([
            'label' => 'No of Term',
            'name' => 'no_of_term',
            'type' => 'number',
            'wrapperAttributes' => [
                'class' =>  'form-group col-md-12',
                'id'    =>  'no_of_term',
                'style' =>  'display: none;'
            ]
        ])->afterField('term_type');

        $this->crud->addField([
            'label' => 'Enable Track',
            'type' => 'checkbox',
            'name' => 'with_track',
            'wrapperAttributes' => [
                'class' => 'form-group p-l-15',
                'style' => 'display: inline-block'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Enable Course',
            'type' => 'checkbox',
            'name' => 'course',
            'wrapperAttributes' => [
                'class' => 'form-group p-l-15',
                'style' => 'display: inline-block'
            ]
        ]);

        $this->crud->addField([
            'label' => '',
            'type' => 'department.script',
            'name' => 'department_script'
        ]);

        $this->crud->addColumn([
            'label' => 'Enable Tracks',
            'type' => 'check',
            'name' => 'with_track',
        ]);

        $this->crud->addColumn([
            'label' => 'Enable Course',
            'type' => 'check',
            'name' => 'course',
        ]);

        $this->crud->addColumn([
            'label' => 'Active',
            'type'  => 'check',
            'name'  => 'active'
        ]);

        $this->crud->removeButton('update');
        $this->crud->removeButton('delete');
        $this->crud->denyAccess(['delete']);
        $this->crud->addButtonFromView('line', 'update', 'department.update', 'end');
        $this->crud->addButtonFromView('line', 'active', 'department.active', 'end');
        $this->crud->addButtonFromView('line', 'delete', 'department.delete', 'end');
        $this->crud->setHeading('Department');
         // Setters
        //dd(get_class_methods($this->crud));

        // $this->crud->setTitle('Add New Department', 'create'); // set the Title for the create action
        // $this->crud->setHeading('Add New Department', 'create'); // set the Heading for the create action
        // $this->crud->setSubheading('Create departments for employee assignments', 'create'); // set the Subheading for the create action
        
        // $this->crud->setHeading('Update Department', 'edit'); // set the Heading for the create action
        // $this->crud->setSubheading('Modify department information', 'edit'); // set the Subheading for the create action

         // $this->crud->setCreateView('crud.create');
         // $this->crud->setListView('list');

    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry

        $no_of_term = $request->no_of_term ?? 0;
        if($request->term_type == 'FullTerm') {
            $no_of_term = 0;
        }

        $term = new TermManagement;
        $term->department_id = $this->crud->entry->id;
        $term->type = $request->term_type;
        $term->no_of_term = $no_of_term;
        $term->save();

        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {

        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry

        $no_of_term = $request->no_of_term ?? 0;
        if($request->term_type == 'FullTerm') {
            $no_of_term = 0;
        }

        TermManagement::where('department_id', $this->crud->entry->id)->update(['type' => $request->term_type, 'no_of_term' => $no_of_term]);

        return $redirect_location;
    }

    public function destroy ($id)
    {
        $model = $this->crud->model::findOrFail($id);
        $model->delete();
    }

    public function getDepartment($id)
    {
        $department = $this->crud->model::where('id', $id)
                                  ->with([
                                            'levels' => function ($q) use ($id) {
                                                $q->select('id', 'year', 'department_id'); 
                                                $q->with(['tracks'=> function ($qt) {
                                                    $qt->where('active', 1);
                                                }]);
                                                $q->with('courses:id,acronym,level_id');
                                            },
                                            'terms:id,department_id,type',
                                            'term'
                                        ])->first(['id', 'name']);
        return $department;
    }

    public function setActive ($id) 
    {
        $this->crud->model::where('id', $id)->update(['active' => 1]);
        \Alert::success("The item has been set to active.")->flash();
        return redirect()->back();
    }

    public function setDeactive ($id) 
    {
        $this->crud->model::where('id', $id)->update(['active' => 0]);
        \Alert::success("The item has been set to deactive.")->flash();
        return redirect()->back();
    }
}
