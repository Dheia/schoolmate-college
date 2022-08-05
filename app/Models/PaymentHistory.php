<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

use App\Models\Tuition;
use App\Models\OtherProgram;
use App\Models\OtherService;
use App\Models\SpecialDiscount;

use App\SelectedOtherProgram;
use App\SelectedOtherService;
use App\AdditionalFee;

use App\Http\Controllers\QuickBooks\QuickBooksOnline;

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
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'payment_histories';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'enrollment_id',
        'user_id',
        'invoice_no',
        'payment_method_id',
        'paynamics_payment_id',
        'amount',
        'fee',
        'description',
        'date_received',
        'payment_historable_id',
        'payment_historable_type'
    ];
    
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['remaining_balance', 'payment_for', 'payment_method_name'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function addInvoicePayment ($paymentHistory)
    {
        $response = [
            'status' => null,
            'data' => null,
            'message' => null,
        ];

        $enrollment = Enrollment::where('id', $paymentHistory->enrollment_id)->with('student')->first();
        if($enrollment == null) {
            \Log::info('Payment History Observer Error (Created): This Enrollment (' . $enrollment->id . ') Fee Is Not Yet Invoiced On QuickBooks');
            $response['status'] = 'error';
            $response['message'] = 'Payment Invoicing Error: This Enrollment (' . $enrollment->id . ') Fee Is Not Yet Invoiced On QuickBooks';
            return $response;
        }

        try {

            $qbo = new QuickBooksOnline;
            $qbo->initialize(); 

            if($qbo->dataService() === null) {
                \Log::info('Payment History Observer Error (Created): Unauthorized QuickBooks');
                $response['status'] = 'error';
                $response['message'] = 'Payment Invoicing Error: Unauthorized QuickBooks';
                return $response;
            }

            $paymentHistory = PaymentHistory::where('id', $paymentHistory->id)->with('student')->first();
            $invoice_id = $qbo->dataService()->Query("SELECT * FROM Invoice WHERE DocNumber = '" . $enrollment->invoice_no . "'");

            // VALIDATE IF THIS PAYMENT HAS ALREADY INVOICED
            if($paymentHistory->invoice_no !== null) {
                \Log::info('Payment History Observer Error (Created): This Payment (' . $paymentHistory->id . ') Was Already Invoiced');
                $response['status'] = 'error';
                $response['message'] = 'Payment Invoicing Error: This Payment (' . $paymentHistory->id . ') Was Already Invoiced';
                return $response;
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
                $response['status'] = 'error';
                $response['message'] = 'Payment Invoicing Error: ' . $error->getResponseBody();
                return $response;
            }

            $paymentHistory->invoice_no = $resultingObj->Id;
            $paymentHistory->save();
            \Log::info('Payment History Observer Success (Created): Successfully Invoiced Payment');
            
            $response['status'] = 'success';
            $response['data']   = $paymentHistory;
            $response['message'] = 'The Payment has been Successfully Invoiced';
            return $response;

        } catch (\Exception $e) {
            $status  = "ERROR";
            $message = $e->getMessage();
            \Log::info("Payment History Observer Error (Created): " . json_encode($e));

            $response['status']  = 'error';
            $response['message'] = "Payment Invoicing Error: " . json_encode($e);
            return $response;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function commitmentPayment ()
    {
        return $this->belongsTo(CommitmentPayment::class);
    }

    public function paymentMethod ()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function tuition ()
    {
        return $this->belongsTo(Tuition::class);
    }

    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    public function schoolYear ()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function level ()
    {
        return $this->belongsTo(Level::class);
    }

    public function payment_historable ()
    {
        return $this->morphTo();
    }

    public function enrollment ()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function paynamicsPayment ()
    {
        return $this->belongsTo(PaynamicsPayment::class);
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
        $tuition = Tuition::where('id', $this->tuition_id)->first();

        if(!$tuition) { return 0; }

        /**
         * [$otherPrograms GET ALL TOTAL OF THE OTHER PROGRAMS]
         * @var [type]
         */
        $otherPrograms = SelectedOtherProgram::where('enrollment_id', $tuition->enrollment_id)
                                                ->with('otherPrograms')->get();
        
        $otherPrograms = $otherPrograms->pluck('otherPrograms')->flatten();
        $totalOtherProgram = 0;
        foreach ($otherPrograms as $value) {
            $totalOtherProgram += (float)$value->amount;
        }      


         /**
         * [$otherPrograms GET ALL TOTAL OF THE SPECIAL DISCOUNTS]
         * @var [type]
         */
        $specialDiscount    = SpecialDiscount::where('enrollment_id', $tuition->enrollment_id)->get();
        
        $totalSpecialDiscount = $specialDiscount->sum('amount');
        


        $remainingBalance = 0;
        foreach($tuition->grand_total as $grandTotal) {
            if($grandTotal['payment_type'] == $this->payment_method_id) {
                $remainingBalance = ( (float)$grandTotal['amount'] + $totalOtherProgram ) - (float)$this->amount - (float)$totalSpecialDiscount;
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
                return $selectedOtherProgram->otherProgram->name ?? 'Unknown';
            }
                $selectedOtherService = SelectedOtherService::where('id', $this->payment_historable_id)->with('otherService')->first();
                return $selectedOtherService->otherService->name ?? 'Unknown';
        }

            return 'Enrollment Fee';
    }

    public function getStudentNumberAttribute()
    {
        $enrollment = $this->enrollment()->with('student')->first();
        if($enrollment) {
            return $enrollment->student ? $enrollment->student->studentnumber : '-';
        }
        return '-';
    }

    public function getFullNameAttribute()
    {
        $enrollment = $this->enrollment()->with('student')->first();
        if($enrollment) {
            return $enrollment->student ? $enrollment->student->full_name : '-';
        }
        return '-';
    }

    public function getTuitionNameAttribute ()
    {
        $enrollment = $this->enrollment()->with('tuition')->first();
        if($enrollment) {
            return $enrollment->tuition ? $enrollment->tuition->form_name : '-';
        }
        return '-';
    }

    public function getLevelAttribute ()
    {
        $enrollment = $this->enrollment()->with('level')->first();
        if($enrollment) {
            return $enrollment->level ? $enrollment->level->year : '-';
        }
        return '-';
    }

    public function getSchoolYearAttribute ()
    {
        $enrollment = $this->enrollment()->with('schoolYear')->first();
        if($enrollment) {
            return $enrollment->schoolYear ? $enrollment->schoolYear->schoolYear : '-';
        }
        return '-';
    }
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
