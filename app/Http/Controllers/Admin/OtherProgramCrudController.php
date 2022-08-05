<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OtherProgramRequest as StoreRequest;
use App\Http\Requests\OtherProgramRequest as UpdateRequest;


// Chart of Accounts
use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Account;
use App\Http\Controllers\QuickBooks\QuickBooksOnline as QBO;

// MODELS
use App\Models\SchoolYear;

class OtherProgramCrudController extends CrudController
{
    private $qbItems = [];

    private function getQBItems() 
    {
        $qbo =  new QBO;
        $qbo->initialize();
        if($qbo->dataService() === null)
        {
            $status  = "ERROR";
            $message = "Unauthorized QuickBooks";
            return null;
        }

        $name = "Mandatory Fee " .  request()->school_year_id;
        // dd( $name);
      
        $chartOfAccounts = $qbo->dataService->Query("SELECT Id, Name FROM Item maxresults 1000");
        $chartOfAccounts = $chartOfAccounts == null ? [] : $chartOfAccounts;
        
        $error = $qbo->dataService->getLastError();
        if ($error) {
            echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
            echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
            echo "The Response message is: " . $error->getResponseBody() . "\n";
            \Alert::warning($error->getResponseBody())->flash();
            return redirect()->back();
        }
        $collection = collect($chartOfAccounts);
        $collection = $collection->pluck('Name','Id');

        return $collection ?? [];
    }

    public function setup()
    {

        $this->qbItems = $this->getQBItems();

        if($this->qbItems == null) {
            \Alert::warning('Unauthorized QuickBooks')->flash();
            $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);
        }

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

        $this->crud->setModel('App\Models\OtherProgram');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/other-programs');
        $this->crud->setEntityNameStrings('Other Program', 'Other Programs');

        // $this->crud->setFromDb();
        
        /*
        |--------------------------------------------------------------------------
        | FIELDS
        |--------------------------------------------------------------------------
        */
        $this->crud->addField([
            'label' => 'QB Map',
            'type'  => 'select_from_array',
            'name'  => 'qbo_map',
            'options'=> $this->qbItems,
        ]);

        $this->crud->addField([
            'label' => 'Name',
            'type' => 'text',
            'name' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 required'
            ]
        ]);

        $this->crud->addField([
            'label' => 'School Year',
            'type' => 'select',
            'name' => 'school_year_id',
            // 'options' => SchoolYear::active()->pluck('schoolYear', 'id'),
            'entity' => 'schoolYear',
            'attribute' => 'schoolYear',
            'model' => 'App\Models\SchoolYear',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 required'
            ],
        ]);

        $this->crud->addField([
            'label' => 'Amount',
            'type' => 'number',
            'name' => 'amount',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-md-6 required'
            ]
        ]);

        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
            'label' => 'Name',
            'type' => 'text',
            'name' => 'name',
        ]);

        $this->crud->addColumn([
            'label' => 'Amount',
            'type' => 'text',
            'name' => 'amount',
        ]);

        $this->crud->addColumn([
            'label' => 'School Year',
            'type' => 'select',
            'name' => 'school_year_id',
            'entity' => 'schoolYear',
            'attribute' => 'schoolYear',
            'model' => 'App\Models\SchoolYear',
        ]);

        $this->crud->orderBy('created_at', 'DESC');
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
    
      // -----------------------------
     // ------ CUSTOM FUNCTION ------ 
    // -----------------------------

    public function QBOConnect ($id)
    {
        if($this->crud->hasAccess('list')) {
            return $this->qbo->bindChildServices($this, $id);
        }
    }
    
    public function getOtherPrograms () 
    {   
        $other_programs = $this->crud->model::all();
        return response()->json($other_programs);
    }

}
