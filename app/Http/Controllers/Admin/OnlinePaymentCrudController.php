<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OnlinePaymentRequest as StoreRequest;
use App\Http\Requests\OnlinePaymentRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class OnlinePaymentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OnlinePaymentCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->denyAccess(['list', 'create', 'delete', 'edit']);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\OnlinePayment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/online-payment');
        $this->crud->setEntityNameStrings('Online Payment', 'Online Payments');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in OnlinePaymentRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->addColumn([
            'label' => 'Student No.',
            'type' => 'text',
            'name' => 'studentnumber',
            'prefix' => config('settings.schoolabbr') . ' - ',
        ]);

        $this->crud->addColumn([
            'label' => 'Amount',
            'type' => 'number',
            'name' => 'amount',
            'prefix' => 'PHP ',

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
}
