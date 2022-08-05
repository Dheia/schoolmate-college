<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ItemInventoryRequest as StoreRequest;
use App\Http\Requests\ItemInventoryRequest as UpdateRequest;
use App\Models\ItemInventory;

use Illuminate\Http\Request;
use App\Models\ItemInventoryQuantityLog;
use App\Models\InventoryTransaction;
use App\Models\ItemInventoryQuantity;

use App\Models\ItemOrder;

use Carbon\Carbon;

/**
 * Class ItemInventoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ItemOrderSummaryCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ItemOrderSummary');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/item-order-summary');
        $this->crud->setEntityNameStrings('Item', 'Item Order Summary');

        $item_order_ids=[];
        if(request('date')){
            $itemOrders = ItemOrder::where('deleted_at', null)->where('pickup_date', request('date'))->get();
        }
        else{
            $itemOrders = ItemOrder::where('deleted_at', null)->get();
        }
       
        foreach ($itemOrders as $key => $itemOrder) {
            $orders = collect($itemOrder->orders);
            foreach($orders as $order) {
                // dd($order['item_inventory_id']);
                $item_order_ids[] =  $order['item_inventory_id'];
            }
        }
        $this->crud->addClause('whereIn', 'id', $item_order_ids);

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();
        $this->crud->addFilter([ // date filter
          'type' => 'date',
          'name' => 'date',
          'label'=> 'Date'
        ],
        false,
        function($value) { // if the filter is active, apply these constraints
            // $this->crud->addClause('where', 'pickup_date', '>=', $value);
            // $this->crud->addClause('where', 'pickup_date', '<=', $value . ' 23:59:59');
        });

        // COLUMNS
        $this->crud->addColumn([
            'label' => "Image", // Table column heading
            'type' => "image",
            'name' => 'image', // the column that contains the ID of that connected entity;
        ]);

        $this->crud->addColumn([
            'label' => "Category", // Table column heading
            'type' => "select",
            'name' => 'item_category_id', // the column that contains the ID of that connected entity;
            'entity' => 'item_category', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\Models\ItemCategory", // 
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
            'name' => 'item_category_id', // the db column for the foreign key
            'entity' => 'item_category', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\ItemCategory" // for
        ]);
         $this->crud->addColumn([
            'label' => "Total Order",
            'type' => 'text',
            'name' => 'total_order'
        ]);
        $this->crud->removeColumns(['cost_price','barcode','description','quantity_on_hand']);

        $this->crud->denyAccess(['create', 'update', 'reorder', 'delete']);
        $this->crud->allowAccess('show');
        $this->crud->enableAjaxTable();
        $this->crud->enableExportButtons();
    }
}
