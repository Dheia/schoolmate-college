<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\RoomRequest as StoreRequest;
use App\Http\Requests\RoomRequest as UpdateRequest;

use App\Models\AssetInventory;

/**
 * Class RoomCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class RoomCrudController extends CrudController
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
        $this->crud->setModel('App\Models\Room');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/room');
        $this->crud->setEntityNameStrings('room', 'rooms');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        // add asterisk for fields that are required in RoomRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->addField([
               'label' => "Building",
               'type' => 'select2',
               'name' => 'building_id', // the db column for the foreign key
               'entity' => 'building', // the method that defines the relationship in your Model
               'attribute' => 'name', // foreign key attribute that is shown to user
               'model' => "App\Models\Building" 
        ])->beforeField('name');

        $this->crud->addField([
            'label' => "Floor Area (in sqm)", // Table column heading
            'type' => "number",
            'name' => "size",
        ]);

        $this->crud->addField([
            'label' => "No. Of Chairs", // Table column heading
            'type' => "number",
            'name' => "chairs",
        ]);

        $this->crud->addField([
            'label' => "No. Of Tables", // Table column heading
            'type' => "number",
            'name' => "tables",
        ]);

        $this->crud->addField([
            'label' => "No. Of Air Conditioning Unit", // Table column heading
            'type' => "number",
            'name' => "aircons",
        ]);


        // COLUMNS
        $this->crud->addColumn([
            'label' => "Building", // Table column heading
            'type' => "select",
            'name' => 'building_id', // the column that contains the ID of that connected entity;
            'entity' => 'building', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\Models\Building",
        ]);

        $this->crud->addColumn([
            'label' => "Floor Area (in sqm)", // Table column heading
            'type' => "number",
            'name' => "size",
        ]);

        $this->crud->addColumn([
            'label' => "No. Of Chairs", // Table column heading
            'type' => "number",
            'name' => "chairs",
        ]);

        $this->crud->addColumn([
            'label' => "No. Of Tables", // Table column heading
            'type' => "number",
            'name' => "tables",
        ]);

        $this->crud->addColumn([
            'label' => "No. Of Air Conditioning Unit", // Table column heading
            'type' => "number",
            'name' => "aircons",
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
