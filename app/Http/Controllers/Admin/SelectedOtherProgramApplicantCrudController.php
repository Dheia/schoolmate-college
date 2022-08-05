<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Http\Request;
use Backpack\CRUD\CrudPanel;

use App\SelectedOtherProgram;
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

class SelectedOtherProgramApplicantCrudController extends CrudController
{
	public $enrollments;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\SelectedOtherProgram');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/other-program-applicant');
        $this->crud->setEntityNameStrings('other program applicant', 'Other Program Applicants');

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
        $this->crud->addButtonFromView('line', 'Approved', 'enrollment.otherProgramApplicant.approved', 'end');
    }

    public function approve($id)
    {
    	$selected_other_program = SelectedOtherProgram::findOrFail($id);

    	$response = $this->addInvoiceOtherProgram($id);

		if(!$response['status']) {
			\Alert::error("Error Approving. <br> Something went wrong, please try to reload the page.")->flash();
			return redirect('admin/other-program-applicant');
		}

		if($response['status'] == 'error') {
			\Alert::error($response['message'])->flash();
			return redirect('admin/other-program-applicant');
		}

		if($response['status'] == 'warning') {
			\Alert::warning($response['message'])->flash();
			return redirect('admin/other-program-applicant');
		}

		if($response['status'] == 'success') {
			\Alert::success($response['message'])->flash();
			return redirect('admin/other-program-applicant');
		}

        \Alert::error("Error Approving. <br> Something went wrong, please try to reload the page.")->flash();
    	return redirect('admin/other-program-applicant');
    }

    public function addInvoiceOtherProgram ($id)
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

            $selectedOtherProgram = SelectedOtherProgram::where('id', $id)->with(['otherProgram'])->first();

            abort_if(! $selectedOtherProgram, 404);

            // VALIDATE IF THIS PAYMENT HAS ALREADY INVOICED
            if($selectedOtherProgram->invoice_no !== null) {
            	$response = [
		    		'status' 	=> 'warning',
		    		'message'	=> 'This Other Program Was Already Invoiced',
		    		'data' 		=> null
		    	];

		    	return $response;
            }

            // GET THE ENROLLMENT AND OTHER PROGRAM QBO ID AND QUERY TO QBO
            $enrollment_id       = $selectedOtherProgram->enrollment_id;
            $enrollment          = Enrollment::where('id', $enrollment_id)->with('student')->firstOrFail();
            $student_qbo_id      = $enrollment->student->qbo_customer_id;

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

            SelectedOtherProgram::where('id', $enrollment_id)->update(['invoice_no' => $resultingObj->DocNumber]);
            $selectedOtherProgram->invoice_no = $resultingObj->DocNumber;
            $selectedOtherProgram->approved   = 1;
            $selectedOtherProgram->user_id    = backpack_auth()->user()->id;
            $selectedOtherProgram->save();

            $response = [
	    		'status' 	=> 'success',
	    		'message'	=> 'Selected Program has been Successfully Approved and Invoiced',
	    		'data' 		=> $selectedOtherProgram
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
