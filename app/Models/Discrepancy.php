<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

use App\Http\Controllers\QuickBooks\QuickBooksOnline;

use App\Models\Enrollment;

// QUICKBOOKS FACADES
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\CreditMemo;

class Discrepancy extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'discrepancies';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
   
    public static function addInvoiceDiscrepancy ($discrepancy)
    {

        $enrollment = Enrollment::where('id', $discrepancy->enrollment_id)->first();
        if($enrollment == null) {
            \Log::info('Discrepancy Error (Created): This Enrollment (' . $enrollment->id . ') Fee Is Not Yet Invoiced On QuickBooks');
            return null;
        }

        try {

            $qbo = new QuickBooksOnline;
            $qbo->initialize();

            $enrollment_id       = $enrollment->id;
            $enrollment          = Enrollment::where('id', $enrollment_id)->with('student')->firstOrFail();
            $student_qbo_id      = $enrollment->student->qbo_customer_id;

            // VALIDATE IF THIS PAYMENT HAS ALREADY INVOICED
            if($discrepancy->invoice_no !== null) {
                \Log::info('Discrepancy Error (Created): Unauthorized QuickBooks');
                return null;
            }

            $items =    [
                            "AutoDocNumber" => true,
                            "Line" => [
                                [
                                    "DetailType"          => "SalesItemLineDetail", 
                                    "Amount"              => $discrepancy->amount, 
                                    "SalesItemLineDetail" => [
                                        "ItemRef" => 
                                        [
                                            // "name"   => $discrepancy->name,
                                            "value"  => $discrepancy->qbo_id
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
                            "PrivateNote" => "Adjustment: " . $discrepancy->description
                        ];

            $theResourceObj = CreditMemo::create($items);
            $resultingObj   = $qbo->dataService()->Add($theResourceObj);
            $error          = $qbo->dataService()->getLastError();

            if ($error) {
                \Log::info('Discrepancy Observer Error (Created): ' . $error->getResponseBody());
                return null;
            }

            Discrepancy::where('id', $discrepancy->id)->update(['invoice_no' => $resultingObj->Id]);
            \Log::info('Discrepancy Observer Success (Created): Successfully Discrepancy');
            
        } catch (\Exception $e) {
            $message = $e->getMessage();
            \Log::info("Discrepancy Observer Error (Created): " . json_encode($e->getMessage()));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user ()
    {
        return $this->belongsTo(User::class);
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
