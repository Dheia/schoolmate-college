<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasicReportController extends Controller
{
    //
  	public function enrollmentList(){
  		
  		return view('reports.enrollment-list');
  	}
}
