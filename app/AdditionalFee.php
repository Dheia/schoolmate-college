<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

class AdditionalFee extends Model
{
	use SoftDeletes;
	protected $appends = ['remaining_balance'];


    public static function addInvoiceAdditionalFee ($additionalFee)
    {
        // CHECK IF ENROLLMENT IS IN THE URL PARAMETER
        $enrollment = Enrollment::where('id', $additionalFee->enrollment_id)->with('student')->first();
        if($enrollment == null) {
            \Log::info('Additional Fee Error (Created): This Enrollment (' . $enrollment->id . ') Fee Is Not Yet Invoiced On QuickBooks');
            return null;
        }

        try {

            $qbo = new QuickBooksOnline;
            $qbo->initialize();

            if($qbo->dataService() === null)
            {
                \Log::info('Additional Fee Error (Created): Unauthorized QuickBooks');
                return null;
            }

            // VALIDATE IF THIS PAYMENT HAS ALREADY INVOICED
            if($additionalFee->invoice_no !== null) {
                \Log::info('Additional Fee Error (Created): This Payment (' . $additionalFee->id . ') Was Already Invoiced');
                return null;
            }

            // GET THE ENROLLMENT AND OTHER PROGRAM QBO ID AND QUERY TO QBO
            $enrollment_id       = $enrollment->id;
            $enrollment          = Enrollment::where('id', $enrollment_id)->with('student')->firstOrFail();
            $student_qbo_id      = $enrollment->student->qbo_customer_id;

             $items =    [
                            "AutoDocNumber" => true,
                            "Line" => [
                                [
                                    "DetailType"          => "SalesItemLineDetail", 
                                    "Amount"              => $additionalFee->amount, 
                                    "SalesItemLineDetail" => [
                                        "ItemRef" => 
                                        [
                                            // "name"   => $selectedOtherService->otherService->name,
                                            "value"  => $additionalFee->qbo_id 
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
                            "PrivateNote" => "Additional Fee: " . $additionalFee->description
                        ];

            $theResourceObj = Invoice::create($items);
            $resultingObj   = $qbo->dataService()->Add($theResourceObj);
            $error          = $qbo->dataService()->getLastError();

            if ($error) {
                \Log::info('Additional Fee Observer Error (Created): ' . $error->getResponseBody());
                return null;
            }

            AdditionalFee::where('id', $additionalFee->id)->update(['invoice_no' => $resultingObj->DocNumber]);
            \Log::info('Additional Fee Observer Success (Created): Successfully Invoiced AdditionalFee');

        } catch (\Exception $e) {
            $message = $e->getMessage();
            \Log::info("Additional Fee Observer Error (Created): " . json_encode($e));
        }
    }

    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentHistory ()
    {
        return $this->hasMany('App\Models\PaymentHistory', 'payment_historable_id')->where('payment_historable_type', 'App\AdditionalFee');
    }

    public function getRemainingBalanceAttribute ()
    {
        return  $this->amount - $this->paymentHistory()->get()->sum('amount');
    }
}
