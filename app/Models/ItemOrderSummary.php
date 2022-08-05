<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ItemOrderSummary extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'item_inventories';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['image', 'code','name','barcode','description','sale_price','cost_price'];
    // protected $hidden = [];
    // protected $dates = [];
     protected $appends = ['total_order'];
    protected $dates = ['deleted_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function item_category()
    {
        return $this->belongsTo(ItemCategory::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function getTotalOrderAttribute(){
        if(request('date')){
            $itemOrders = ItemOrder::where('deleted_at', null)->where('pickup_date', request('date'))->get();
        }
        else{
            $itemOrders = ItemOrder::where('deleted_at', null)->get();
        }
        
        $count = '0';
        foreach ($itemOrders as $key => $itemOrder) {
            $orders = collect($itemOrder->orders);
            $data=[];
            foreach($orders as $order) {
                if($this->id == $order['item_inventory_id']){

                    $count = $count + $order['quantity'];
                }
            }
        }
        return $count;
       
    }
}
