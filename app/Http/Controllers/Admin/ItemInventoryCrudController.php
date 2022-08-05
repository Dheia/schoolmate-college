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

use Carbon\Carbon;

/**
 * Class ItemInventoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ItemInventoryCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setDefaultPageLength(10);
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
        $this->crud->setModel('App\Models\ItemInventory');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/item-inventory');
        $this->crud->setEntityNameStrings('Item', 'Items Inventory');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        // add asterisk for fields that are required in ItemInventoryRequest
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

        $this->crud->addButtonFromView('line', 'Quantity', 'itemInventory.addQuantity', 'end');
        $this->crud->addButtonFromView('line',  'Favorite', 'itemInventory.isFavorite', 'end');
        $this->crud->addButtonFromView('top', 'start_inventory_today', 'itemInventory.add_start_inventory_for_today', 'end');
        $this->crud->addButtonFromView('top', 'end_inventory_today', 'itemInventory.add_end_inventory_for_today', 'end');
        
        $this->crud->addField([
            'name' => 'quantity_on_hand',
            'type' => 'number'
        ], 'create');
        $this->crud->removeColumns(['cost_price','barcode','description']);

        
        $this->crud->enableAjaxTable();
        $this->crud->enableExportButtons();
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $item = ItemInventory::findOrFail($request)->first();
        $request->request->set('quantity_on_hand',$item->quantity_on_hand);

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

    public function pos(){
        return view("pos.cashier");
    }

    public function json_items(){
        $items = ItemInventory::all()->jsonSerialize();
        return $items;
    }

    /**
     * INVENTORY REPORTS
     * @param  [type]  $report  [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function inventoryReport ($report = null, Request $request)
    {
        $reports = null;
        if($report !== null) {
            switch ($report) {
                case 'today':
                    $reports = self::getTodayInventoryReport();
                    break;

                case 'thisWeek' :
                    $reports = self::getThisWeekInventoryReport();
                    break;

                case 'thisMonth' :
                    $reports = self::getThisMonthInventoryReport();
                    break;
                
                default:
                    $reports = self::getCustomInventoryReport($request->start_date, $request->end_date);
                    break;
            }
        }

        return view("itemInventory.form-inventory-report", compact('report', 'reports'));
    }

    private function getTodayInventoryReport ()
    {
        $start_date = Carbon::today();
        $end_date   = Carbon::today();
        $reports    = ItemInventoryQuantity::whereDate('created_at', $end_date)->pluck('items');
        
        $items = [];
        foreach ($reports as $key => $item) {
            $items[] = json_decode($item);
        }

        $flattened_items = array_flatten($items);
        $items = collect($flattened_items)->groupBy('code');
        
        $groupedItems = [];
        foreach($items as $key => $value) {            
            $groupedItems[] = (object)[
                "item_id"        => $key, 
                "start_quantity" => $value->sum('start_quantity'),
                "end_quantity"   => $value->sum('end_quantity'),
            ];
        }

        foreach ($groupedItems as $key => $value) {
            $item = ItemInventory::where('code', $value->item_id)->first();
            if($item !== null) {
                $groupedItems[$key]->id = $item->id;
            }
        }

        $final = $groupedItems; 

        $data       = [
                        'start_date' => $start_date,
                        'end_date'   => $end_date,
                        'data'      => $final
                      ];
        return $data;
    }


    private function getThisWeekInventoryReport ()
    {
        $start_date = Carbon::now()->startOfWeek();
        $end_date   = Carbon::now()->endOfWeek();
        $reports    = ItemInventoryQuantity::whereDate('created_at', '>=', $start_date)
                                            ->whereDate('created_at', '<=', $end_date)
                                            ->pluck('items');
        
        $items = [];
        foreach ($reports as $key => $item) {
            $items[] = json_decode($item);
        }

        $flattened_items = array_flatten($items);
        $items = collect($flattened_items)->groupBy('code');
        
        $groupedItems = [];
        foreach($items as $key => $value) {            
            $groupedItems[] = (object)[
                "item_id"        => $key, 
                "start_quantity" => $value->sum('start_quantity'),
                "end_quantity"   => $value->sum('end_quantity'),
            ];
        }

        foreach ($groupedItems as $key => $value) {
            $item = ItemInventory::where('code', $value->item_id)->first();
            if($item !== null) {
                $groupedItems[$key]->id = $item->id;
            }
        }

        $final = $groupedItems; 

        $data       = [
                        'start_date' => $start_date,
                        'end_date'   => $end_date,
                        'data'      => $final
                      ];

        return $data;
    }
 
    private function getThisMonthInventoryReport ()
    {
        $start_date = Carbon::now()->startOfMonth();
        $end_date   = Carbon::now()->endOfMonth();
        $reports    = ItemInventoryQuantity::whereDate('created_at', '>=', $start_date)
                                            ->whereDate('created_at', '<=', $end_date)
                                            ->pluck('items');
        $items = [];
        foreach ($reports as $key => $item) {
            $items[] = json_decode($item);
        }

        $flattened_items = array_flatten($items);
        $items = collect($flattened_items)->groupBy('code');
        
        $groupedItems = [];
        foreach($items as $key => $value) {            
            $groupedItems[] = (object)[
                "item_id"        => $key, 
                "start_quantity" => $value->sum('start_quantity'),
                "end_quantity"   => $value->sum('end_quantity'),
            ];
        }

        foreach ($groupedItems as $key => $value) {
            $item = ItemInventory::where('code', $value->item_id)->first();
            if($item !== null) {
                $groupedItems[$key]->id = $item->id;
            }
        }

        $final = $groupedItems; 

        $data       = [
                        'start_date' => $start_date,
                        'end_date'   => $end_date,
                        'data'      => $final
                      ];

        return $data;
    }

    private function getCustomInventoryReport ($start_date, $end_date)
    {
        $start_date = Carbon::parse($start_date);
        $end_date   = Carbon::parse($end_date);
        $reports    = ItemInventoryQuantity::whereDate('created_at', '>=', $start_date)
                                            ->whereDate('created_at', '<=', $end_date)
                                            ->pluck('items');

        $items = [];
        foreach ($reports as $key => $item) {
            $items[] = json_decode($item);
        }

        $flattened_items = array_flatten($items);
        $items = collect($flattened_items)->groupBy('code');
        
        $groupedItems = [];
        foreach($items as $key => $value) {            
            $groupedItems[] = (object)[
                "item_id"        => $key, 
                "start_quantity" => $value->sum('start_quantity'),
                "end_quantity"   => $value->sum('end_quantity'),
            ];
        }

        foreach ($groupedItems as $key => $value) {
            $item = ItemInventory::where('code', $value->item_id)->first();
            if($item !== null) {
                $groupedItems[$key]->id = $item->id;
            }
        }

        $final = $groupedItems; 

        $data       = [
                        'start_date' => $start_date,
                        'end_date'   => $end_date,
                        'data'      => $final
                      ];

        return $data;
    }
    /*** END OF INVENTORY REPORT ***/


    /**
     * [salesReport description]
     * @param  [type]  $report  [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function salesReport ($report = null, Request $request)
    {   
        $reports = null;
        if($report !== null) {
            switch ($report) {
                case 'today':
                    $reports = self::getTodaySalesReport();
                    break;

                case 'thisWeek' :
                    $reports = self::getThisWeekSalesReport();
                    break;

                case 'thisMonth' :
                    $reports = self::getThisMonthSalesReport();
                    break;
                
                default:
                    $reports = self::getCustomSalesReport($request->start_date, $request->end_date);
                    break;
            }
        }   

        return view('itemInventory.form-sales-report', compact('report', 'reports'));
    }

    private function getTodaySalesReport ()
    {
         $start_date = Carbon::today();
         $end_date   = Carbon::today();
         $report     = InventoryTransaction::whereDate('created_at', $end_date)->get();
         $data       = [
                            'start_date' => $start_date,
                            'end_date'   => $end_date,
                            'data'      => $report
                       ];

         return $data;
    }

    private function getThisMonthSalesReport ()
    {
        $start_date = Carbon::now()->startOfMonth();
        $end_date   = Carbon::now()->endOfMonth();
        $report     = InventoryTransaction::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->get();
        $data       = [
                        'start_date' => $start_date,
                        'end_date'   => $end_date,
                        'data'      => $report
                      ];

        return $data;
    }

    private function getThisWeekSalesReport ()
    {
        $start_date = Carbon::now()->startOfWeek();
        $end_date   = Carbon::now()->endOfWeek();
        $report     = InventoryTransaction::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->get();
        $data       = [
                        'start_date' => $start_date,
                        'end_date'   => $end_date,
                        'data'      => $report
                      ];

        return $data;
    }

    private function getCustomSalesReport ($start_date, $end_date)
    {
        $start_date = Carbon::parse($start_date);
        $end_date   = Carbon::parse($end_date);
        $report     = InventoryTransaction::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->get();
        $data       = [
                        'start_date' => $start_date,
                        'end_date'   => $end_date,
                        'data'      => $report
                      ];
        return $data;
    }
    /*** END OF SALES REPORT ***/

    public function startInventoryForToday ()
    {
        $isExistToday = ItemInventoryQuantity::whereDate('created_at', Carbon::today())
                                             ->exists();
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

        $insertedItems                        = new ItemInventoryQuantity;
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
        $isExistToday = ItemInventoryQuantity::whereDate('created_at', Carbon::today())
                                             ->exists();
        if($isExistToday) {
            $items = $this->crud->model::all();
            $itemQuantities = ItemInventoryQuantity::whereDate('created_at', Carbon::today())
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

            $insertedItems             = ItemInventoryQuantity::whereDate('created_at', Carbon::today())
                                                              ->update(["items" => json_encode($data), "is_end_quantity_set" => true]);

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

        $item = ItemInventory::findOrFail($id);
        
        if($item) {
        
            $itemInventoryQtyLog                     = new ItemInventoryQuantityLog;
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
        $item = ItemInventory::findOrFail($id);

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
