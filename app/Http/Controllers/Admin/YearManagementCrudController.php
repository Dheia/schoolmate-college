<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\YearManagementRequest as StoreRequest;
use App\Http\Requests\YearManagementRequest as UpdateRequest;

use Illuminate\Http\Request;

use App\Models\YearManagement;
use App\Models\Department;

class YearManagementCrudController extends CrudController
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
        
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\YearManagement');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/year_management');
        $this->crud->setEntityNameStrings('Level', 'Level Management');


        // $this->crud->enableReorder("Reorder", MAX_TREE_LEVEL);
        // $this->crud->allowAccess('reorder');
        $this->crud->addButtonFromView('top', 'Sequence', 'sequence', 'ending');
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();


        // ------ CRUD COLUMNS

        $this->crud->addColumn([
            'label'     => 'Department',
            'type'      => 'select',
            'name'      => 'department_id',
            'entity'    => 'department', 
            'attribute' => 'name',
            'model'     => 'App\Models\Department',
        ]);

        // ------ CRUD FIELDS
        // $this->crud->addField($options, 'update/create/both');
        $this->crud->addField([
            'name' => 'year',
            'label' => 'Level Name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'label'     => 'Department',
            'type'      => 'select2_from_array',
            'name'      => 'department_id',
            'options'   => Department::active()->pluck('name', 'id'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name' => 'time_in',
            'label' => 'Time of Entry',
            'type' => 'time',
            'default' => '06:00:00',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name' => 'time_out',
            'label' => 'Time of Exit',
            'type' => 'time',
            'default' => '18:00:00',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->removeFields(['sequence']);
        
        $this->crud->orderBy('sequence');
        $this->crud->setDefaultPageLength(30);
    }


    public function resequence ()
    {
        $items = $this->crud->model::orderBy('sequence', 'ASC')->get();
        $crud = $this->crud;
        $attribute = 'year';
        // dd($items);
        return view('resequence', compact('items', 'crud', 'attribute'));
    }
    
    public function saveSequence (Request $request) 
    {   
        $ids = json_decode($request->sequence); 
        $array = [];
        $flagError = false;
        for($i = 0; $i < count($ids); $i++) {
            $model = $this->crud->model::find($ids[$i]);
            $model->sequence = $i + 1;
            if(!$model->save()) {
                $flagError = true;
            }
        }

        if($flagError) {
            \Alert::success("Error Saving")->flash();
            return redirect()->back()->withInput($request->input());
        }
        \Alert::success("Succesfully Update")->flash();
        return redirect()->to($this->crud->route);
        // return redirect()->back()->withInput($request->input());
    }



    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        
        // $nameIsExists = $this->crud->model::where('year', $request->year)->exists();

        // if($nameIsExists) {
        //     \Alert::warning("Name Is Already Existing")->flash();
        //     return back();
        // }

        // GET THE MODEL 
        $sy = $this->crud->model::latest()->first();

        // CHECK IF NOT NULL SCHOOL YEAR SEQUENCE
        if($sy !== null)
        {
            if($sy->sequence !== null)
            {
                $sy;
                $request->request->set('sequence', $sy->sequence + 1);
            }
        } else {
            $request->request->set('sequence', 1);
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
}
