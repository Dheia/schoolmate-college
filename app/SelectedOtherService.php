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

class SelectedOtherService extends Model
{
    use CrudTrait;
    use \Awobaz\Compoships\Compoships;

    protected $table = 'selected_other_services';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
    	'enrollment_id',
		'other_service_id',
        'added_by',
        'approved'
    ];

    protected $appends = ['name', 'amount' ,'remaining_balance'];


    public static function addInvoiceOtherService ($selectedOtherService)
    {
        // CHECK IF ENROLLMENT IS IN THE URL PARAMETER
        $enrollment = Enrollment::where('id', $selectedOtherService->enrollment_id)->with('student')->first();
        if($enrollment == null) {
            \Log::info('Selected Other Program Error (Created): This Enrollment (' . $enrollment->id . ') Fee Is Not Yet Invoiced On QuickBooks');
            return null;
        }

        try {

            $qbo = new QuickBooksOnline;
            $qbo->initialize();

            if($qbo->dataService() === null)
            {
                \Log::info('Selected Other Service Error (Created): Unauthorized QuickBooks');
                return null;
            }

            $selectedOtherService = SelectedOtherService::where('id', $selectedOtherService->id)->with(['student', 'otherService'])->first();

            // VALIDATE IF THIS PAYMENT HAS ALREADY INVOICED
            if($selectedOtherService->invoice_no !== null) {
                \Log::info('Selected Other Service Error (Created): This Payment (' . $selectedOtherService->id . ') Was Already Invoiced');
                return null;
            }

            // GET THE ENROLLMENT AND OTHER PROGRAM QBO ID AND QUERY TO QBO
            $enrollment_id       = $selectedOtherService->enrollment_id;
            $enrollment          = Enrollment::where('id', $enrollment_id)->with('student')->firstOrFail();
            $student_qbo_id      = $enrollment->student->qbo_customer_id;

             $items =    [
                            "AutoDocNumber" => true,
                            "Line" => [
                                [
                                    "DetailType"          => "SalesItemLineDetail", 
                                    "Amount"              => $selectedOtherService->otherService->amount, 
                                    "SalesItemLineDetail" => [
                                        "ItemRef" => 
                                        [
                                            // "name"   => $selectedOtherService->otherService->name,
                                            "value"  => $selectedOtherService->otherService->qbo_map 
                                        ],
                                        "TaxCodeRef" => [
                                            "value" => "NON",
                                            // "value" => config('settings.taxrate'),
                                        ]
                                    ]
                                ]
                            ], 
                            "CustomerRef" => [
                                "value" => (string)$student_qbo_id
                            ],
                            "PrivateNote" => "Other Services: " . $selectedOtherService->otherService->name
                        ];

            $theResourceObj = Invoice::create($items);
            $resultingObj   = $qbo->dataService()->Add($theResourceObj);
            $error          = $qbo->dataService()->getLastError();

            if ($error) {
                \Log::info('Selected Other Service Observer Error (Created): ' . $error->getResponseBody());
                return null;
            }

            SelectedOtherService::where('id', $selectedOtherService->id)->update(['invoice_no' => $resultingObj->DocNumber]);
            \Log::info('Selected Other Service Observer Success (Created): Successfully Invoiced Other Service');

        } catch (\Exception $e) {
            $message = $e->getMessage();
            \Log::info("Selected Other Service Observer Error (Created): " . json_encode($e));
        }
    }

    public function otherService ()
    {   
        return $this->belongsTo('App\Models\OtherService');
    }

    public function otherServices ()
    {   
        return $this->hasMany('App\Models\OtherService', ['id'], ['other_service_id']);
    }

    public function user ()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function student ()
    {
        return $this->belongsTo("App\Models\Student",  "student_no", "studentnumber");
    }

    public function enrollment ()
    {
        return $this->belongsTo('App\Models\Enrollment');
    }

    public function paymentHistory ()
    {
        return $this->hasMany('App\Models\PaymentHistory', 'payment_historable_id')->where('payment_historable_type', 'App\SelectedOtherService');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS WITH TRASHED
    |--------------------------------------------------------------------------
    */

    public function otherServiceWithTrashed ()
    {   
         return $this->otherService()->withTrashed();
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getRemainingBalanceAttribute ()
    {
        return  $this->otherService()->first()->amount - $this->paymentHistory()->get()->sum('amount');
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

    public function getServiceNameAttribute()
    {
        $other_service = $this->otherService()->first();
        return $other_service ? $other_service->name : '-';
    }

    public function getNameAttribute()
    {
        $other_service = $this->otherService()->first();
        return $other_service ? $other_service->name : '-';
    }

    public function getAmountAttribute()
    {
        $other_service = $this->otherService()->first();
        return $other_service ? $other_service->amount : 0;
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
}
