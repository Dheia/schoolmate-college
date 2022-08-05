<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PaymentHistoryRequest as StoreRequest;
use App\Http\Requests\PaymentHistoryRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\Enrollment;
use App\Models\SchoolYear;

use App\Http\Controllers\QuickBooks\QuickBooksOnline;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;

use App\Models\AutoApprovePaymentLog;
/**
 * Class PaymentHistoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PaymentHistoryCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PaymentHistory');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/payment-history');
        $this->crud->setEntityNameStrings('Payment History', 'Payment Histories');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in PaymentHistoryRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->addButtonFromView('line', 'invoice', 'paymentHistory.invoice', 'beginning');
        $this->crud->addButtonFromView('line', 'receipt', 'paymentHistory.show_receipt', 'beginning');
        $this->crud->denyAccess(['create', 'update', 'delete']);
        
        $this->crud->removeColumns(['enrollment_id']);

        $this->crud->addColumn([
            'label' => 'Student No.',
            'type'  => 'text',
            'name' => 'student_number',
            'prefix' => config('settings.schoolabbr') . '-',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('enrollment', function ($q) use ($column, $searchTerm) {
                    $q->where('studentnumber', 'like', '%'.$searchTerm.'%');
                });
            }
        ])->beforeColumn('user_id');

        $this->crud->addColumn([
            'label' => 'Full Name',
            'type'  => 'text',
            'name' => 'full_name',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('enrollment.student', function ($q) use ($column, $searchTerm) {
                    $q->where('firstname', 'like', '%'.$searchTerm.'%')
                        ->orWhere('lastname', 'like', '%'.$searchTerm.'%');
                });
            }
        ])->afterColumn('student_number');

        $this->crud->addColumn([
            'label' => 'Invoice No.',
            'type'  => 'text',
            'name' => 'invoice_no',
        ])->afterColumn('full_name');

        $this->crud->addColumn([
            'label' => 'Payment For',
            'type'  => 'text',
            'name' => 'payment_for',
        ])->afterColumn('invoice_no');

        $this->crud->addColumn([
            'label'     => 'Created By',
            'type'      => 'select',
            'name'      => 'user_id',
            'attribute' => 'email',
            'entity'    => 'user',
            'model'    => 'App\Models\User'
        ]);

        $this->crud->addColumn([
            'label'     => 'Tuition',
            'type'      => 'text',
            'name'      => 'tuition_name',
        ]);

        $this->crud->addColumn([
            'label'     => 'Grade Level',
            'type'      => 'text',
            'name'      => 'level',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('enrollment.level', function ($q) use ($column, $searchTerm) {
                    $q->where('year', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);

        $this->crud->addColumn([
            'label'     => 'School Year',
            'type'      => 'text',
            'name'      => 'schoolYear',
        ]);

        $this->crud->addColumn([
            'label' => 'Payment Method',
            'type'  => 'select',
            'name'  => 'payment_method_id',
            'attribute' => 'name',
            'entity'    => 'paymentMethod',
            'model'    => 'App\Models\PaymentMethod'
        ]);

        $this->crud->addColumn([
            'label' => 'Amount',
            'type'  => 'number',
            'name'  => 'amount',
        ]);

        $this->crud->addColumn([
            'label' => 'Fee',
            'type'  => 'number',
            'name'  => 'fee',
        ]);

        $this->crud->addColumn([
            'label' => 'Description',
            'type'  => 'textarea',
            'name'  => 'description',
        ]);

        $this->crud->addColumn([
            'label' => 'Date Received',
            'type'  => 'datetime',
            'format' => 'MMMM D, YYYY',
            'name'  => 'date_received',
        ]);

        $this->crud->addColumn([
            'label' => 'Date Created',
            'type'  => 'datetime',
            'format' => 'MMMM D, YYYY | hh:mm a',
            'name'  => 'created_at',
        ]);

        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'from_to',
            'label'=> 'Date Created'
        ],
        false,
        function($value) { // if the filter is active, apply these constraints
            $dates = json_decode($value);
            $this->crud->addClause('where', 'created_at', '>=', $dates->from);
            $this->crud->addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');
        });

        $this->crud->addFilter([ // select2 filter
            'name' => 'schoolyear_id',
            'type' => 'select2',
            'label'=> 'School Year'
        ], function() {
              return SchoolYear::all()->keyBy('id')->pluck('schoolYear', 'id')->toArray();
        }, function($value) { // if the filter is active
            $enrollments = Enrollment::where('school_year_id', $value)->get();
            $this->crud->addClause('whereIn', 'enrollment_id', $enrollments->pluck('id'));
        });

        $this->crud->addClause('whereHas', 'enrollment.student');
        $this->crud->orderBy('invoice_no');
        $this->crud->orderBy('created_at', 'DESC');

        $this->crud->enableExportButtons();
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }


    public function receiptLayouts ()
    {
        return response()->json([
            "header"     => view('paymentHistory.receipt.partials.header')->render(),
            "style"      => view('paymentHistory.receipt.partials.style')->render(),
        ]);

    }

    public function setInvoice ($id)
    {
        $qbo = new QuickBooksOnline;
        $qbo->initialize();

        if($qbo->dataService() === null) { 
            \Alert::warning("Error Quickbooks Data Service")->flash();
            \Log::info("Error Data Service"); 
            return redirect()->back();
        }

        // Check Enrollment Is Null 
        $paymentHistory = $this->crud->model::where('id', $id)
                                            ->with(['enrollment' => function ($query) {
                                                $query->where('invoice_no', '!=', null);
                                                $query->with('student');
                                            }])
                                            ->first();
        if(!$paymentHistory->enrollment) {
            \Alert::warning('Enrollment Not Found')->flash();
            \Log::info('Error Auto Payment: No Enrollment Found Of Payment (Payment ID: ' . $paymentHistory->id . ')');
            return redirect()->back();
        }

        // Check Enrollment Invoice Is Not Set
        if($paymentHistory->enrollment->invoice_no == null) {
            \Alert::warning('Enrollment Is Not Set To Quickbooks')->flash();
            \Log::info('Error Auto Payment: Enrollment Is Not Set To Quickbooks (ID: ' . $paymentHistory->enrollment->id . ')');
            return redirect()->back();
        }

        try {
            $invoice_id = $qbo->dataService()->Query("SELECT * FROM Invoice WHERE DocNumber = '" . $paymentHistory->enrollment->invoice_no . "'");
            $LinkedTxn  = $invoice_id[0]->Id;

            // GET THE INVOICE_NO OF RELATED TO THEIR MODEL
            if($paymentHistory->payment_historable_id !== null && $paymentHistory->payment_historable_type !== null)
            {
                // AUTOMATICALLY GET THE SelectedOther{Program, Service} MODEL MORPH RELATED TO THEIR MODEL AND GET THE INVOICE_NO
                $service_invoice_no = $paymentHistory->payment_historable->invoice_no;
                $invoice_id         = $qbo->dataService()->Query("SELECT * FROM Invoice WHERE DocNumber = '" . $service_invoice_no . "'");
            }

            $items = [
                "TotalAmt" => $paymentHistory->amount,
                "CustomerRef" => [
                    "value" => $paymentHistory->enrollment->student->qbo_customer_id
                ],
                "Line" => [
                    "Description" => null,
                    "Amount"      => $paymentHistory->amount,
                    "LinkedTxn"   => [
                        [
                            "TxnId"   => $invoice_id[0]->Id, 
                            "TxnType" => "Invoice"
                        ]
                    ]
                ],
                "PrivateNote"   => "Payment: " . $paymentHistory->description
            ];

            $theResourceObj = Payment::create($items);
            $resultingObj   = $qbo->dataService()->Add($theResourceObj);
            $error          = $qbo->dataService()->getLastError();

            if ($error) {
                \Log::info($error);
                \Alert::warning('Error: ' . $error)->flash();
                return redirect()->back();
            }

            $paymentHistory->invoice_no = $resultingObj->Id;
            $paymentHistory->save();
            \Log::info("Auto Payment: Successfully Added Invoiced Payment");
            \Alert::warning('Successfully Invoiced Payment')->flash();
            return redirect()->back();

        } catch (\Exception $e) {
            \Log::info($e);
            \Alert::warning('Error: ' . $e)->flash();
            return redirect()->back();

        }
    }
}
