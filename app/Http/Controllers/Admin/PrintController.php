<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App;
use App\Models\Student;
use App\Models\YearManagement;

class PrintController extends Controller {
	public function student($id){
		$students = Student::where('id', $id)->with('yearManagement')->get();

		$level = YearManagement::where('id', $students[0]->level_id)->get();

		$pdf = App::make('dompdf.wrapper');
		$pdf->setPaper(array(0, 0, 595, 1670), 'portrait');
		$pdf->loadHTML(view('print',[
										'student'=> $students,
										'level' => $level
									]
							));
		return $pdf->stream();
	}
}