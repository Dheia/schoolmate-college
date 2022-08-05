<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\OtherServiceRequest as StoreRequest;
use App\Http\Requests\OtherServiceRequest as UpdateRequest;

use QuickBooksOnline\API\Facades\Item;
use App\Http\Controllers\QuickBooks\QuickBooksOnline as QBO;
use App\Models\SchoolYear;
/**
 * Class OtherServiceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class OtherServiceCrudController extends CrudController
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

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\OtherService');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/other-service');
        $this->crud->setEntityNameStrings('Service', 'Other Services');
        

        // TODO: remove setFromDb() and manually define Fields and Columns

        // $this->crud->setFromDb();

        // add asterisk for fields that are required in OtherServiceRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');


        /*
        |--------------------------------------------------------------------------
        | QBO ITEMS
        |--------------------------------------------------------------------------
        */

        $this->qbItems = $this->getQBItems();
        // dd($this->crud->getActionMethod());
        if($this->qbItems == null) {
            \Alert::warning('Unauthorized QuickBooks')->flash();
            $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);
        }

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

    public function QBOConnect ($id)
    {
        return $this->qbo->bindChildServices($this, $id);
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
        if($model->qbo_id == null)
        {
            $model->delete();
        }
        \Alert::warning("Error Deleting This Items Is Binded To QBO");
    }
}
