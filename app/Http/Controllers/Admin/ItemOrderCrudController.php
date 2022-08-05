<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ItemOrderRequest as StoreRequest;
use App\Http\Requests\ItemOrderRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\ItemInventory;

use Illuminate\Http\Request;

use Carbon\Carbon;

/**
 * Class ItemOrderCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ItemOrderCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ItemOrder');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/item-order');
        $this->crud->setEntityNameStrings('Item Order', 'Item Orders');
        
        $this->crud->addClause('orderBy', 'id', 'DESC');
        $currentDate =  Carbon::now()->toDateString();
        // if(!request('date')){
        //     $this->crud->addClause('where', 'pickup_date', '>=', $currentDate);
        //     $this->crud->addClause('where', 'pickup_date', '<=', $currentDate . ' 23:59:59');
        // }
        
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        
        $this->crud->addFilter([ // date filter
          'type' => 'date',
          'name' => 'date',
          'label'=> 'Pickup Date'
        ],
        false,
        function($value) { // if the filter is active, apply these constraints
            $this->crud->addClause('where', 'pickup_date', '>=', $value);
            $this->crud->addClause('where', 'pickup_date', '<=', $value . ' 23:59:59');
        });
        $this->crud->addFilter([ // dropdown filter
            'name' => 'status',
            'type' => 'dropdown',
            'label'=> 'Status'
          ], [
            1 => 'Picked up',
            2 => 'Not Picked up'
          ], function($value) { // if the filter is active
                if($value == 1){
                    $this->crud->addClause('where', 'pickup', '=', '1');
                }
                else if($value == 2){
                    $this->crud->addClause('where', 'pickup', '!=', '1');
                }
        });

        $this->crud->addFilter([ // dropdown filter
            'name' => 'approved',
            'type' => 'dropdown',
            'label'=> 'Approved'
          ], [
            1 => 'Approved',
            2 => 'Not Approved'
          ], function($value) { // if the filter is active
                if($value == 1){
                    $this->crud->addClause('where', 'approved', '=', '1');
                }
                else if($value == 2){
                    $this->crud->addClause('where', 'approved', '!=', '1');
                }
        });

        $this->crud->addColumn([
            'name' => 'code',
            'type' => 'text',
            'label' => 'Code'
        ]);
        $this->crud->addColumn([
            'name' => 'id_number',
            'type' => 'text',
            'label' => 'ID No.',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('student', function ($q) use ($column, $searchTerm) {
                    $q->where('studentnumber', 'like', '%'.$searchTerm.'%');
                });
                $query->orWhereHas('employee', function ($q) use ($column, $searchTerm) {
                    $q->where('employee_id', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);
        $this->crud->addColumn([
            'name' => 'student_fullname',
            'type' => 'text',
            'label' => 'Full name',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('student', function ($q) use ($column, $searchTerm) {
                    $q->where('firstname', 'like', '%'.$searchTerm.'%')
                    ->orWhere('middlename', 'like', '%'.$searchTerm.'%')
                    ->orWhere('lastname', 'like', '%'.$searchTerm.'%');
                });
                $query->orWhereHas('employee', function ($q) use ($column, $searchTerm) {
                    $q->where('firstname', 'like', '%'.$searchTerm.'%')
                    ->orWhere('middlename', 'like', '%'.$searchTerm.'%')
                    ->orWhere('lastname', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);
        $this->crud->addColumn([
            'name' => 'department',
            'type' => 'text',
            'label' => 'Department'
        ]);
        $this->crud->addColumn([
            'name' => 'year',
            'type' => 'text',
            'label' => 'Grade Level'
        ]);
        $this->crud->addColumn([
            'name' => 'track',
            'type' => 'text',
            'label' => 'Track'
        ]);
        $this->crud->addColumn([
            'name' => 'position',
            'type' => 'text',
            'label' => 'Position'
        ]);
        $this->crud->addColumn([
            'name' => 'total_price',
            'type' => 'text',
            'label' => 'Total Price',
            'prefix' => '₱',
        ]);
        $this->crud->addColumn([ // date filter
          'type' => 'date',
          'name' => 'pickup_date',
          'label'=> 'Pickup Date',
          'format' => 'MMMM DD, YYYY'
        ]);
        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();
        $this->crud->removeColumn('orders');
        $this->crud->removeField('orders');

        $this->crud->addColumn([
            'name' => 'status',
            'type' => 'text',
            'label' => 'Status'
        ]);

        // $this->crud->addColumn([
        //     'name' => 'orders',
        //     'type' => 'table',
        //     'label' => 'Orders'
        // ]);

        // add asterisk for fields that are required in ItemOrderRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');

        $this->crud->allowAccess('pickup');
        $this->crud->addButtonFromView('line', 'itemOrder.pickup', 'itemOrder.pickup', 'beginning');
        $this->crud->allowAccess('approved');
        $this->crud->addButtonFromView('line', 'itemOrder.approved', 'itemOrder.approved', 'beginning');
        $this->crud->addButtonFromView('line', 'itemOrder.view_items', 'itemOrder.view_items', 'beginning');
        $this->crud->addButtonFromView('top', 'itemOrder.orders_summary', 'itemOrder.orders_summary', 'end');
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
    public function pickup(Request $request){
        $itemOrders = $this->crud->model::where('id', $request->order_id)->first();
        $itemOrders->pickup = 1;
        if($itemOrders->update()){
            \Alert::success('Successfully Picked up.')->flash();
            return \Redirect::to($this->crud->route);
        } else{
            \Alert::error('Error, Something Went Wrong, Please Try Again.')->flash();
            return \Redirect::to($this->crud->route);
        }
    }

     public function approved(Request $request){
        $itemOrders = $this->crud->model::where('id', $request->order_id)->first();
        $itemOrders->approved = 1;
        if($itemOrders->update()){
            \Alert::success('Successfully Approved.')->flash();
            return \Redirect::to($this->crud->route);
        } else{
            \Alert::error('Error, Something Went Wrong, Please Try Again.')->flash();
            return \Redirect::to($this->crud->route);
        }
    }

    public function getItems(Request $request){
        $itemOrders = $this->crud->model::where('id', $request->id)->first();
        $orders = collect($itemOrders->orders);
        $data=[];
        foreach($orders as $order) {
            $item = ItemInventory::where('id', $order['item_inventory_id'])->first();
            $data[] = [
                'item_name' => $item->name,
                'price'     => $item->sale_price,
                'quantity'  => $order['quantity'],
                'item_total'=> $item->sale_price * $order['quantity']
            ];
        }
        return response()->json($data);
    }
}
