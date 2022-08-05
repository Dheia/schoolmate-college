<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\ItemInventoryCollection;
use App\Models\Fund;
use App\Models\ItemInventory;
use App\Models\Rfid;

class POSAPIController extends Controller
{
    
	public function getInventory ()
	{
		$items = new ItemInventoryCollection(ItemInventory::all());
        return response()->json($items);
	}

	public function checkoutItem(Request $request)
	{
		dd($request);
	}











	public function submitFund (Request $request)
	{
		$message;

        $fund = new Fund();
        $fund->rfid_id  = $request->rfid;
        $fund->fund     = $request->amount;

        if($fund->save()) {
            $message = ['message' => 'Successfully Funded', 'status' => 'OK'];
        } else {
            $message = ['message' => 'Error Funding', 'status' => 'ERROR'];
        }

        return response()->json($message);
	}

    public function getStudentInfo ($rfid)
    {
    	$student = Rfid::where('rfid', $rfid)->with('studentRfid')->first();
    	return response()->json($student);
    }
}
