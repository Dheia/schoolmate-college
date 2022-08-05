<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PeriodRequest as StoreRequest;
use App\Http\Requests\PeriodRequest as UpdateRequest;

// MODELS
use App\Models\Department;

/**
 * Class PeriodCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PeriodCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Period');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/period');
        $this->crud->setEntityNameStrings('period', 'periods');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in PeriodRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');


        $this->crud->groupBy(['department_id']);
        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');

        $this->crud->removeColumns(['name', 'sequence']);
        $this->crud->removeButton('delete');
        $this->crud->removeFields(['sequence']);

        $this->crud->addField([
            'name' => 'department_id',
            'label' => 'Department',
            'type' => 'select2_from_array',
            'options' => Department::active()->pluck('name', 'id')
        ])->beforeField('name');

        $this->crud->addColumn([
            'name' => 'department_id',
            'label' => 'Department',
            'type' => 'select',
            'attribute' => 'name',
            'entity' => 'department',
            'model' => 'App\Models\Department',
        ])->beforeField('name');

        if($this->crud->getActionMethod() === "edit") {
            $this->crud->addField([
                'name' => 'department_id',
                'label' => 'Department',
                'type' => 'hidden',
            ])->beforeField('name');
        }

        if(env('APP_DEBUG') === false) {
            $pathRoute = str_replace("admin/", "", $this->crud->route);
            $user = backpack_auth()->user();

            $permissions = $user->getAllPermissions();
            $allowed_method_access = $user->getAllPermissions()->pluck('name')->toArray();

            foreach ($permissions as $permission) {
                if($permission->page_name === $pathRoute) {
                    $methodName = strtolower( explode(' ', $permission->name)[0] );
                    array_push($allowed_method_access, $methodName);
                }
            }

            $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone', 'show']);
            $this->crud->allowAccess($allowed_method_access);
        }
    }

    public function store(StoreRequest $request)
    {
        // Check if has already exisintg department then increment sequence
        $latest = $this->crud->model::where('department_id', $request->department_id)->latest('sequence')->first();   

        $latest === null ? $request->request->set('sequence', 1) : $request->request->set('sequence', $latest->sequence + 1); 

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

    public function showDetailsRow($id)
    {
        $this->crud->hasAccessOrFail('details_row');

        $this->data['uniqid']   = uniqid();
        $period                 = $this->crud->model::where('id', $id)->first();
        $this->data['entry']    = $period;
        $this->data['department_id']    = $period->department_id;
        $this->data['items']    = $this->crud->model::where('department_id', $period->department_id)->orderBy('sequence', 'asc')->get();
        $this->data['crud']     = $this->crud;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('crud::period.details_row', $this->data);
    }

    public function updateSequence () 
    {   
        $ids = request()->sequence; 
        // dd($ids);
        $array = [];
        $flagError = false;
        for($i = 0; $i < count($ids); $i++) {
            $model = $this->crud->model::where('id', $ids[$i])->first();
            $model->sequence = $i + 1;
            if($model->save()) {
                $flagError = false;
            } else {
                $flagError = true;
            }
        }

        if($flagError) {
            return 'Error Update';
            // \Alert::success("Error Saving")->flash();
            // return redirect()->back()->withInput($request->input());
        }
        return 'OK';
        // \Alert::success("Succesfully Update")->flash();
        // return redirect()->back()->withInput($request->input());
    }
}
