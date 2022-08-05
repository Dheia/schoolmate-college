<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\BalanceSheetRequest as StoreRequest;
use App\Http\Requests\BalanceSheetRequest as UpdateRequest;

class BalanceSheetCrudController extends CrudController
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
        $this->crud->setModel('App\Models\BalanceSheet');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/balancesheet');
        $this->crud->setEntityNameStrings('balancesheet', 'balance_sheets');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        // ------ CRUD FIELDS
        // $this->crud->addField($options, 'update/create/both');
        // $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');

        $this->crud->addField([
            'label' => '',
            'type' => 'chartAccount.chartAccountScript',
            'name' => 'chartAccountScript',
        ]);

        $this->crud->addField([
            'label' => 'Name',
            'type' => 'text',
            'name' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
            ]
        ]);

        $this->crud->addField([
            'label' => 'Code',
            'type' => 'text',
            'name' => 'code',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
            ],
            'attributes' => [
                'placeholder' => '(Optional)'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Hierarchy Type',
            'type' => 'select_from_array',
            'name' => 'hierarchy_type',
            'options' => [
                'group' => 'Group', 
                'account' => 'Account'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
            ]
        ]);

        $this->crud->addField([
            'label' => 'Group',
            'type' => 'select_from_array',
            'name' => 'group_id',
            'options' => [
                1 => 'Assets', 
                2 => 'Liabilites',
                3 => 'Equity'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
            ]
        ]);

        $this->crud->addField([
            'label' => 'Tax Code',
            'type' => 'select_from_array',
            'name' => 'tax_code',
            'options' => [
                0 => 'VAT 0%'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12 tax-code'
            ],
            'allows_null' => true
        ]);

        $this->crud->addField([
            'label' => 'Control Account',
            'type' => 'checkbox',
            'name' => 'is_control_account',
            'wrapperAttributes' => [
                'class' => 'form-group col-xs-12 is-control-account'
            ],
            'attributes' => [
                'id' => 'is-control-account'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Made Up',
            'type' => 'select_from_array',
            'name' => 'made_up_id',
            'options' => [
                1 => 'Cash Accounts',
                2 => 'Customers',
                3 => 'Suppliers',
                4 => 'Fixed Assets',
                5 => 'Intangible Assets',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 made-up'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Starting balance as at 01/01/17',
            'type' => 'checkbox',
            'name' => 'is_starting_balance',
            'wrapperAttributes' => [
                'class' => 'form-group is-starting-balance col-xs-12'
            ],
            'attributes' => [
                'id' => 'is-starting-balance'
            ]
        ]);

        $this->crud->addField([
            'label' => '',
            'type' => 'select_from_array',
            'name' => 'starting_balance_type_id',
            'options' => [
                1 => 'Debit',
                2 => 'Credit',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-1 starting-balance-type'
            ]
        ]);

        $this->crud->addField([
            'label' => '',
            'type' => 'text',
            'name' => 'starting_balance',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-2 starting-balance'
            ]
        ]);

        
        // ------ CRUD COLUMNS
        // $this->crud->addColumn(); // add a single column, at the end of the stack
        // $this->crud->addColumns(); // add multiple columns, at the end of the stack
        // $this->crud->removeColumn('column_name'); // remove a column from the stack
        // $this->crud->removeColumns(['column_name_1', 'column_name_2']); // remove an array of columns from the stack
        // $this->crud->setColumnDetails('column_name', ['attribute' => 'value']); // adjusts the properties of the passed in column (by name)
        // $this->crud->setColumnsDetails(['column_1', 'column_2'], ['attribute' => 'value']);

        // ------ CRUD BUTTONS
        // possible positions: 'beginning' and 'end'; defaults to 'beginning' for the 'line' stack, 'end' for the others;
        // $this->crud->addButton($stack, $name, $type, $content, $position); // add a button; possible types are: view, model_function
        // $this->crud->addButtonFromModelFunction($stack, $name, $model_function_name, $position); // add a button whose HTML is returned by a method in the CRUD model
        // $this->crud->addButtonFromView($stack, $name, $view, $position); // add a button whose HTML is in a view placed at resources\views\vendor\backpack\crud\buttons
        // $this->crud->removeButton($name);
        // $this->crud->removeButtonFromStack($name, $stack);
        // $this->crud->removeAllButtons();
        // $this->crud->removeAllButtonsFromStack('line');

        // ------ CRUD ACCESS
        // $this->crud->allowAccess(['list', 'create', 'update', 'reorder', 'delete']);
        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);

        // ------ CRUD REORDER
        // $this->crud->enableReorder('label_name', MAX_TREE_LEVEL);
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('reorder');

        // ------ CRUD DETAILS ROW
        // $this->crud->enableDetailsRow();
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('details_row');
        // NOTE: you also need to do overwrite the showDetailsRow($id) method in your EntityCrudController to show whatever you'd like in the details row OR overwrite the views/backpack/crud/details_row.blade.php

        // ------ REVISIONS
        // You also need to use \Venturecraft\Revisionable\RevisionableTrait;
        // Please check out: https://laravel-backpack.readme.io/docs/crud#revisions
        // $this->crud->allowAccess('revisions');

        // ------ AJAX TABLE VIEW
        // Please note the drawbacks of this though:
        // - 1-n and n-n columns are not searchable
        // - date and datetime columns won't be sortable anymore
        // $this->crud->enableAjaxTable();

        // ------ DATATABLE EXPORT BUTTONS
        // Show export to PDF, CSV, XLS and Print buttons on the table view.
        // Does not work well with AJAX datatables.
        // $this->crud->enableExportButtons();

        // ------ ADVANCED QUERIES
        // $this->crud->addClause('active');
        // $this->crud->addClause('type', 'car');
        // $this->crud->addClause('where', 'name', '==', 'car');
        // $this->crud->addClause('whereName', 'car');
        // $this->crud->addClause('whereHas', 'posts', function($query) {
        //     $query->activePosts();
        // });
        // $this->crud->addClause('withoutGlobalScopes');
        // $this->crud->addClause('withoutGlobalScope', VisibleScope::class);
        // $this->crud->with(); // eager load relationships
        // $this->crud->orderBy();
        // $this->crud->groupBy();
        // $this->crud->limit();
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
