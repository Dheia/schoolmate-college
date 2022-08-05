<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\RoleRequest as StoreRequest;
use App\Http\Requests\RoleRequest as UpdateRequest;

/**
 * Class RoleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class RoleCrudController extends CrudController
{
    public function setup()
    {
        
        $role_model = config('permission.models.role');
        $permission_model = config('permission.models.permission');

        $this->crud->setModel('App\Models\Role');
        $this->crud->setEntityNameStrings(trans('backpack::permissionmanager.role'), trans('backpack::permissionmanager.roles'));
        $this->crud->setRoute(config('backpack.base.route_prefix').'/role');

        $this->crud->setColumns([
            [
                'name'  => 'name',
                'label' => trans('backpack::permissionmanager.name'),
                'type'  => 'text',
            ],
            [
                // n-n relationship (with pivot table)
                'label'     => ucfirst(trans('backpack::permissionmanager.permission_plural')),
                'type'      => 'select_multiple_roles_custom',
                'name'      => 'permissions', // the method that defines the relationship in your Model
                'entity'    => 'permissions', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     => $permission_model, // foreign key model
                'pivot'     => true, // on create&update, do you need to add/delete pivot table entries?
            ],
        ]);

        $this->crud->addField([
            'name'  => 'name',
            'label' => trans('backpack::permissionmanager.name'),
            'type'  => 'text',
        ]);


        $this->crud->addField([
            'label'     => ucfirst(trans('backpack::permissionmanager.permission_plural')),
            'type'      => 'checklist_with_label',
            'name'      => 'permissions',
            'entity'    => 'permissions',
            'attribute' => 'name',
            'model'     => 'App\Models\Permission',
            'pivot'     => true,
        ]);


        if (config('backpack.permissionmanager.allow_role_create') == false) {
            $this->crud->denyAccess('create');
        }
        if (config('backpack.permissionmanager.allow_role_update') == false) {
            $this->crud->denyAccess('update');
        }
        if (config('backpack.permissionmanager.allow_role_delete') == false) {
            $this->crud->denyAccess('delete');
        }
    }

    public function store(StoreRequest $request)
    {
        //otherwise, changes won't have effect
        \Cache::forget('spatie.permission.cache');

        return parent::storeCrud();
    }

    public function update(UpdateRequest $request)
    {
        //otherwise, changes won't have effect
        \Cache::forget('spatie.permission.cache');

        return parent::updateCrud();
    }

    public function destroy ($id)
    {
        $model = $this->crud->model::findOrFail($id);
        $model->delete();
    }
}
