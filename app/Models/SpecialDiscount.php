<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Http\Controllers\QuickBooks\QuickBooksOnline;

use App\Models\Enrollment;

// QUICKBOOKS FACADES
use QuickBooksOnline\API\Facades\Invoice;

class SpecialDiscount extends Model
{
    use CrudTrait;
    use \Awobaz\Compoships\Compoships;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'special_discounts';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected $dates = ['deleted_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
   
    public function addInvoiceSpecialDiscount ($specialDiscount)
    {
        // CHECK IF ENROLLMENT IS IN THE URL PARAMETER
        $enrollment = Enrollment::where('id', $selectedOtherProgram->enrollment_id)->with('student')->first();
        if($enrollment == null) {
            \Log::info('Speciald Discount Error (Created): This Enrollment (' . $enrollment->id . ') Fee Is Not Yet Invoiced On QuickBooks');
            return null;
        }

        try {

            $qbo = new QuickBooksOnline;
            $qbo->initialize();

            if($qbo->dataService() === null) {
                \Log::info('Speciald Discount Error (Created): Unauthorized QuickBooks');
                return null;
            }

            $specialDiscount = SpecialDiscount::where('id', $id)->with('student')->first();
            
            // VALIDATE IF THIS PAYMENT HAS ALREADY INVOICED
            if($specialDiscount->invoice_no !== null) {
                \Log::info('Speciald Discount Error (Created): This Payment (' . $specialDiscount->id . ') Was Already Invoiced');
                return null;
            }

            // GET THE ENROLLMENT AND OTHER PROGRAM QBO ID AND QUERY TO QBO
            $enrollment_id  = $enrollment->id;
            $enrollment     = Enrollment::where('id', $enrollment_id)->with('student')->firstOrFail();
            $student_qbo_id = $enrollment->student->qbo_customer_id;

            $items =    [
                            "AutoDocNumber" => true,
                            "Line" => [
                                [
                                    "DetailType"          => "SalesItemLineDetail", 
                                    "Amount"              => $specialDiscount->amount, 
                                    "SalesItemLineDetail" => [
                                        "ItemRef" => 
                                        [
                                            // "name"   => $specialDiscount->name,
                                            "value"  => $specialDiscount->qbo_id
                                        ],
                                        "TaxCodeRef" => [
                                            // "value" => "NON",
                                            "value" => config('settings.taxrate'),
                                        ]
                                    ]
                                ]
                            ], 
                            "CustomerRef" => [
                                "value" => (string)$student_qbo_id
                            ],
                            "PrivateNote" => "Discount: " . $specialDiscount->description
                        ];

            $theResourceObj = CreditMemo::create($items);
            $resultingObj   = $qbo->dataService()->Add($theResourceObj);
            $error          = $qbo->dataService()->getLastError();

            if ($error) {
                \Log::info('Special Discount Observer Error (Created): ' . $error->getResponseBody());
                return null;
            }

            SpecialDiscount::where('id', $discrepancy->id)->update(['invoice_no' => $resultingObj->Id]);
            \Log::info('Special Discount Observer Success (Created): Successfully SpecialDiscount');

        } catch (\Exception $e) {
            $message = $e->getMessage();
            \Log::info("Speciald Discount Observer Error (Created): " . json_encode($e));
        }
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
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
        return $this->belongsTo(Enrollment::class);
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
