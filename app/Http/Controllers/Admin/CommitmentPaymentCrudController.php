<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CommitmentPaymentRequest as StoreRequest;
use App\Http\Requests\CommitmentPaymentRequest as UpdateRequest;

use App\Models\PaymentMethod;

class CommitmentPaymentCrudController extends CrudController
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
        $this->crud->setModel('App\Models\CommitmentPayment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/commitment-payment');
        $this->crud->setEntityNameStrings('Commitment Payment', 'Commitment Payments');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        
        // ------ CRUD ACCESS
        // $this->crud->allowAccess(['list', 'create', 'update', 'reorder', 'delete']);
        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);
        $this->crud->denyAccess(['create', 'update', 'reorder', 'delete']);

        // ------ CRUD REORDER
        // $this->crud->enableReorder('label_name', MAX_TREE_LEVEL);
        $this->crud->addButtonFromView('line', 'activate', 'commitmentPayment.activate', 'end');
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


    public function setActive ($action, $id)
    {
        // DISABLED ALL ACTIVE
        $this->crud->model::find($id)->update(['active' => 0]);

        // SET ACTIVE TO TRUE THE GIVEN ID
        $actionMessage = '';
        if($action == 'activate')
        {
            $updateActive  = $this->crud->model::where('id', $id)->update(['active' => 1]);
            $actionMessage = 'Activated'; 
        } else 
        {
            $updateActive  = $this->crud->model::where('id', $id)->update(['active' => 0]);
            $actionMessage = 'Deactivated';
        }

        if($updateActive) {
            \Alert::success("Successfully " . $actionMessage)->flash();
            return redirect()->back();
        }
            \Alert::warning("Error " . $actionMessage . "! Please Try Again...")->flash();
            return redirect()->back();
    }
}
