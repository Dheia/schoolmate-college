<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CashAccountRequest as StoreRequest;
use App\Http\Requests\CashAccountRequest as UpdateRequest;
use Illuminate\Http\Request as Request;

use App\Models\CashAccount;
use App\Models\ReceiveMoney;
use App\Models\SpendMoney;
use App\Models\transferMoney;
use App\PaymentHistory;

/**
 * Class Cash_accountCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CashAccountCrudController extends CrudController
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
        $this->crud->setModel('App\Models\CashAccount');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/cash-account');
        $this->crud->setEntityNameStrings('Cash Account', 'Cash Accounts');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        // ------ CRUD COLUMNS
        // $this->crud->addColumn(); // add a single column, at the end of the stack
        // $this->crud->addColumns(); // add multiple columns, at the end of the stack

        $this->crud->addColumn([ 
            'name' => 'cleared_balance', // The db column name
            'label' => "Cleared balance", // Table column heading
            'type' => 'cashAccount.cleared_balanced'
        ]);

        $this->crud->addColumn([ 
            'name' => 'available_credit', // The db column name
            'label' => "Available credit", // Table column heading
            'type' => 'cashAccount.available_credit'
        ]);

        // $this->crud->removeColumn('column_name'); // remove a column from the stack
        // $this->crud->removeColumns(['column_name_1', 'column_name_2']); // remove an array of columns from the stack
        // $this->crud->setColumnDetails('column_name', ['attribute' => 'value']); // adjusts the properties of the passed in column (by name)
        // $this->crud->setColumnsDetails(['column_1', 'column_2'], ['attribute' => 'value']);


        $this->crud->removeColumns([
                        'credit_limit', 
                        'currency_id', 
                        'currency', 
                        'is_bank_maintained', 
                        'is_starting_balance', 
                        'starting_balance', 
                        'inactive',
                    ]);

        // ------ CRUD FIELDS
        // $this->crud->addField($options, 'update/create/both');
        // $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');

        $this->crud->addField([
            'name' => 'cashAccountScript',
            'label' => '',
            'type' => 'cashAccount.cashAccountScript'
        ]);

        $this->crud->addField([ // Text
            'name' => 'name',
            'label' => "Name",
            'type' => 'text',
             'wrapperAttributes' => [
               'class' => 'form-group col-md-4 name'
             ]
        ]); 

        $this->crud->addField([ // Text
            'name' => 'code',
            'label' => "Code",
            'type' => 'text',
             'wrapperAttributes' => [
               'class' => 'form-group col-md-4 code'
             ]
        ]);

        $this->crud->addField([ // Text
            'name' => 'credit_limit',
            'label' => "Credit Limit",
            'type' => 'number',
             'wrapperAttributes' => [
               'class' => 'form-group col-md-4 credit-limit d-none'
             ],
             'attributes' => [
                'placeholder' => 'optional'
             ]
        ]);

        $this->crud->addField([ // Text
            'name' => 'currency_id',
            'label' => "Currency ID",
            'type' => 'hidden',
        ]);

        $this->crud->addField([ // Text
            'name' => 'currency',
            'label' => "Currency",
            'type' => 'hidden',
        ]);

        $this->crud->addField([ // Text
            'name' => 'select_currency',
            'label' => "Currency",
            'type' => 'cashAccount.selectCurrency',
             'wrapperAttributes' => [
               'class' => 'form-group col-md-5 currency'
             ]
        ])->afterField('currency_id');

        $this->crud->addField([   // Checkbox
            'name' => 'is_starting_balance',
            'label' => 'Starting balance as at 01/01/17',
            'type' => 'checkbox',
            'attributes' => [
                'id' => 'is_starting_balance'
            ]
        ])->afterField('is_bank_maintained');

        $this->crud->addField([   // Checkbox
            'name' => 'starting_balance',
            'label' => "Starting Balance",
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 starting-balance d-none'
            ]
        ]); 


        $this->crud->addField([
            'name' => 'info',
            'type' => 'custom_html',
            'value' => 'Enter starting balance as per bank statement. Pending deposits and pending withdrawals at start date need to be entered individually.',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-8 desc d-none'
            ]
        ])->afterField('starting_balance');

        $this->crud->addField([   // Checkbox
            'name' => 'is_bank_maintained',
            'label' => 'This account is maintained by a bank or other financial institution',
            'type' => 'checkbox',
            'attributes' => [
                'id' => 'is_bank_maintained'
            ]
        ]); 

        $this->crud->addField([   // Checkbox
            'name' => 'inactive',
            'label' => 'Inactive',
            'type' => 'checkbox'
        ]); 

        // add asterisk for fields that are required in Cash_accountRequest
        // $this->crud->setRequiredFields(StoreRequest::class, 'create');
        // $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        // ------ CRUD BUTTONS
        // possible positions: 'beginning' and 'end'; defaults to 'beginning' for the 'line' stack, 'end' for the others;
        // $this->crud->addButton($stack, $name, $type, $content, $position); // add a button; possible types are: view, model_function
        $this->crud->addButtonFromView('top', 'receive_money', 'receive_money_button');
        $this->crud->addButtonFromView('top', 'spend_money', 'spend_money_button');
        $this->crud->addButtonFromView('top', 'transfer_money', 'transfer_money_button');
        // $this->crud->addButtonFromModelFunction($stack, $name, $model_function_name, $position); // add a button whose HTML is returned by a method in the CRUD model
        // $this->crud->addButtonFromView($stack, $name, $view, $position); // add a button whose HTML is in a view placed at resources\views\vendor\backpack\crud\buttons
        // $this->crud->removeButton($name);
        // $this->crud->removeButtonFromStack($name, $stack);
        // $this->crud->removeAllButtons();
        // $this->crud->removeAllButtonsFromStack('line');

        // ------ CRUD ACCESS
        // $this->crud->allowAccess(['list', 'create', 'update', 'reorder', 'delete']);
        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);
        $this->crud->denyAccess(['reorder', 'delete']);

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
        // $this->crud->addClause('where', 'name', '=', 'car');
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

    public function clearedBalance ($id) 
    {
        $paymentHistories = PaymentHistory::where('payment_type_id', $id)->orderBy('updated_at', 'desc')->get();
        $receiveMoneys = ReceiveMoney::where('received_in_id', $id)->orderBy('updated_at', 'desc')->get();
        $spendMoneys = SpendMoney::where('paid_from_id', $id)->get();
        $transferMoneys = TransferMoney::where('paid_from_id', $id)->orWhere('received_in_id', $id)->orderBy('updated_at', 'desc')->get();
        $pathId = $id;
        // dd($spendMoney);
        return view('clearedBalance', compact('paymentHistories', 'receiveMoneys', 'spendMoneys', 'transferMoneys', 'pathId'));
    }

    public function findCashAccount($id, Request $request) {
        $account;
        if(isset($request->active)) {
            $account = CashAccount::select('id', 'is_bank_maintained', 'is_starting_balance', 'name', 'code', 'currency')
                            ->where('inactive', $request->active)
                            ->find($id);
        } else {
            $account = CashAccount::select('id', 'is_bank_maintained', 'is_starting_balance', 'name', 'code', 'currency')->find($id);
        }

        return response()->json($account);
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
