<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ProfitsLossStatementRequest as StoreRequest;
use App\Http\Requests\ProfitsLossStatementRequest as UpdateRequest;

use App\Models\ProfitsLossStatement;

class ProfitsLossStatementCrudController extends CrudController
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
        $this->crud->setModel('App\Models\ProfitsLossStatement');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/profits-loss-statement');
        $this->crud->setEntityNameStrings('Profits Loss Statement', 'Profits Loss Statements');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        $this->crud->setListView('profitsAndLossStatements');

        $this->crud->addField([
            'label' => '',
            'type' => 'profitsAndLossStatement.profitsAndLossStatementScript',
            'name' => 'profitsAndLossStatementScript'
        ]);

        $this->crud->addField([
            'label'             => 'Name',
            'type'              => 'text',
            'name'              => 'name',
            'attributes'        => [
                'id' => 'name'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 name',
            ]
        ]);

        $this->crud->addField([
            'label'             => 'Code',
            'type'              => 'text',
            'name'              => 'code',
            'attributes'        => [
                'id' => 'code',
                'placeholder' => '(optional)'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 code',
            ]
        ]);

        $this->crud->addField([
            'label'             => 'Expenses',
            'type'              => 'checkbox',
            'name'              => 'is_expenses',
            'attributes'        => [
                'id' => 'expenses'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-xs-12 expenses'
            ],
        ]); 

        $this->crud->addField([
            'label'             => 'Group or Account?',
            'type'              => 'select_from_array',
            'name'              => 'hierarchy_type',
            'options'           => [
                'group'   => 'Group', 
                'account' => 'Account'
            ],
            'attributes'        => [
                'id' => 'hierarchy-type'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-4 col-md-6 hierarchy-type'
            ]
        ]);

        $this->crud->addField([
            'label'             => 'Group',
            'type'              => 'profitsAndLossStatement.profit_group_select',
            'name'              => 'group_id',
            'entity'            => 'profitsLossStatement',
            'attribute'         => 'name',
            'model'             => 'App\Models\ProfitsLossStatement',
            'attributes'        => [
                'id' => 'group'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-8 col-md-6 group'
            ],
            'allows_null'       => true
        ]);

        $this->crud->addField([
            'label'             => 'Tax Code',
            'type'              => 'select_from_array',
            'name'              => 'tax_code',
            'options'           => [
                '1'   => 'VAT 0%',
            ],
            'attributes'        => [
                'id' => 'tax-code'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-lg-5 col-md-6 tax-code d-none'
            ],
            'allows_null'       => true
        ]);
     
        $this->crud->denyAccess(['delete']);
    }

    public function deleteTree ($id)
    {
        // dd($id);
        $delete = ProfitsLossStatement::where('id', $id)->orWhere('group_id', $id)->delete();
        if($delete) {
            \Alert::success('Successfully Deleted')->flash();
        } else {
            \Alert::success('Unable to delete, Please try again!')->flash();
        }
        return redirect()->back();
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
