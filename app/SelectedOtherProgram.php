<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

use App\Http\Controllers\QuickBooks\QuickBooksOnline;

use App\Models\Enrollment;

// QUICKBOOKS FACADES
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;
use QuickBooksOnline\API\Data\IPPPaymentLineDetail;
use QuickBooksOnline\API\Data\IPPLine;
use QuickBooksOnline\API\Data\IPPSalesItemLineDetail;
use QuickBooksOnline\API\Data\IPPDiscountLineDetail;
use QuickBooksOnline\API\Data\IPPDiscountOverride;

class SelectedOtherProgram extends Model
{
    use CrudTrait;
    use \Awobaz\Compoships\Compoships;

    protected $table = 'selected_other_programs';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
    	'enrollment_id',
        'other_program_id',
        'added_by',
        'approved'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['name', 'amount', 'remaining_balance'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function addInvoiceOtherProgram ($selectedOtherProgram)
    {
        // CHECK IF ENROLLMENT IS IN THE URL PARAMETER
        $enrollment = Enrollment::where('id', $selectedOtherProgram->enrollment_id)->with('student')->first();
        if($enrollment == null) {
            \Log::info('Selected Other Program Error (Created): This Enrollment (' . $enrollment->id . ') Fee Is Not Yet Invoiced On QuickBooks');
            return null;
        }

        try {

            $qbo = new QuickBooksOnline;
            $qbo->initialize();
            
            if($qbo->dataService() === null)
            {
                \Log::info('Selected Other Program Error (Created): Unauthorized QuickBooks');
                return null;
            }

            $selectedOtherProgram = SelectedOtherProgram::where('id', $selectedOtherProgram->id)->with(['otherProgram'])->first();

            // VALIDATE IF THIS PAYMENT HAS ALREADY INVOICED
            if($selectedOtherProgram->invoice_no !== null) {
                \Log::info('Selected Other Program Error (Created): This Payment (' . $selectedOtherProgram->id . ') Was Already Invoiced');
                return null;
            }

            // GET THE ENROLLMENT AND OTHER PROGRAM QBO ID AND QUERY TO QBO
            $enrollment_id       = $enrollment->id;
            $enrollment          = Enrollment::where('id', $enrollment_id)->with('student')->firstOrFail();
            $student_qbo_id      = $enrollment->student->qbo_customer_id;
            // $enrolmentFeeProduct = $qbo->dataService()->Query("SELECT * FROM Item WHERE Name = 'Enrolment Fee' AND Type = 'Service'");
            // $enrolmentFee        = $enrolmentFeeProduct[0];

            $items =    [
                            "AutoDocNumber" => true,
                            "Line" => [
                                [
                                    "DetailType"          => "SalesItemLineDetail", 
                                    "Amount"              => $selectedOtherProgram->otherProgram->amount, 
                                    "SalesItemLineDetail" => [
                                        "ItemRef" => 
                                        [
                                            // "name"   => $selectedOtherProgram->otherProgram->name,
                                            "value"  => $selectedOtherProgram->otherProgram->qbo_map 
                                        ],
                                        "TaxCodeRef" => [
                                            "value" => config('settings.taxrate'),
                                        ]
                                    ]
                                ]
                            ], 
                            "CustomerRef" => [
                                "value" => (string)$student_qbo_id
                            ],
                            "PrivateNote" => "Other Program: " . $selectedOtherProgram->otherProgram->name
                        ];

            $theResourceObj = Invoice::create($items);
            $resultingObj   = $qbo->dataService()->Add($theResourceObj);
            $error          = $qbo->dataService()->getLastError();

            if ($error) {
                \Log::info('Selected Other Program Observer Error (Created): ' . $error->getResponseBody());
                return null;
            }

            SelectedOtherProgram::where('id', $selectedOtherProgram->id)->update(['invoice_no' => $resultingObj->DocNumber]);
            \Log::info('Selected Other Program Observer Success (Created): Successfully Invoiced Other Program');
            
        } catch (\Exception $e) {
            $message = $e->getMessage();
            \Log::info("Selected Other Program Observer Error (Created): " . json_encode($e));
        }
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    
    public function otherPrograms ()
    {   
        return $this->hasMany('App\Models\OtherProgram', ['id'], ['other_program_id']);
    }

    public function otherProgram ()
    {   
        return $this->belongsTo('App\Models\OtherProgram');
    }

    public function user ()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function enrollment ()
    {
        return $this->belongsTo('App\Models\Enrollment');
    }

    public function paymentHistory ()
    {
        return $this->hasMany('App\Models\PaymentHistory', 'payment_historable_id')->where('payment_historable_type', 'App\SelectedOtherProgram');
    }

    // public function student ()
    // {
    //     return $this->belongsTo("App\Models\Student",  "student_no", "studentnumber");
    // }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS WITH TRASHED
    |--------------------------------------------------------------------------
    */

    public function otherProgramWithTrashed ()
    {   
         return $this->otherProgram()->withTrashed();
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

    public function getRemainingBalanceAttribute ()
    {
        return  $this->otherProgram()->first()->amount - $this->paymentHistory()->get()->sum('amount');
    }

    public function getStudentnumberAttribute()
    {
        $enrollment = $this->enrollment()->first();
        $student    = $enrollment->student;

        return $student ? $student->studentnumber : '-';
    }

    public function getFullnameAttribute()
    {
        $enrollment = $this->enrollment()->first();
        $student    = $enrollment->student;

        return $student ? $student->firstname . ' ' . $student->lastname : '-';
    }

    public function getProgramNameAttribute()
    {
        $other_program = $this->otherProgram()->first();
        return $other_program ? $other_program->name : '-';
    }

    public function getNameAttribute()
    {
        $other_program = $this->otherProgram()->first();
        return $other_program ? $other_program->name : '-';
    }

    public function getAmountAttribute()
    {
        $other_program = $this->otherProgram()->first();
        return $other_program ? $other_program->amount : 0;
    }

    public function getSchoolYearNameAttribute()
    {
        $enrollment = $this->enrollment()->first();
        return $enrollment ? $enrollment->school_year_name : '-';
    }

    public function getDepartmentNameAttribute()
    {
        $enrollment = $this->enrollment()->first();
        return $enrollment ? $enrollment->department_name : '-';
    }

    public function getLevelNameAttribute()
    {
        $enrollment = $this->enrollment()->first();
        return $enrollment ? $enrollment->level_name : '-';
    }

    public function getTermTypeAttribute()
    {
        $enrollment = $this->enrollment()->first();
        return $enrollment ? $enrollment->term_type : '-';
    }

    public function getTrackNameAttribute()
    {
        $enrollment = $this->enrollment()->first();
        return $enrollment ? $enrollment->track_code : '-';
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
