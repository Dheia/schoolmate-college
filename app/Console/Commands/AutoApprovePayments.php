<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\QuickBooks\QuickBooksOnline;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;

use App\Models\PaymentHistory;
use App\Models\AutoApprovePaymentLog;

class AutoApprovePayments extends Command
{

    private $qbo;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoapprovepayments:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Set Payment Invoice To Quickbooks';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    
    public function handle()
    {
        if(config('settings.autoapprovepayments') == 1) {
            try {
                $this->qbo = new QuickBooksOnline;
                $this->qbo->initialize();

                if($this->qbo->dataService() === null) { 
                    \Log::info("Error Data Service"); 
                }

                $paymentHistories = PaymentHistory::where('invoice_no', null)
                                                    ->with(['enrollment' => function ($query) {
                                                        $query->where('invoice_no', '!=', null);
                                                        $query->with('student');
                                                    }])
                                                    ->get();

                    // self::setInvoice($paymentHistories);/
                foreach ($paymentHistories as $paymentHistory) {
                    if(!$paymentHistory->enrollment) { return; }
                    if($paymentHistory->enrollment->invoice_no == null) { return; }

                    $log = AutoApprovePaymentLog::where('payment_history_id', $paymentHistory->id)->first();

                    if($log) {
                        if($log->status == 'failed') {
                            $log->attempt += 1;
                            $log->status = 'retry';
                            $log->save();
                            self::setInvoice($paymentHistory);                
                        }
                    } else {
                        $newLog = new AutoApprovePaymentLog;
                        $newLog->payment_history_id = $paymentHistory->id;
                        $newLog->status = 'pending';
                        $newLog->save();
                        self::setInvoice($paymentHistory);
                    }
                }        
            } catch (\Exception $e) {
                $message = $e->getMessage();
                \Log::info($message);
            }
        }
    }

    private function setInvoice ($paymentHistory)
    {
        // Check Enrollment Is Null 
        if(!$paymentHistory->enrollment) {
            \Log::info('Error Auto Payment: No Enrollment Found Of Payment (Payment ID: ' . $paymentHistory->id . ')');
        }

        // Check Enrollment Invoice Is Not Set
        if($paymentHistory->enrollment->invoice_no == null) {
            \Log::info('Error Auto Payment: Enrollment Is Not Set To Quickbooks (ID: ' . $paymentHistory->enrollment->id . ')');
        }

        try {
            // $invoice_id = $this->qbo->dataService()->Query("SELECT * FROM Invoice WHERE DocNumber = '" . $paymentHistory->enrollment->invoice_no . "'");
            // $LinkedTxn  = $invoice_id[0]->Id;

            // GET THE INVOICE_NO OF RELATED TO THEY MODEL
            if($paymentHistory->payment_historable_id !== null && $paymentHistory->payment_historable_type !== null)
            {
                // AUTOMATICALLY GET THE SelectedOther{Program, Service} MODEL MORPH RELATED TO THEIR MODEL AND GET THE INVOICE_NO
                $service_invoice_no = $paymentHistory->payment_historable->invoice_no;
                // $invoice_id         = $qbo->dataService()->Query("SELECT * FROM Invoice WHERE DocNumber = '" . $service_invoice_no . "'");
            }

            $items = [
                "TotalAmt" => $paymentHistory->amount,
                "CustomerRef" => [
                    "value" => $paymentHistory->enrollment->student->qbo_customer_id
                ],
                // "Line" => [
                //     "Description" => null,
                //     "Amount"      => $paymentHistory->amount,
                //     "LinkedTxn"   => [
                //         [
                //             "TxnId"   => $invoice_id[0]->Id, 
                //             "TxnType" => "Invoice"
                //         ]
                //     ]
                // ],
                "PrivateNote"   => "Payment: " . $paymentHistory->description
            ];

            $theResourceObj = Payment::create($items);
            $resultingObj   = $this->qbo->dataService()->Add($theResourceObj);
            $error          = $this->qbo->dataService()->getLastError();

            if ($error) {
                \Log::info($error);
                AutoApprovePaymentLog::where('payment_history_id', $paymentHistory->id)->update(['status' => 'failed']);
            }

            $paymentHistory->invoice_no = $resultingObj->Id;
            $paymentHistory->save();
            AutoApprovePaymentLog::where('payment_history_id', $paymentHistory->id)->update(['status' => 'good']);
            \Log::info("Auto Payment: Successfully Added Invoiced Payment");
        } catch (\Exception $e) {
            \Log::info($e);
        }
    }
}
