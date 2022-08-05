<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ReceiveMoneyRequest as StoreRequest;
use App\Http\Requests\ReceiveMoneyRequest as UpdateRequest;

class ReceiveMoneyCrudController extends CrudController
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
        $this->crud->setModel('App\Models\ReceiveMoney');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/receive-money');
        $this->crud->setEntityNameStrings('Receive Money', 'Receipt');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();


        $this->crud->setCreateView('list');
        $this->crud->setEditView('edit');

        $this->crud->addField([
            'name' => 'receiveMoneyScript',
            'label' => '',
            'type' => 'receiveMoney.receiveMoneyScript'
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
            'wrapperAttributes' => [
                'class' => 'form-group col-md-7'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Received In',
            'name' => 'received_in_id',
            'type' => 'select',
            'entity' => 'cashAccount', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => 'App\Models\CashAccount',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Status',
            'name' => 'status',
            'type' => 'select_from_array',
            'options' => ['cleared' => 'Cleared', 'pending' => 'Pending'],
            'attributes' => [
                'id' => 'status'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group status col-md-4'
            ],
            'allows_null' => true
        ]);

        $this->crud->addField([
            'label' => '',
            'name' => 'received_date',
            'type' => 'date',
            'wrapperAttributes' => [
                'class' => 'form-group received-date col-md-4'
            ],
            'allows_null' => true
        ]);

        $this->crud->addField([
            'label' => 'Payer',
            'name' => 'payer',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Description',
            'name' => 'description',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField(
            [   // Table
                'name' => 'accounts',
                'label' => '',
                'type' => 'receiveMoney.receiveMoneyTable',
                'entity_singular' => 'Line', // used on the "Add X" button
                'columns' => [
                    [
                        'label' => 'Account',
                        'type' => 'receiveMoney.account_select',
                        'name' => 'account',
                        'entity' => 'profitsLossStatement',
                        'attribute' => 'name',
                        'model' => 'App\Models\ProfitsLossStatement',
                        'attributes' => [
                            'class' => 'form-control col-md-12'
                        ]
                    ],
                    [
                        'label' => 'Description',
                        'type' => 'child_text',
                        'name' => 'description',
                    ],
                    [
                        'label' => 'Quantity',
                        'type' => 'child_number',
                        'name' => 'quantity',
                        'attributes' => [
                            'required' => true
                        ]
                    ],
                    [
                        'label' => 'Unit Price',
                        'type' => 'child_number',
                        'name' => 'unit_price',
                        'attributes' => [
                            'min' => 0,
                            'required' => true
                        ],
                    ],
                    [
                        'label' => 'Amount',
                        'type' => 'child_number',
                        'name' => 'amount',
                        'attributes' => [
                            'readonly' => true,
                            'ng-model' => 'amount',
                            'id' => 'amount',
                            'ng-init' => "<% columnTotal = item.unit_price * item.quantity %>",
                            'value' => "<% columnTotal %>",
                        ]
                    ],
                    [
                        'label' => 'Currency',
                        'type' => 'child_text',
                        'name' => 'currency',
                        'attributes' => [
                            'readonly' => true,
                            'class' => 'form-control ng-pristine ng-valid ng-not-empty ng-valid-min ng-touched currency'
                        ]
                    ],
                    [   
                        'label' => 'Tax',
                        'type' => 'child_select',
                        'name' => 'taxCode_id',
                        'entity' => 'receiveMoney',
                        'attribute' => 'name',
                        'model' => 'App\Models\TaxCode',
                        'attributes' => [
                            'class' => 'form-control col-md-12'
                        ]
                    ],
                    // 'level' => 'GRADE/LEVEL',
                    // 'school' => 'NAME OF SCHOOL',
                    // 'yearattended' => 'ACADEMIC YEAR ATTENDED',
                ],
                'max' => 6, // maximum rows allowed in the table
                'min' => 1 // minimum rows allowed in the table
            ],
            'update/create/both'
        );
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
