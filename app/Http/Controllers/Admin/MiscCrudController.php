<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\MiscRequest as StoreRequest;
use App\Http\Requests\MiscRequest as UpdateRequest;

class MiscCrudController extends CrudController
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
        $this->crud->setModel('App\Models\Misc');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/misc');
        $this->crud->setEntityNameStrings('Misc', 'Miscellaneous');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();
        $this->crud->child_resource_included = ['select' => false, 'number' => false];

        $this->crud->addField([
            'label' => 'Grade',
            'type' => 'select',
            'name' => 'grade_year_id',
            'entity' => 'grade_year',
            'attribute' => 'year',
            'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
            ],
            'model' => 'App\Models\YearManagement'
        ]);

        $this->crud->addField([
            'name' => 'miscellaneous',
            'label' => 'misc',
            'type' => 'child_misc',
            'entity_singular' => 'Add Misc', // used on the "Add X" button
            'columns' => [
                [
                    'label' => 'Code',
                    'type' => 'child_text',
                    'name' => 'code',],
                [
                    'label' => 'Description',
                    'type' => 'child_text',
                    'name' => 'description',
                ],
                [
                    'label' => 'Amount',
                    'type' => 'child_number',
                    'name' => 'amount',
                    'attributes' => [
                        'onkeyup' => 'UpdateTotal()',
                        'name' => 'currency',
                        'id' => 'misc_amount'
                    ]

                ],
            ],
            'max' => 12, // maximum rows allowed in the table
            'min' => 1 // minimum rows allowed in the table
        ]);

        
        $this->crud->addField(
            [
                'name' => 'name',
                'label' => 'Miscellaneous Name',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ], 
            'update/create/both'
        );

        $this->crud->addField([
            'label' => 'Payment Type',
            'type' => 'select',
            'name' => 'commitment_payment_id',
            'entity' => 'commitment_payment',
            'attribute' => 'name',
            'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
            ],
            'model' => 'App\Models\CommitmentPayment'
        ]);

        $this->crud->addField(
            [
                'name' => 'payment_scheme',
                'label' => 'Payment Scheme',
                'type' => 'child_payment_scheme',
                'entity_singular' => 'Payment Scheme', // used on the "Add X" button
                'columns' => [
                     [
                        'label' => 'Scheme Date',
                        'type' => 'child_date',
                        'name' => 'payment_scheme_date',
                        'ng-model' => 'payment_scheme_date',
                        'attributes' => [
                            'as-date' => null,
                            // 'date' => 'dd-MM-yyyy'
                            // 'ng-bind' => "formatDate(<% payment_scheme_date %>) |  date:'MM/dd/yyyy'"
                        ]
                    ],
                    [
                        'label' => 'Amount',
                        'type' => 'child_number',
                        'name' => 'payment_scheme_amount',
                        'attributes' => [
                            'onkeyup' => 'UpdateTotal2()',
                            'id' => 'payment_scheme_amount',
                            // 'ng-model' => 'payment_scheme_amount'
                        ]
                    ]
                ]
        ]);

        $this->crud->addField(
            [
                'label' => "School Year",
                'type' => 'select2',
                'name' => 'schoolyear_id', // the db column for the foreign key //schoolyearid
                'entity' => 'schoolyear', // the method that defines the relationship in your Model
                'attribute' => 'schoolYear', // foreign key attribute that is shown to user
                'model' => "App\Models\SchoolYear",
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
            ],   
            'update/create/both'
        );


        
        // ------ CRUD COLUMNS
        // $this->crud->addColumn(); // add a single column, at the end of the stack
        // $this->crud->addColumns(); // add multiple columns, at the end of the stack
        // $this->crud->removeColumn('column_name'); // remove a column from the stack
        // $this->crud->removeColumns(['column_name_1', 'column_name_2']); // remove an array of columns from the stack
        // $this->crud->setColumnDetails('column_name', ['attribute' => 'value']); // adjusts the properties of the passed in column (by name)
        // $this->crud->setColumnsDetails(['column_1', 'column_2'], ['attribute' => 'value']);
        $this->crud->setColumns(['name', 'grade_year_id', 'commitment_payment_id', 'schoolyear_id']);

        $this->crud->setColumnsDetails('commitment_payment_id', 
            [
                'label' => 'Payment Type',
                'type' => 'select',
                'name' => 'commitment_payment_id',
                'entity' => 'commitment_payment',
                'attribute' => 'name',
                'model' => 'App\Models\CommitmentPayment',
            ]
        );


        $this->crud->setColumnsDetails('grade_year_id', [
            'label' => 'Grade Year',
            'type' => 'select',
            'name' => 'grade_year_id',
            'entity' => 'grade_year',
            'attribute' => 'year',
            'model' => 'App\Models\YearManagement'
        ]);

        $this->crud->setColumnsDetails('schoolyear_id', [
            'label' => 'School Year',
            'type' => 'select',
            'name' => 'schoolyear_id',
            'entity' => 'schoolyear',
            'attribute' => 'schoolYear',
            'model' => 'App\Models\SchoolYear'
        ]);

        $this->crud->addButtonFromView('line', 'Print', 'print', 'beginning');


         $this->crud->enableDetailsRow();
        $this->crud->enableExportButtons();
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

    public function showDetailsRow($id){
        $misc = $this->crud->model->find($id);
        //$data = $this->crud->model->getAttributes();
        $data = $misc->getAttributes();
        return view('MiscDetailsRow',['data'=>$data]);
    }
}
