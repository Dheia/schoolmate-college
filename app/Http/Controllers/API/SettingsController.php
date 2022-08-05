<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\TextBlast;
use App\Models\Setting;

class SettingsController extends Controller
{    
    public function getSettings(Request $request){

        $_key = $request->get('key');

        $result = DB::table('settings')
        ->select('value')
        ->where("key","=",$_key)
        ->first()->value;

        return $result;
    }

    public function getSchoolInformation()
    {
    	$keys 		=	['schoolname', 'schoolemail', 'schooladdress', 'schoolcontactnumber', 'schoollogo', 'schoolabbr'];
    	$results 	= 	Setting::select(['key', 'name', 'description', 'value', 'active'])
	    					->whereIn('key', $keys)
	    					->get();
    	return response()->json($results);
    }
}
