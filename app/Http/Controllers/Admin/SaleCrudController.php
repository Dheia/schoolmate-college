<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SaleRequest as StoreRequest;
use App\Http\Requests\SaleRequest as UpdateRequest;

/**
 * Class SaleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SaleCrudController extends CrudController
{
    public function setup()
    {
        $user = \Auth::user();
        $permissions = collect($user->getAllPermissions());

        $plucked = $permissions->pluck('name');
        $this->allowed_method_access = $plucked->all();

        $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);
        $this->crud->allowAccess($this->allowed_method_access);
        
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Sale');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/sale');
        $this->crud->setEntityNameStrings('pos', 'POS');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        // add asterisk for fields that are required in SaleRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->addField([
            'name' => 'items',
            'label' => 'Item',
            'type' => 'table_pos',
            'entity_singular' => 'Items', // used on the "Add X" button
            'columns' => [
                'barcode' => 'Barcode',
                'item' => 'Item Code',
                'item_description' => 'Desc',
                'price' => 'Price',
                'quantity' => 'Qty',
                'sub' => 'Sub-Total'
            ],
            'max' => 20, // maximum rows allowed in the table
            'min' => 1, // minimum rows allowed in the table
        ]);

        $this->crud->addField([
            'name' => 'rfid_id',
            'label' => 'Scan RFID',
            'type' => 'rfid',

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
