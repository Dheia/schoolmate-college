<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeographicController extends Controller
{
    public function getProvinces ()
    {
        $provinces = json_decode(\File::get(public_path('philippines/refprovince.json')));
        $sorted = collect($provinces->records)->sortBy('provDesc');
        $data['records'] = $sorted->values()->all();
    	return response()->json($data);
    }
    
    public function getCities ()
    {
        $cities = collect(json_decode(\File::get(public_path('philippines/refcitymun.json')))->records);
        // dd($cities);
        $cities = $cities->where('provCode', request()->province_code)->flatten();
    	return response()->json($cities);
    }

    public function getBarangay ()
    {
        $barangay = collect(json_decode(\File::get(public_path('philippines/refbrgy.json')))->records);
        $barangay = $barangay->where('citymunCode', request()->city_code)->flatten();
    	return response()->json($barangay);
    }
}
