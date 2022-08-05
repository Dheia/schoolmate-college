<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PermissionRequest as StoreRequest;
use App\Http\Requests\PermissionRequest as UpdateRequest;
use Config;



/**
 * Class PermissionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PermissionCrudController extends CrudController
{   
    public function setup()
    {
        
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Permission');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/permission');
        $this->crud->setEntityNameStrings('permission', 'permissions');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in PermissionRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        // $this->crud->setListView('permission.list');
        // $this->crud->denyAccess('update');

        $role_model = config('permission.models.role');
        $permission_model = config('permission.models.permission');

        $this->crud->removeColumn('name');

        $this->crud->addColumn([ // n-n relationship (with pivot table)
            'label'     => trans('backpack::permissionmanager.roles_have_permission'),
            'type'      => 'select_multiple',
            'name'      => 'roles',
            'entity'    => 'roles',
            'attribute' => 'name',
            'model'     => $role_model,
            'pivot'     => true,
        ]);

        $this->crud->addField([
            'name' => 'name',
            'label' => 'Action',
            'type' => 'checklist',
            'entity'    => 'action',
            'attribute' => 'name',
            'model'     => "App\Models\Action",
            'class' => 'col-md-3',
            'wrapperAttributes' => [
                'class' => 'col-md-4'
            ]
            // 'pivot'     => true,
        ]);

        $this->crud->addField([
            'name'  => 'page_name',
            'label' => 'Slug',
            'type'  => 'select_from_array',
            'options' => $this->pageCrud(), 
            'wrapperAttributes' => [
                'class' => 'col-md-8'
            ]   
        ]);

        $this->crud->addField([
            'label'     => trans('backpack::permissionmanager.roles'),
            'type'      => 'checklist',
            'name'      => 'roles',
            'entity'    => 'roles',
            'attribute' => 'name',
            'model'     => $role_model,
            'pivot'     => true,
        ]);

        $this->crud->addColumn([
            'name'  => 'page_name',
            'label' => 'Slug',
            'type'  => 'text',
        ]);

        $this->crud->removeField('guard_name');
        $this->crud->removeColumn('guard_name');

        if (!config('backpack.permissionmanager.allow_permission_create')) {
            $this->crud->denyAccess('create');
        }
        if (!config('backpack.permissionmanager.allow_permission_update')) {
            $this->crud->denyAccess('update');
        }
        if (!config('backpack.permissionmanager.allow_permission_delete')) {
            $this->crud->denyAccess('delete');
        }

        $this->crud->addClause('groupBy', 'page_name');
    }


    private function pageCrud ()
    {
        $routeCollection = array_map(function (\Illuminate\Routing\Route $route) 
                            { return $route->action; }, (array) \Route::getRoutes()->getIterator());

        $pages = [];

        foreach ($routeCollection as $route) {
            if (isset($route['as']) && strpos($route['as'], 'crud') !== false) {

                $extract1 = substr($route['as'], 5);
                $extract2 = strpos($extract1, '.');
                $slug = substr($extract1, 0, $extract2);

                $pages[$slug] = title_case(str_slug($slug, ' '));
            }
        }
        return $pages;
    }


    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        // dd($request);
        $flagError = false;

        foreach($request->name as $action) {
            // dd($action);
            $action_name = \App\Models\Action::where('id', $action)->first()->name;

            $permission = new \App\Models\Permission;
            $permission->name = strtolower($action_name);
            $permission->page_name = $request->page_name;
            
            if($permission->save()) {
                $flagError = false;
            } else {
                \Alert::success('Erro Saving: ' . $action_name . ' ' . $request->page_name)->flash();
                return redirect('admin/permission');
            }
        }

        \Alert::success('Successfully Adding Item')->flash();
        return redirect('admin/permission');
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
