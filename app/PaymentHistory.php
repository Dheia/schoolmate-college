<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Http\Controllers\QuickBooks\QuickBooksOnline;

use App\Models\Tuition;
use App\Models\OtherProgram;
use App\Models\OtherService;
use App\Models\SpecialDiscount;
use App\Models\Enrollment;

use App\SelectedOtherProgram;
use App\SelectedOtherService;
use App\AdditionalFee;

// QUICKBOOKS FACADES
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;
use QuickBooksOnline\API\Data\IPPPaymentLineDetail;
use QuickBooksOnline\API\Data\IPPLine;
use QuickBooksOnline\API\Data\IPPSalesItemLineDetail;
use QuickBooksOnline\API\Data\IPPDiscountLineDetail;
use QuickBooksOnline\API\Data\IPPDiscountOverride;

class PaymentHistory extends Model
{

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'payment_histories';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['payment_historable_id', 'payment_historable_type'];
    // protected $hidden = [];
    // protected $dates = [];
    // protected $appends = ['total_payment_history'];
    protected $appends = ['remaining_balance', 'payment_for', 'payment_method_name'];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
   
    public static function addInvoicePayment ($paymentHistory)
    {
        $enrollment = Enrollment::where('id', $paymentHistory->enrollment_id)->with('student')->first();
        if($enrollment == null) {
            \Log::info('Payment History Observer Error (Created): This Enrollment (' . $enrollment->id . ') Fee Is Not Yet Invoiced On QuickBooks');
            return null;
        }

        try {

            $qbo = new QuickBooksOnline;
            $qbo->initialize(); 

            if($qbo->dataService() === null) {
                \Log::info('Payment History Observer Error (Created): Unauthorized QuickBooks');
                return null;
            }

            $paymentHistory = PaymentHistory::where('id', $paymentHistory->id)->with('student')->first();
            $invoice_id = $qbo->dataService()->Query("SELECT * FROM Invoice WHERE DocNumber = '" . $enrollment->invoice_no . "'");

            // VALIDATE IF THIS PAYMENT HAS ALREADY INVOICED
            if($paymentHistory->invoice_no !== null) {
                \Log::info('Payment History Observer Error (Created): This Payment (' . $paymentHistory->id . ') Was Already Invoiced');
                return null;
            }

            // GET THE INVOICE_NO OF RELATED TO THEY MODEL
            if($paymentHistory->payment_historable_id !== null && $paymentHistory->payment_historable_type !== null)
            {
                // AUTOMATICALLY GET THE SelectedOther{Program, Service} MODEL MORPH RELATED TO THEIR MODEL AND GET THE INVOICE_NO
                $service_invoice_no = $paymentHistory->payment_historable->invoice_no;
            }

            $items = [
                "TotalAmt"      => $paymentHistory->amount,
                "CustomerRef"   => [
                    "value" => $enrollment->student->qbo_customer_id
                ],
                "PrivateNote"   => "Payment: " . $paymentHistory->description
            ];

            $theResourceObj = Payment::create($items);
            $resultingObj   = $qbo->dataService()->Add($theResourceObj);
            $error          = $qbo->dataService()->getLastError();

            if ($error) {
                \Log::info('Payment History Observer Error (Created): ' . $error->getResponseBody());
                return null;
            }

            $paymentHistory->invoice_no = $resultingObj->Id;
            $paymentHistory->save();
            \Log::info('Payment History Observer Success (Created): Successfully Invoiced Payment');

        } catch (\Exception $e) {
            $status  = "ERROR";
            $message = $e->getMessage();
            \Log::info("Payment History Observer Error (Created): " . json_encode($e));
        }
    }
    

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    
    public function paymentMethod ()
    {
        return $this->belongsTo('App\Models\PaymentMethod');
    }

    public function user ()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function commitmentPayment ()
    {
        return $this->belongsTo("App\Models\CommitmentPayment");
    }

    public function student ()
    {
        return $this->belongsTo("App\Models\Student",  "studentnumber", "studentnumber");
    }

    public function payment_historable ()
    {
        return $this->morphTo();
    }

    public function enrollment ()
    {
        return $this->belongsTo("App\Models\Enrollment");
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
    public function getPaymentMethodNameAttribute()
    {
        $paymentMethod = $this->paymentMethod()->first();
        return $paymentMethod ? $paymentMethod->name : '-';
    }
    
    public function getRemainingBalanceAttribute ()
    {
      // var totalPaymentHistory       = this.totalPaymentHistory; ^
      // var totalSelectedOtherProgram = this.totalSelectedOtherProgram;
      // var totalSpecialDiscount      = this.totalSpecialDiscount;

        $remainingBalance = 0;
        if(!$this->enrollment()->first()) {
            return $remainingBalance;
        }

        $tuition = Tuition::where('id', $this->enrollment()->first()->id)->first();


        /**
         * [$otherPrograms GET ALL TOTAL OF THE OTHER PROGRAMS]
         * @var [type]
         */
        $otherPrograms     = SelectedOtherProgram::where('enrollment_id', $this->enrollment_id)->with('otherPrograms')->get();
        $otherPrograms     = $otherPrograms->pluck('otherPrograms')->flatten();
        $totalOtherProgram = 0;
        foreach ($otherPrograms as $value) {
            $totalOtherProgram += (float)$value->amount;
        }      

         /**
         * [$specialDiscount GET ALL TOTAL OF THE SPECIAL DISCOUNTS]
         * @var [type]
         */
        $specialDiscount        = SpecialDiscount::where('enrollment_id', $this->enrollment_id)->get();
        $totalSpecialDiscount   = $specialDiscount->sum('amount');
        
        if($tuition !== null) {
            foreach($tuition->grand_total as $grandTotal) {
                if($grandTotal['payment_type'] == $this->payment_method_id) {
                    $remainingBalance = ( (float)$grandTotal['amount'] + $totalOtherProgram ) -  (float)$this->amount - (float)$totalSpecialDiscount;
                }
            }
        }

        return $remainingBalance;
    }


    public function getPaymentForAttribute ()
    {
        if($this->payment_historable_id !== null && $this->payment_historable_type !== null)
        {   
            if('App\SelectedOtherProgram' == $this->payment_historable_type)
            {
                $selectedOtherProgram = SelectedOtherProgram::where('id', $this->payment_historable_id)->with('otherProgram')->first();
                if($selectedOtherProgram) {
                    if($selectedOtherProgram->otherProgram) {
                        return 'Other Program | ' . $selectedOtherProgram->otherProgram->name ?? 'Other Program | -';
                    }
                }
                return 'Other Program | -';
            }
            else if('App\SelectedOtherService' == $this->payment_historable_type) {
                $selectedOtherService = SelectedOtherService::where('id', $this->payment_historable_id)->with('otherService')->first();
                if($selectedOtherService) {
                    if($selectedOtherService->otherService) {
                        return 'Other Service | ' . $selectedOtherService->otherService->name;
                    }
                }
                return 'Other Service | -';
            } 
            else if('App\AdditionalFee' == $this->payment_historable_type) {
                $additionalFee = AdditionalFee::where('id', $this->payment_historable_id)->first();
                return 'Additional Fee | ' . $additionalFee->description ?? 'Additional Fee | -';
            } else {
                return 'Unknown';
            }
        }

        return 'Enrollment Fee | ' . $this->description;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
