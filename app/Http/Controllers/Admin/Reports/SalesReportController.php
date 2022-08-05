<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentHistory;
use App\Models\User;
use DB;
use Carbon\Carbon;


class SalesReportController extends Controller
{
   public function index(){
       return view('reports.accounting.sale_report');
   }
  
    public function reportlogs($period)
    {
        
        $response = [
            'error'     => false,
            'message'   => null,
            'data'      => null
        ];

        $startDate  = request()->date_from;
        $endDate    = request()->date_to;
        $today     = Carbon::now()->format('Y-m-d');
        $firstDay = Carbon::now()->firstOfMonth()->format('Y-m-d');  

        if($period == 'today'){
           
            $days      = Carbon::now()->addDays(1)->format('Y-m-d');
            $payment   = PaymentHistory::with('user')
                                        ->with('enrollment')
                                        ->with('paymentMethod')->with('payment_histories')->where('created_at', '>=', $today )
            ->where('created_at', '<=', $days . ' 23:59:59')->get();

        }else if($period == 'this_week'){
           
            $endDate   = Carbon::now()->addDays(7)->format('Y-m-d');
            $payment   = PaymentHistory::with('user')
                                        ->with('enrollment')
                                        ->with('paymentMethod')->with('payment_histories')->where('created_at', '>=', $today )
            ->where('created_at', '<=', $endDate . ' 23:59:59')->get();

        }else if($period == 'this_month'){

            $endDate   = Carbon::now()->addMonth(1)->format('Y-m-d');
            $payment   = PaymentHistory::with('user')
                                        ->with('enrollment')
                                        ->with('paymentMethod')->where('created_at', '>=', $firstDay )
            ->where('created_at', '<=', $endDate . ' 23:59:59')->get();
          
        }else if($period == 'custom'){
            $payment   = PaymentHistory::with('user')
                                        ->with('enrollment')
                                        ->with('paymentMethod')->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate . ' 23:59:59')->get();
           
        }
     
        $response['data']   =   $payment;
        return $response;
    }

    public function generateReport (Request $request)
    {
        $startDate = $request->date_from;
        $endDate   = $request->date_to;
        $today     = Carbon::now()->format('Y-m-d');
        $period    = $request->report_name;
        $firstDay  = Carbon::now()->firstOfMonth()->format('Y-m-d');  

        if($request->report_name == 'today'){
           
            $days      = Carbon::now()->addDays(1)->format('Y-m-d');
            $payment   = PaymentHistory::where('created_at', '>=', $today )
            ->where('created_at', '<=', $days . ' 23:59:59')->get();

        }else if($request->report_name == 'this_week'){
           
            $endDate   = Carbon::now()->addDays(7)->format('Y-m-d');
            $payment   = PaymentHistory::where('created_at', '>=', $today  )
            ->where('created_at', '<=', $endDate )->get();

        }else if($request->report_name == 'this_month'){

            $endDate   = Carbon::now()->addMonth(1)->format('Y-m-d');
            $payment   = PaymentHistory::where('created_at', '>=',  $firstDay  )
            ->where('created_at', '<=', $endDate . ' 23:59:59')->get();
          
        }else{
           
            $payment   = PaymentHistory::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate . ' 23:59:59')->get();
           
        }
        
        $data = [
            'payments'        => $payment,
            'schoollogo'      => config('settings.schoollogo') ? (string)\Image::make(config('settings.schoollogo'))->encode('data-url') : null,
            'schoolmate_logo' =>(string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url'),
            'totalamount'     => number_format($payment->sum('amount'), 2, '.', ',') ,
            'startDate'       =>  $startDate,
            'endDate'         => $endDate,
            'period'          => $period
        ];
        
      

        return view('reports.accounting.generateReport', $data );

       
    }
}
