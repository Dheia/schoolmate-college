<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;
use Backpack\CRUD\CrudPanel;

use App\SelectedOtherService;
use App\Models\SchoolYear;
use App\Models\Enrollment;

use App\Http\Controllers\QuickBooks\QuickBooksOnline;

// QUICKBOOKS FACADES
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;
use QuickBooksOnline\API\Data\IPPPaymentLineDetail;
use QuickBooksOnline\API\Data\IPPLine;
use QuickBooksOnline\API\Data\IPPSalesItemLineDetail;
use QuickBooksOnline\API\Data\IPPDiscountLineDetail;
use QuickBooksOnline\API\Data\IPPDiscountOverride;

class SelectedOtherServiceApplicantCrudController extends CrudController
{
    public $enrollments;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\SelectedOtherService');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/other-service-applicant');
        $this->crud->setEntityNameStrings('other service applicant', 'Other Service Applicants');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->removeButtons(['create', 'delete']);
        $this->crud->denyAccess(['create', 'update']);

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        $this->crud->removeButton('create');
        $this->crud->removeButton('update');


        $this->crud->enableAjaxTable();
        $this->crud->enableExportButtons();
        
        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */
        $active_school_year = SchoolYear::active()->first();
        $this->enrollments = $enrollments 		= Enrollment::where('school_year_id', $active_school_year->id)->get();

        $this->crud->addClause('where', 'approved', 0);
        $this->crud->addClause('whereIn', 'enrollment_id', $enrollments->pluck('id'));

        /*
        |--------------------------------------------------------------------------
        | COLUMN DETAILS
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumn([
            'label' => 'Student Number',
            'type' => 'text',
            'name' => 'studentnumber',
            'prefix' => \Setting::get('schoolabbr') . '-',
        ]);

        $this->crud->addColumn([
            'label' => 'Fullname',
            'type' => 'text',
            'name' => 'fullname'
        ]);

        $this->crud->addColumn([
            'label' => 'Department',
            'type' => 'text',
            'name' => 'department_name'
        ]);

        $this->crud->addColumn([
            'label' => 'Level',
            'type' => 'text',
            'name' => 'level_name'
        ]);

         $this->crud->addColumn([
            'label' => 'Track',
            'type' => 'text',
            'name' => 'track_name'
        ]);

        $this->crud->addColumn([
            'label' => 'Term',
            'type' => 'text',
            'name' => 'term_type'
        ]);

        /*
        |--------------------------------------------------------------------------
        | BUTTON
        |--------------------------------------------------------------------------
        */
        $this->crud->addButtonFromView('line', 'Approved', 'enrollment.otherServiceApplicant.approved', 'end');
    }

    public function approve($id)
    {
    	$selected_other_service = SelectedOtherService::findOrFail($id);

    	$response = $this->addInvoiceOtherService($id);

		if(!$response['status']) {
			\Alert::error("Error Approving. <br> Something went wrong, please try to reload the page.")->flash();
			return redirect('admin/other-service-applicant');
		}

		if($response['status'] == 'error') {
			\Alert::error($response['message'])->flash();
			return redirect('admin/other-service-applicant');
		}

		if($response['status'] == 'warning') {
			\Alert::warning($response['message'])->flash();
			return redirect('admin/other-service-applicant');
		}

		if($response['status'] == 'success') {
			\Alert::success($response['message'])->flash();
			return redirect('admin/other-service-applicant');
		}

        \Alert::error("Error Approving. <br> Something went wrong, please try to reload the page.")->flash();
    	return redirect('admin/other-service-applicant');
    }

    public function addInvoiceOtherService ($id)
    {
    	$response = [
    		'status' 	=> null,
    		'message'	=> null,
    		'data' 		=> null
    	];

        try {

            $qbo = new QuickBooksOnline;
            $qbo->initialize();

            if($qbo->dataService() === null)
            {
                // $status  = "ERROR";
                // $message = "Unauthorized QuickBooks";

                // return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
                $response = [
		    		'status' 	=> 'error',
		    		'message'	=> 'Unauthorized QuickBooks',
		    		'data' 		=> null
		    	];

		    	return $response;
            }

            if($qbo->dataService() === null)
            {
                // $status  = "ERROR";
                // $message = "Unauthorized QuickBooks";

                // return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
                $response = [
		    		'status' 	=> 'error',
		    		'message'	=> 'Unauthorized QuickBooks',
		    		'data' 		=> null
		    	];

		    	return $response;
            }

            $selectedOtherService = SelectedOtherService::where('id', $id)->with(['student', 'otherService'])->first();

            abort_if(! $selectedOtherService, 404);

            // VALIDATE IF THIS PAYMENT HAS ALREADY INVOICED
            if($selectedOtherService->invoice_no !== null) {
                $response = [
		    		'status' 	=> 'warning',
		    		'message'	=> 'This Other Service Was Already Invoiced',
		    		'data' 		=> null
		    	];

		    	return $response;
            }

            // GET THE ENROLLMENT AND OTHER SERVICE QBO ID AND QUERY TO QBO
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
                                            // "value" => "NON",
                                            "value" => config('settings.taxrate'),
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
                $status  = "ERROR";
                $message = $error->getResponseBody();

                if(str_contains($message, 'Unsupported')) {
                    $message = $error->getResponseBody() . "<br><br> You have to go into the Company <b>Settings</b> -> <b>Sales</b>-> <b>Sales form Content</b> section then tick <b>Deposit</b> under the Sales. Once this is done, you'll be able to make add other programs or services to update this tuition enrollment invoice to QBO.";
                }
                else if(str_contains($message, 'OperationOperation')) {
                    $message = $error->getResponseBody() . "<br><br> You have to go into the Company <b>Settings</b> -> <b>Sales</b>-> <b>Sales form Content</b> section then tick <b>Deposit</b> under the Sales. Once this is done, you'll be able to make add other programs or services to update this tuition enrollment invoice to QBO.";
                }
                else if(str_contains($message, 'Create/Update')) {
                    $message = $error->getResponseBody() . "<br><br> You have to go into the Company <b>Settings</b> -> <b>Sales</b>-> <b>Sales form Content</b> section then tick <b>Deposit</b> under the Sales. Once this is done, you'll be able to make add other programs or services to update this tuition enrollment invoice to QBO.";
                }

                $response = [
		    		'status' 	=> 'error',
		    		'message'	=> $message,
		    		'data' 		=> null
		    	];

		    	return $response;
            }

            $selectedOtherService->invoice_no = $resultingObj->DocNumber;
            $selectedOtherService->approved   = 1;
            $selectedOtherService->user_id    = backpack_auth()->user()->id;
            $selectedOtherService->save();

            $response = [
                'status'    => 'success',
                'message'   => 'Selected Service has been Approved and Invoiced Successfully',
                'data'      => $selectedOtherProgram
            ];

            return $response;

        } catch (\Exception $e) {
            // $status  = "ERROR";
            // $message = $e->getMessage();
            // return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
            $response = [
	    		'status' 	=> 'error',
	    		'message'	=> $e->getMessage(),
	    		'data' 		=> null
	    	];

	    	return $response;
        }
    }
}
