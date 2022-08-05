<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CafeteriaRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Laravel\Passport\Client;

use App\Models\ItemInventory;
use App\Models\ItemCategory;
use App\Models\ItemOrder;
use App\StudentCredential;

use Carbon\Carbon;

class CafeteriaController extends Controller
{
	private $client;

    public function __construct ()
    {
        $this->client = Client::find(1);
    }
    public function index(){
    	$this->data['ItemInventories'] 	= 	ItemInventory::orderBy('name', 'ASC')
    													->where('deleted_at', null)
                                                        ->where('orderable', '1')
    													->get();
        $categories_ids = collect($this->data['ItemInventories'])->pluck('item_category_id')->toArray();
        // dd($categories_ids);
        $this->data['ItemCategories']   =   ItemCategory::orderBy('name', 'ASC')
                                                        ->where('deleted_at', null)
                                                        ->whereIn('id', $categories_ids)
                                                        ->get();
        return view('cafeteria.categories', $this->data);
    }

    public function getItems($id, $category_name){
    	$this->data['ItemInventory']     =   ItemInventory::orderBy('name', 'ASC')
                                                        ->where('deleted_at', null)
                                                        ->where('orderable', '1')
                                                        ->get();
        $categories_ids = collect($this->data['ItemInventory'])->pluck('item_category_id')->toArray();
        // dd($categories_ids);
        $this->data['ItemCategories']   =   ItemCategory::orderBy('name', 'ASC')
                                                        ->where('deleted_at', null)
                                                        ->whereIn('id', $categories_ids)
                                                        ->get();

    	$this->data['ItemInventories'] 	= 	ItemInventory::orderBy('name', 'ASC')
    													->where('item_category_id', $id)
    													->where('deleted_at', null)
                                                        ->where('orderable', '1')
    													->get();	
    	return view('cafeteria.categories', $this->data);
    }

    public function submitOrder(){
        return view('cafeteria.submit');
    }

    public function studentLogin(CafeteriaRequest $request){
     //    $date = Carbon::now()->addDays(6)->toDateString();
    	// $this->validate($request, [
     //        'studentnumber'   => 'required',
     //        'password'        => 'required',
     //        'cart_pickupDate' => 'required|date|after:'.$date
     //    ]);
       	// Attempt to log the user in
	    if (Auth::guard('student')->attempt(['studentnumber' => $request->studentnumber, 'password' => $request->password], $request->remember_token)) 
	    { 
	        // if successful, then redirect to their intended location
	        $data=[];
	        $orders = json_decode($request->cart_data);
            if(count($orders)>0){
    	        foreach ($orders as $key => $order) {
    	        	$item     = ItemInventory::where('id', $order->id)->first();
                    $data[]   = [
                        'item_inventory_id' => $order->id,
                        'price'             => $item->sale_price,
                        'quantity'          => $order->quantity
                    ];
    	        }
                $pickup_date = strtotime($request->cart_pickupDate);
                $item_order = new ItemOrder();
                $item_order->studentnumber  =   $request->studentnumber;
                $item_order->orders         =   $data;
                $item_order->code           =   str_pad(ItemOrder::max('id')+1,6,'0',STR_PAD_LEFT);
                $item_order->pickup_date    =   date('Y-m-d', $pickup_date);
                $item_order->pickup         =   '0';
                $item_order->save();

                // Auth::guard('student')->logout();

    	        return redirect('cafeteria')
                    ->with('success', 'Order Submitted!');
            }
            else{
                return redirect('cafeteria')
                    ->with('success', 'Order Submitted!');
            }
	    }
        else if(Auth::attempt(['email' => request('studentnumber'), 'password' => request('password')])){
            $user = Auth::user();
            $data=[];
            $orders = json_decode($request->cart_data);
            if(count($orders)>0){
                
                   
                foreach ($orders as $key => $order) {
                    $item     = ItemInventory::where('id', $order->id)->first();
                    $data[]   = [
                        'item_inventory_id' => $order->id,
                        'price'             => $item->sale_price,
                        'quantity'          => $order->quantity
                    ];
                }
                $pickup_date = strtotime($request->cart_pickupDate);
                $item_order = new ItemOrder();
                $item_order->employee_id    =   $user->employee_id;
                $item_order->orders         =   $data;
                $item_order->code           =   str_pad(ItemOrder::max('id')+1,6,'0',STR_PAD_LEFT);
                $item_order->pickup_date    =   date('Y-m-d', $pickup_date);
                $item_order->pickup         =   '0';
                $item_order->save();

                // Auth::guard('student')->logout();

                return redirect('cafeteria')
                    ->with('success', 'Order Submitted!');
            }
            else{
                return redirect('cafeteria')
                    ->with('success', 'Order Submitted!');
            } 

        }

	    // if unsuccessful, then redirect back to the login with the form data
	    return redirect()->back()->withInput($request->only('studentnumber'))->with('failed', 'Credential not matched!');

    }
}
