<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PosTransactionRequest as StoreRequest;
use App\Http\Requests\PosTransactionRequest as UpdateRequest;

/**
 * Class PosTransactionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PosTransactionCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PosTransaction');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/pos-transaction');
        $this->crud->setEntityNameStrings('Transaction', 'Transactions');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in PosTransactionRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->addField([
            'name' => 'items',
            'label' => 'Items',
            'type' => 'table',
            'entity_singular' => 'Item', // used on the "Add X" button
            'columns' => [
                'name' => 'Name',
                'desc' => 'Description',
                'price' => 'Price'
            ],
            'max' => 5, // maximum rows allowed in the table
            'min' => 1, // minimum rows allowed in the table
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
