<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\DiscrepancyRequest as StoreRequest;
use App\Http\Requests\DiscrepancyRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class DiscrepancyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */

// Chart of Accounts
use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Account;
use App\Http\Controllers\QuickBooks\QuickBooksOnline as QBO;

class DiscrepancyCrudController extends CrudController
{
    private $qbItems = [];

    public function setup()
    {
        
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Discrepancy');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/discrepancy');
        $this->crud->setEntityNameStrings('discrepancy', 'discrepancies');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in DiscrepancyRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        // if($this->crud->getActionMethod() == "create" || $this->crud->getActionMethod() == "edit") {
        //     $this->qbItems = $this->getQBItems();

        //     if($this->qbItems == null) {
        //         \Alert::warning('Unauthorized QuickBooks')->flash();
        //         $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);
        //     }
        // }
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
}
