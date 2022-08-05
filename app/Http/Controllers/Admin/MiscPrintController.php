<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App;

class MiscPrintController  extends Controller {
	public function index($id){
		$miscs = DB::select("select * from miscs where id = $id");
		$sy = DB::select("select schoolYear from school_years where id = ".$miscs[0]->schoolyear_id);
		$miscs[] = $sy[0]->schoolYear;
		$pdf = App::make('dompdf.wrapper');
		$pdf->setPaper(array(0, 0, 595, 500), 'portrait');
		$pdf->loadHTML(view('printmisc',['miscs'=>$miscs]));
		return $pdf->stream();
	}
}