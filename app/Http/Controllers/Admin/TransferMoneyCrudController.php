<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TransferMoneyRequest as StoreRequest;
use App\Http\Requests\TransferMoneyRequest as UpdateRequest;

class TransferMoneyCrudController extends CrudController
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
        $this->crud->setModel('App\Models\TransferMoney');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/transfer-money');
        $this->crud->setEntityNameStrings('Transfer Money', 'Transfer Monies');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        $this->crud->setCreateView('list');
        $this->crud->setEditView('edit');

        $this->crud->addColumn([
            'label' => 'Paid From',
            'name' => 'paid_from_id',
            'type' => 'select',
            'entity' => 'cashAccount', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => 'App\Models\CashAccount',
        ]);

        $this->crud->addColumn([
            'label' => 'Received In',
            'name' => 'received_in_id',
            'type' => 'select',
            'entity' => 'cashAccount', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => 'App\Models\CashAccount',
        ]);

        $this->crud->addField([
            'label' => '',
            'name' => 'transferMoneyScript',
            'type' => 'TransferMoney.TransferMoneyScript'
        ]);

        $this->crud->addField([
            'label' => 'Date',
            'name' => 'date',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-5'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Referrence No.',
            'name' => 'referrence_no',
            'type' => 'number',
            'attributes' => [
                'placeholder' => '(optional)'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-7'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Paid From',
            'name' => 'paid_from_id',
            'type' => 'select',
            'entity' => 'cashAccount', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => 'App\Models\CashAccount',
            'attributes' => [
                'attr-type' => 'paid',
                'id' => 'paid-from'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-3'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Amount',
            'name' => 'paid_amount',
            'type' => 'number',
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-3'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Status',
            'name' => 'paid_from_status',
            'type' => 'select_from_array',
            'options' => [
                'Cleared' => 'Cleared',
                'Pending' => 'Pending'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-3 paid_from_status d-none'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Date',
            'name' => 'paid_date',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-3 paid_from_date d-none'
            ]
        ]);

        $this->crud->addField([
            'name' => 'clearfix',
            'type' => 'custom_html',
            'value' => '<div class="clearfix"></div>'
        ])->afterField('paid_date');

        $this->crud->addField([
            'label' => 'Received In',
            'name' => 'received_in_id',
            'type' => 'select',
            'entity' => 'cashAccount', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => 'App\Models\CashAccount',
            'attributes' => [
                'attr-type' => 'received',
                'id' => 'received-in'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-3'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Amount',
            'name' => 'receive_in_amount',
            'type' => 'number',
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-3'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Status',
            'name' => 'received_in_status',
            'type' => 'select_from_array',
            'options' => [
                'Cleared' => 'Cleared',
                'Pending' => 'Pending'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-3 received_in_status d-none'
            ],
        ]);

        $this->crud->addField([
            'label' => 'Date',
            'name' => 'received_in_date',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-3 received_in_date d-none'
            ]
        ]);
        $this->crud->denyAccess(['list']);
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
