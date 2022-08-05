<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SchoolStoreInventoryRequest as StoreRequest;
use App\Http\Requests\SchoolStoreInventoryRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

// MODELS
use App\Models\SchoolStoreInventory;
use App\Models\SchoolStoreCategory;
use App\Models\SchoolStoreInventoryQuantity;
use App\Models\SchoolStoreInventoryQuantityLog;
use Carbon\Carbon;

/**
 * Class SchoolStoreInventoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SchoolStoreInventoryCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SchoolStoreInventory');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/schoolstore/inventory');
        $this->crud->setEntityNameStrings('Inventory', 'Inventories');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in SchoolStoreInventoryRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

         // COLUMNS
        $this->crud->addColumn([
            'label' => "Image", // Table column heading
            'type' => "image",
            'name' => 'image', // the column that contains the ID of that connected entity;
        ]);

        $this->crud->addColumn([
            'label' => "Category", // Table column heading
            'type' => "select",
            'name' => 'school_store_category_id', // the column that contains the ID of that connected entity;
            'entity' => 'schoolStoreCategory', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\Models\SchoolStoreCategory", // 
        ]);
        
        // FIELDS
        $this->crud->addField([
            'label' => 'Image',
            'type' => 'image',
            'name' => 'image',
            'crop' => true,
            'upload' => true,
            'aspect_ratio' => 1,
        ]);

        $this->crud->addField([
            'label' => "Category",
            'type' => 'select',
            'name' => 'school_store_category_id', // the db column for the foreign key
            'entity' => 'schoolStoreCategory', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\SchoolStoreCategory" // for
        ]);

        $this->crud->addField([
            'name' => 'quantity_on_hand',
            'type' => 'number'
        ], 'create');

        // CUSTOM BUTTONS
        $this->crud->addButtonFromView('line', 'Quantity', 'schoolStore.addQuantity', 'end');
        $this->crud->addButtonFromView('line', 'Favorite', 'schoolStore.isFavorite', 'end');
        $this->crud->addButtonFromView('top', 'start_inventory_today', 'schoolStore.add_start_inventory_for_today', 'end');
        $this->crud->addButtonFromView('top', 'end_inventory_today', 'schoolStore.add_end_inventory_for_today', 'end');
        
        // ADDITIONAL 
        $this->crud->removeColumns(['cost_price','barcode','description']);

        $this->crud->enableAjaxTable();
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


    public function startInventoryForToday ()
    {
        $isExistToday = SchoolStoreInventoryQuantity::whereDate('created_at', Carbon::today())->exists();
        if($isExistToday) {
            \Alert::warning("Error, You have already set the start inventory")->flash();
            return redirect()->back();
        }

        $items = $this->crud->model::all();

        $data  = []; 

        foreach ($items as $key => $value) 
        {
            $data[$key]['code']           = $value->code;
            $data[$key]['start_quantity'] = $value->quantity_on_hand;
            $data[$key]['start_date']     = Carbon::now()->format('Y-m-d h:i:s');
        }

        $insertedItems                        = new SchoolStoreInventoryQuantity;
        $insertedItems->items                 = json_encode($data);
        $insertedItems->is_start_quantity_set = true;
        $insertedItems->created_by            = backpack_auth()->user()->id;

        if($insertedItems->save()) {
            \Alert::success("Successfully Start Quantity For Today")->flash();
            return redirect()->back();
        }

        \Alert::warning("Error, Please Try Again...")->flash();
        return redirect()->back();
    }

    public function endInventoryForToday ()
    {
        $isExistToday = SchoolStoreInventoryQuantity::whereDate('created_at', Carbon::today())
                                             ->exists();
        if($isExistToday) {
            $items = $this->crud->model::all();
            $itemQuantities = SchoolStoreInventoryQuantity::whereDate('created_at', Carbon::today())
                                                   ->first();
            $data  = []; 

            foreach ($items as $keyItem => $item) 
            {
                foreach (json_decode($itemQuantities->items) as $keyItemQuantity => $itemQuantity) {
                    if($itemQuantity->code == $item->code) {
                        $data[$keyItem]['code']           = $itemQuantity->code;
                        $data[$keyItem]['start_quantity'] = $itemQuantity->start_quantity;
                        $data[$keyItem]['end_quantity']   = $item->quantity_on_hand;
                        $data[$keyItem]['start_date']     = $itemQuantity->start_date;
                        $data[$keyItem]['end_date']       = Carbon::now()->format('Y-m-d h:i:s');
                    }
                }
            }

            $insertedItems = SchoolStoreInventoryQuantity::whereDate('created_at', Carbon::today())->update(["items" => json_encode($data), "is_end_quantity_set" => true]);

            if($insertedItems) {
                \Alert::success("Successfully Set End Quantity For Today")->flash();
                return redirect()->back();
            }

            \Alert::warning("Error, Please Try Again...")->flash();
            return redirect()->back();
        }

        \Alert::warning("You have not set start inventory")->flash();
        return redirect()->back();
    }

    public function addQuantity ($id, Request $request) 
    {

        $item = SchoolStoreInventory::findOrFail($id);
        
        if($item) {
        
            $itemInventoryQtyLog                     = new SchoolStoreInventoryQuantityLog;
            $itemInventoryQtyLog->quantity           = $request->quantity;
            $itemInventoryQtyLog->item_inventory_id  = $id;
            $itemInventoryQtyLog->description        = $request->description;
            $itemInventoryQtyLog->created_by_user_id = backpack_auth()->user()->id;
            $saveItemQtyLog                          = $itemInventoryQtyLog->save();

            if($saveItemQtyLog) {
                $item->quantity_on_hand += $request->quantity;
                if( $item->save() ) {
                    \Alert::success("Successfully Updated Quantity")->flash();
                    return redirect()->back();
                } else {
                    \Alert::warning("Error Updating")->flash();
                    return redirect()->back();
                }
            }
        }
    }

    public function setFavorite ($id)
    {
        $item = SchoolStoreInventory::findOrFail($id);

        if($item) {
            $item->is_favorite = $item->is_favorite ? 0 : 1;
            if( $item->save() ) {
                \Alert::success("Successfully Updated")->flash();
                return redirect()->back();
            } else {
                \Alert::warning("Error Updating")->flash();
                return redirect()->back();
            }
        }
    }
}
