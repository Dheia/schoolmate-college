<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\StudentAccountRequest as StoreRequest;
use App\Http\Requests\StudentAccountRequest as UpdateRequest;
use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\StudentAccount;
use App\Models\SectionManagement;
use App\Models\Tuition;
use App\Models\Enrollment;
use App\Models\OtherProgram;
use App\Models\OtherService;
use App\AdditionalFee;
use App\Models\SpecialDiscount;
use App\Models\Discrepancy;

use App\Models\StudentSectionAssignment;

use App\PaymentHistory;
use App\SelectedPaymentType;
use App\SelectedOtherProgram;
use App\SelectedOtherService;
use App\SelectedOtherFee;

use App\Http\Controllers\QuickBooks\QuickBooksOnline;

use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;
use QuickBooksOnline\API\Facades\CreditMemo;
use QuickBooksOnline\API\Data\IPPPaymentLineDetail;
use QuickBooksOnline\API\Data\IPPLine;
use QuickBooksOnline\API\Data\IPPSalesItemLineDetail;
use QuickBooksOnline\API\Data\IPPDiscountLineDetail;
use QuickBooksOnline\API\Data\IPPDiscountOverride;

use Mail;
use App\Mail\SendMailableSOA;
use PDF;

class StudentAccountCrudController extends CrudController
{
    public function setup()
    {   

        // $user = \Auth::user();
        // $permissions = collect($user->getAllPermissions());

        // $plucked = $permissions->pluck('name');
        // $this->allowed_method_access = $plucked->all();

        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);
        // $this->crud->allowAccess($this->allowed_method_access);
        
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\StudentAccount');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/student-account');
        $this->crud->setEntityNameStrings('Student Account', 'Student Accounts');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();
        $this->crud->setListView('studentAccount.list');
        // $this->crud->setCreateView('studentAccount.list');
        $this->crud->denyAccess(['create', 'edit', 'update']);
       
        $this->crud->removeAllButtons();
        $this->crud->addField([
            'name' => 'search',
            'type' => 'studentAccount.search',
            'label' => 'Search',
            'attributes' => [
                'id' => 'search',
                'placeholder' => 'Search For Name or ID Number (ex. 1100224)',
            ]
        ])->beforeField('student_no');

        $this->crud->addField([
            'name' => 'student_no',
            'type' => 'hidden',
            'label' => 'Student No.',
            'attributes' => [
                'id' => 'student_number'
            ],
            'wrapperAttributes' => [
                'class' => 'col-md-4'
            ]
        ]);

        $this->crud->addField([
            'type' => 'select',
            'label' => 'Payment Type',
            'name' => 'payment_type',
            'entity' => 'commitment_payment',
            'attribute' => 'name',
            'model' => 'App\Models\CommitmentPayment',
            'attributes' => [
                'id' => 'payment_type'
            ], 
            'wrapperAttributes' => [
                'class' => 'col-md-4',
                'style' => 'display: none'
            ]
        ]);

        $this->crud->addField([
            'name' => 'amount',
            'type' => 'hidden',
            'label' => 'Amount',
            'attributes' => [
                'id' => 'amount'    
            ],
            'wrapperAttributes' => [
                'class' => 'col-md-4'
            ]
        ]);
    }

    public function getStudents (Request $request) 
    {   
        $students = Student::where('studentnumber', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('firstname', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('middlename', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('lastname', 'LIKE', '%' . $request->search . '%')
                            ->with('schoolYear')
                            ->with('yearManagement')
                            ->paginate(5);
        $students->setPath(url()->current());
        return response()->json($students);
    }

    public function getSections (Request $request)
    {
        $year_id  = $request->year_id;
        $level_id = $request->level_id;

        $sections = SectionManagement::where('level_id', $level_id)->where('year_id', $year_id)->get();
        return response()->json($sections);
    }


    public function getTuitions (Request $request)
    {
        $tuitions = Tuition::where('grade_level_id', $request->level_id)
                            ->where('schoolyear_id', $request->year_id)
                            ->where('grade_level_id', $request->level_id)
                            ->where('track_id', $request->track_id)
                            ->get();
        return response()->json($tuitions);
    }

    public function getStudentAccount ($studentNumber)
    {

        if(Enrollment::where('studentnumber', $studentNumber)->count() === 0) {
            return response()->json(['message' => 'No record found', 'status' => 'ERROR']);
        }

        $tuitions = Enrollment::where('studentnumber', $studentNumber)
                                ->with('tuitions')
                                ->with(['selectedOtherPrograms' => function ($query) {
                                        $query->with('otherPrograms');
                                    } 
                                ])
                                ->get();
        return response()->json($tuitions);
    }

    public function getEnrollmentsYear ($studentnumber)
    {   
        $enrollments_year = Enrollment::where('studentnumber', $studentnumber)
                                        ->where('is_applicant', 0)
                                        ->with('schoolYear')
                                        ->with('level')
                                        ->with('commitmentPayment')
                                        ->with('section')
                                        ->with('tuition')
                                        ->get();

        return response()->json($enrollments_year);
    }

    public function viewTuition ($enrollment_id)
    {
        Enrollment::findOrFail($enrollment_id);
        return view('studentAccount.student-account-tuition-list');
    }  

    public function addOtherProgram (Request $request)
    {
        $isExist = SelectedOtherProgram::where([
                        'enrollment_id'     => $request->enrollment_id,
                        'other_program_id'  => $request->selected_other_program_id,
                    ])->exists();

        if($isExist) { return ['message' => 'This Other Program Is Already Added', 'status' => 'ERROR', 'data' => null]; }

        $selectedOtherProgram                   = new SelectedOtherProgram;
        $selectedOtherProgram->user_id          = backpack_auth()->user()->id;
        $selectedOtherProgram->enrollment_id    = $request->enrollment_id;
        $selectedOtherProgram->other_program_id = $request->selected_other_program_id;
        // dd($request->request);
        if($selectedOtherProgram->save()) {

            $afterSaving = $selectedOtherProgram
                                ->with('otherProgram')
                                ->with('user')
                                ->orderBy('created_at', 'DESC')
                                ->first();

            return ['message' => 'Successfully Added Program', 'status' => 'SUCCESS', 'data' => $afterSaving];
        } else {
            return ['message' => 'Error Adding Program, Please Try Again Later', 'status' => 'ERROR'];
        }
    }

    public function addOtherService (Request $request)
    {
        $isExist = SelectedOtherService::where([
                        'enrollment_id'     => $request->enrollment_id,
                        'other_service_id'  => $request->selected_other_service_id
                    ])->exists();

        if($isExist) { return ['message' => 'This Other Service Is Already Added', 'status' => 'ERROR', 'data' => null]; }

        $selectedOtherService                   = new SelectedOtherService;
        $selectedOtherService->user_id          = backpack_auth()->user()->id;
        $selectedOtherService->other_service_id = $request->selected_other_service_id;
        $selectedOtherService->enrollment_id    = $request->enrollment_id;

        if($selectedOtherService->save()) {

            $afterSaving =  $selectedOtherService
                                ->with('otherService')
                                ->with('user')
                                ->orderBy('created_at', 'DESC')
                                ->first();

            return ['message' => 'Successfully Added Service', 'status' => 'SUCCESS', 'data' => $afterSaving];
        } else {
            return ['message' => 'Error Adding Service, Please Try Again Later', 'status' => 'ERROR'];
        }
    }

    public function addAdditionalFee (Request $request)
    {
        $additionalFee                  = new AdditionalFee;
        $additionalFee->enrollment_id   = $request->enrollment_id;
        $additionalFee->user_id         = backpack_auth()->user()->id;
        $additionalFee->qbo_id          = $request->qbo_id;
        $additionalFee->amount          = (float)$request->amountInput;
        $additionalFee->description     = $request->description;

        if($additionalFee->save()) {
            $afterSaving =  $additionalFee->with('user')->orderBy('created_at', 'DESC')->first();
            return ['message' => 'Successfully Added Additional Fee', 'status' => 'SUCCESS', 'data' => $afterSaving];
        } else {
            return ['message' => 'Error Adding Additional Fee, Please Try Again...', 'status' => 'ERROR'];
        }
    }

    public function addSpecialDiscount (Request $request)
    {
        $specialDiscount                = new SpecialDiscount;
        $specialDiscount->user_id       = backpack_auth()->user()->id;
        $specialDiscount->enrollment_id = $request->enrollment_id;
        $specialDiscount->amount        = $request->amount;
        $specialDiscount->description   = $request->description;
        $specialDiscount->apply_to      = $request->apply_to;

        $specialDiscount->discount_category = $request->discount_category;
        $specialDiscount->discount_type     = $request->discount_type;
        $specialDiscount->qbo_id            = $request->qbo_id;

        if($specialDiscount->save()) {

            $afterSaving = $specialDiscount
                                ->with('user')
                                ->orderBy('created_at', 'DESC')
                                ->first();

            return ['message' => 'Successfully Adding Special Discount', 'status' => 'SUCCESS', 'data' => $afterSaving];
        } else {
            return ['message' => 'Error Adding Special Discount, Please Try Again Later', 'status' => 'ERROR'];
        }
    }

    public function addDiscrepancy (Request $request)
    {
        $discrepancy                  = new Discrepancy;
        $discrepancy->enrollment_id   = $request->enrollment_id;
        $discrepancy->user_id         = backpack_auth()->user()->id;
        $discrepancy->qbo_id          = $request->qbo_id;
        $discrepancy->amount          = (float)$request->amountInput;
        $discrepancy->description     = $request->description;

        if($discrepancy->save()) {
            $afterSaving =  $discrepancy->with('user')->orderBy('created_at', 'DESC')->first();
            return ['message' => 'Successfully Added Discrepancy', 'status' => 'SUCCESS', 'data' => $afterSaving];
        } else {
            return ['message' => 'Error Adding Discrepancy, Please Try Again Later', 'status' => 'ERROR'];
        }
    }


    public function savePayment (Request $request)
    {
        $enrollment = Enrollment::where('id', $request->enrollment_id)->first();

        // CHECK IF STUDENT IS ENROLLED
        if($enrollment == null)
        {
            return ['message' => 'Error: This Student Is Not Enrolled', 'status' => 'ERROR'];
        }

        return self::paymentProcess($request);
    }

    private function  paymentProcess ($request)
    {   
        $payable = ['id' => null, 'type' => null];
        if($request->payment_for !== null)
        {
            $array           = explode("|",$request->payment_for);
            $payable['id']   = (int)$array[0];
            $payable['type'] = $array[1];
        } 

        $payment                          = new PaymentHistory;
        $payment->enrollment_id           = $request->enrollment_id;
        $payment->user_id                 = backpack_auth()->user()->id;
        $payment->payment_method_id       = $request->payment_method_id;
        $payment->amount                  = $request->amount;
        $payment->fee                     = $request->fee;
        $payment->description             = $request->description;
        $payment->date_received           = $request->date_received;
        $payment->payment_historable_id   = $payable["id"];

        if($payable['type'] === null) {
            $payment->payment_historable_type = null;
        } else if ($payable['type'] === 'OtherProgram' || $payable['type'] === 'OtherService') {
            $payment->payment_historable_type = 'App\\Selected' . $payable["type"];
        } else {
            $payment->payment_historable_type = 'App\\' . $payable["type"];
        }

        if($payment->save()) {
            $afterSaving = $payment->with('paymentMethod')
                                    ->with('user')
                                    ->orderBy('created_at', 'DESC')
                                    ->first();

            return ['message' => 'Successfully Adding Payment', 'status' => 'SUCCESS', 'data' => $afterSaving];
        } else {
            return ['message' => 'Error Adding Payment, Please Try Again Later', 'status' => 'ERROR'];
        }
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


    public function destroy ($id)
    {
        $model = $this->crud->model::findOrFail($id);
        $model->delete();
    }

    public function getQBDiscount() 
    {
        $qbo =  new QuickBooksOnline;
        $qbo->initialize();
        if($qbo->dataService() === null)
        {
            $status  = "ERROR";
            $message = "Unauthorized QuickBooks";

            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
        }

        $name = "Mandatory Fee " .  request()->school_year_id;
        // dd( $name);
      
        $discounts = $qbo->dataService->Query("SELECT * FROM Item WHERE Name LIKE '%discount%' MAXRESULTS 1000");
        $discounts = $discounts == null ? [] : collect($discounts);

        $error = $qbo->dataService->getLastError();
        if ($error) {
            $status  = "ERROR";
            $message = $error->getResponseBody();
            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));

        }

        if(count($discounts) > 1) {
            return $discounts->map(function ($item) { return ['Id' => $item->Id, 'Name' => $item->Name]; });
        }
        return $discounts;
    }

    public function getQBItems() 
    {
        $qbo =  new QuickBooksOnline;
        $qbo->initialize();
        if($qbo->dataService() === null)
        {
            $status  = "ERROR";
            $message = "Unauthorized QuickBooks";

            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
        }

        $name = "Mandatory Fee " .  request()->school_year_id;
        // dd( $name);
      
        $discounts = $qbo->dataService->Query("SELECT * FROM Item MAXRESULTS 1000");
        $discounts = $discounts == null ? [] : collect($discounts);

        $error = $qbo->dataService->getLastError();
        if ($error) {
            $status  = "ERROR";
            $message = $error->getResponseBody();
            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));

        }
        return $discounts->map(function ($item) { return ['Id' => $item->Id, 'Name' => $item->Name]; });
    }


    // (STUDENT ACCOUNT) -> TUITION TABLE API
    
    public function allTuitionFeeData($enrollment_id)
    {
        $checkEnrollmentStudent = Enrollment::where('id', $enrollment_id)->first();
        
        $_enrollment = Enrollment::where('id',$enrollment_id)
                                ->with(['tuition' => function ($q) {
                                    $q->with('school_year');
                                    $q->with('year_management');
                                }])
                                ->with('commitmentPayment');
                                // ->with('student:id,studentnumber,schoolyear,level_id,firstname,lastname,middlename,gender')
                                // ->first(); 

        if($checkEnrollmentStudent) {
            if($checkEnrollmentStudent->studentnumber === null) {
                $_enrollment = $_enrollment->with('student:id,studentnumber,schoolyear,level_id,firstname,lastname,middlename,gender')->first();
            } else {
                $_enrollment = $_enrollment->with('studentById:id,studentnumber,schoolyear,level_id,firstname,lastname,middlename,gender')->first();
            }
        }

        if($_enrollment == null) {
            return "Student Number " . $studentnumber . " Not Found";
        }

        $student = $_enrollment->student;
        $tuition = $_enrollment->tuition;

        $selected_other_programs      = SelectedOtherProgram::where('enrollment_id', $enrollment_id)->where('approved', 1)->with('user')->with('otherProgram')->get();
        $total_selected_other_program = $selected_other_programs->sum('otherProgram.amount');

        $selected_other_services      = SelectedOtherService::where('enrollment_id', $enrollment_id)->where('approved', 1)->with('user')->with('otherServices')->get();
        $total_selected_other_service = $selected_other_services->sum('otherService.amount');

        $additional_fees        = AdditionalFee::where('enrollment_id', $enrollment_id)->with('user')->get();
        $total_additional_fee   = $additional_fees->sum('amount');
        
        $discrepancies          = Discrepancy::where('enrollment_id', $enrollment_id)->with('user')->get();
        $total_discrepancy      = $discrepancies->sum('amount');

        $other_program_lists     = OtherProgram::where('qbo_map', '!=', null)->where('school_year_id', $_enrollment->school_year_id)->get();
        $other_service_lists     = OtherService::where('qbo_map', '!=', null)->where('school_year_id', $_enrollment->school_year_id)->get();
        $special_discounts_lists = SpecialDiscount::where('enrollment_id', $enrollment_id)->with('user')->get();
        $payment_histories       = PaymentHistory::where('enrollment_id', $enrollment_id)->with('user')->with('paymentMethod')->get();

        $total_special_discount = $special_discounts_lists->sum('amount');
        $total_payment_history  = $payment_histories->sum('amount');

        $qbo_discount_items = $this->getQBDiscount();
        if(method_exists($qbo_discount_items, 'getData')) {
            $qbo_discount_items = [];
        }

        $qbo_items = $this->getQBItems();
        if(method_exists($qbo_items, 'getData')) {
            $qbo_items = [];
        }


        $tuition_list = [
            'enrollment_id'                 => $_enrollment->id,
            'enrollment'                    => $_enrollment,
            'commitment_payment'            => $_enrollment->commitmentPayment,
            'remaining_balance'             => $_enrollment->remaining_balance,
            'student'                       => $student,
            'tuition'                       => $tuition,
            'selected_other_programs'       => $selected_other_programs,
            'selected_other_services'       => $selected_other_services,
            'additional_fees'               => $additional_fees,
            'discrepancies'                 => $discrepancies,
            'total_selected_other_program'  => $total_selected_other_program,
            'total_selected_other_service'  => $total_selected_other_service,
            'total_additional_fee'          => $total_additional_fee,
            'total_discrepancy'             => $total_discrepancy,
            'other_program_lists'           => $other_program_lists,
            'other_service_lists'           => $other_service_lists,
            'special_discount_lists'        => $special_discounts_lists,
            'total_special_discount'        => $total_special_discount,
            'payment_histories'             => $payment_histories,
            'total_payment_history'         => $total_payment_history,
            'qbo_discount_items'            => $qbo_discount_items,
            'qbo_items'                     => $qbo_items,
        ];

        return response()->json($tuition_list);   
    }


    /**
    * ADD INVOICE: payment, special discount, other programs and other services 
    **/


    public function addInvoicePayment ($id, Request $request)
    {
        if(request()->input('enrollment_id') == null) {
            return "Missing Parameters";
        }

        if((int)request()->input('enrollment_id') == 0) {
            return "Invalid Format";
        }

        $enrollment = Enrollment::where('id', request()->input('enrollment_id'))->with('student')->first();
        if($enrollment == null) {
            return "This Enrollment Fee Is Not Yet Invoiced On QuickBooks";
        }

        try {

            $qbo = new QuickBooksOnline;
            $qbo->initialize(); 

            if($qbo->dataService() === null)
            {
                $status  = "ERROR";
                $message = "Unauthorized QuickBooks";

                return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
            }

            if($qbo->dataService() === null)
            {
                $status  = "ERROR";
                $message = "Unauthorized QuickBooks";

                return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
            }

            $payment_history = PaymentHistory::where('id', $id)->with('student')->first();
            $invoice_id      = $qbo->dataService()->Query("SELECT * FROM Invoice WHERE DocNumber = '" . $enrollment->invoice_no . "'");

            // VALIDATE IF THIS PAYMENT HAS ALREADY INVOICED
            if($payment_history->invoice_no !== null) {
                \Alert::warning("This Payment Was Already Invoiced")->flash();
                return redirect()->back();
            }

        // dd($request);
            // $LinkedTxn = $invoice_id[0]->Id;

            // GET THE INVOICE_NO OF RELATED TO THEY MODEL
            if($payment_history->payment_historable_id !== null && $payment_history->payment_historable_type !== null)
            {
                // AUTOMATICALLY GET THE SelectedOther{Program, Service} MODEL MORPH RELATED TO THEIR MODEL AND GET THE INVOICE_NO
                $service_invoice_no = $payment_history->payment_historable->invoice_no;
                // $invoice_id         = $qbo->dataService()->Query("SELECT * FROM Invoice WHERE DocNumber = '" . $service_invoice_no . "'");
            }

            $items = [
                "TotalAmt"      => $payment_history->amount,
                "CustomerRef"   => [
                    "value" => $enrollment->student->qbo_customer_id
                ],
                // "Line" => [
                //     "Description" => null,
                //     "Amount"      => $payment_history->amount,
                //     "LinkedTxn"   => [
                //         [
                //             "TxnId"   => $invoice_id[0]->Id, 
                //             "TxnType" => "Invoice"
                //         ]
                //     ]
                // ],
                "PrivateNote"   => "Payment: " . $payment_history->description
            ];

            $theResourceObj = Payment::create($items);
            $resultingObj   = $qbo->dataService()->Add($theResourceObj);
            $error          = $qbo->dataService()->getLastError();

            if ($error) {
                \Alert::warning($error->getResponseBody())->flash();
                return redirect()->back();
            }

            $payment_history->invoice_no = $resultingObj->Id;
            $payment_history->save();
            \Alert::success("Successfully Invoiced")->flash();
            return redirect()->back();

        } catch (\Exception $e) {
            $status  = "ERROR";
            $message = $e->getMessage();
            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
        }
    }

    public function deleteInvoicePayment ($id, Request $request)
    {
        if(request()->input('enrollment_id') == null) {
            return "Missing Parameters";
        }

        if((int)request()->input('enrollment_id') == 0) {
            return "Invalid Format";
        }

        $enrollment = Enrollment::where('id', request()->input('enrollment_id'))->with('student')->first();
        if($enrollment == null) {
            return "This Enrollment Fee Is Not Yet Invoiced On QuickBooks";
        }

       $delete = PaymentHistory::where('id', $id)->whereNull('invoice_no')->delete();

       if($delete) {
            \Alert::success("Successfully Deleted")->flash();
            return back();
       }

       \Alert::success("Error Deleting...")->flash();
        return back();
    }

    public function addInvoiceSpecialDiscount ($id, Request $request)
    {
        if(request()->input('enrollment_id') == null) {
            return "Missing Parameters";
        }

        if((int)request()->input('enrollment_id') == 0) {
            return "Invalid Format";
        }

        $enrollment     = Enrollment::where('id', request()->input('enrollment_id'))->with('student')->first();
        $student_qbo_id = $enrollment->student->qbo_customer_id;
        if($enrollment == null) {
            return "This Enrollment Fee Is Not Yet Invoiced On QuickBooks";
        }

        try {

            $qbo = new QuickBooksOnline;
            $qbo->initialize();

            if($qbo->dataService() === null)
            {
                $status  = "ERROR";
                $message = "Unauthorized QuickBooks";

                return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
            }

            $special_discount = SpecialDiscount::where('id', $id)->with('student')->first();
            
            // VALIDATE IF THIS PAYMENT HAS ALREADY INVOICED
            if($special_discount->invoice_no !== null) {
                \Alert::warning("This Special Discount Was Already Invoiced")->flash();
                return redirect()->back();
            }

            // dd(new IPPLine);
            $items =    [
                            "AutoDocNumber" => true,
                            "Line" => [
                                [
                                    "DetailType"          => "SalesItemLineDetail", 
                                    "Amount"              => $special_discount->amount, 
                                    "SalesItemLineDetail" => [
                                        "ItemRef" => 
                                        [
                                            // "name"   => $discrepancy->name,
                                            "value"  => $special_discount->qbo_id
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
                            "PrivateNote" => "Adjustment: " . $special_discount->description
                        ];

            $theResourceObj = CreditMemo::create($items);
            $resultingObj   = $qbo->dataService()->Add($theResourceObj);
            $error          = $qbo->dataService()->getLastError();

            if ($error) {
                \Alert::warning($error->getResponseBody())->flash();
                return redirect()->back();
            }

            SpecialDiscount::where('id', $special_discount->id)->update(['invoice_no' => $resultingObj->Id]);
            
            \Alert::success("Successfully Invoiced")->flash();
            return redirect()->back();

        } catch (\Exception $e) {
            $status  = "ERROR";
            $message = $e->getMessage();
            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
        }
    }

    public function deleteInvoiceSpecialDiscount ($id, Request $request)
    {
        if(request()->input('enrollment_id') == null) {
            return "Missing Parameters";
        }

        if((int)request()->input('enrollment_id') == 0) {
            return "Invalid Format";
        }

        $enrollment = Enrollment::where('id', request()->input('enrollment_id'))->with('student')->first();
        if($enrollment == null) {
            return "This Enrollment Fee Is Not Yet Invoiced On QuickBooks";
        }

       $delete = SpecialDiscount::where('id', $id)->whereNull('invoice_no')->delete();

       if($delete) {
            \Alert::success("Successfully Deleted")->flash();
            return back();
       }

       \Alert::success("Error Deleting...")->flash();
        return back();
    }

    public function addInvoiceDiscrepancy ($id, Request $request)
    {
        if(request()->input('enrollment_id') == null) {
            return "Missing Parameters";
        }

        if((int)request()->input('enrollment_id') == 0) {
            return "Invalid Format";
        }

        $enrollment     = Enrollment::where('id', request()->input('enrollment_id'))->with('student')->first();
        $student_qbo_id = $enrollment->student->qbo_customer_id;
        
        if($enrollment == null) {
            return "This Enrollment Fee Is Not Yet Invoiced On QuickBooks";
        }

        try {

            $qbo = new QuickBooksOnline;
            $qbo->initialize();

            if($qbo->dataService() === null)
            {
                $status  = "ERROR";
                $message = "Unauthorized QuickBooks";

                return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
            }

            $discrepancy = Discrepancy::where('id', $id)->with('user')->first();
            
            // VALIDATE IF THIS PAYMENT HAS ALREADY INVOICED
            if($discrepancy->invoice_no !== null) {
                \Alert::warning("This Discrepancy Was Already Invoiced")->flash();
                return redirect()->back();
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
                \Alert::warning($error->getResponseBody())->flash();
                return redirect()->back();
            }

            Discrepancy::where('id', $discrepancy->id)->update(['invoice_no' => $resultingObj->Id]);
            
            \Alert::success("Successfully Invoiced")->flash();
            return redirect()->back();

        } catch (\Exception $e) {
            $status  = "ERROR";
            $message = $e->getMessage();
            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
        }
    }

    public function deleteInvoiceDiscrepancy ($id, Request $request)
    {
        if(request()->input('enrollment_id') == null) { return "Missing Parameters"; }

        if((int)request()->input('enrollment_id') == 0) { return "Invalid Format"; }

        $enrollment = Enrollment::where('id', request()->input('enrollment_id'))->with('student')->first();
        if($enrollment == null) {
            return "This Enrollment Fee Is Not Yet Invoiced On QuickBooks";
        }

       $delete = Discrepancy::where('id', $id)->whereNull('invoice_no')->delete();

       if($delete) {
            \Alert::success("Successfully Deleted")->flash();
            return back();
       }

       \Alert::success("Error Deleting...")->flash();
        return back();
    }

    public function addInvoiceOtherProgram ($id, Request $request)
    {
        // CHECK IF ENROLLMENT IS IN THE URL PARAMETER
        if(request()->input('enrollment_id') == null) 
        {
            return abort(400);
        }

        try {

            $qbo = new QuickBooksOnline;
            $qbo->initialize();
            
            if($qbo->dataService() === null)
            {
                $status  = "ERROR";
                $message = "Unauthorized QuickBooks";

                return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
            }

            if($qbo->dataService() === null)
            {
                $status  = "ERROR";
                $message = "Unauthorized QuickBooks";

                return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
            }

            $selectedOtherProgram = SelectedOtherProgram::where('id', $id)->with(['otherProgram'])->first();

            // VALIDATE IF THIS PAYMENT HAS ALREADY INVOICED
            if($selectedOtherProgram->invoice_no !== null) {
                \Alert::warning("This Other Program Was Already Invoiced")->flash();
                return redirect()->back();
            }

            // GET THE ENROLLMENT AND OTHER PROGRAM QBO ID AND QUERY TO QBO
            $enrollment_id       = request()->input('enrollment_id');
            $enrollment          = Enrollment::where('id', $enrollment_id)->with('student')->firstOrFail();
            $student_qbo_id      = $enrollment->student->qbo_customer_id;
            $enrolmentFeeProduct = $qbo->dataService()->Query("SELECT * FROM Item WHERE Name = 'Enrolment Fee' AND Type = 'Service'");
            $enrolmentFee        = $enrolmentFeeProduct[0];

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

                if(str_contains($message, ["Unsupported", "OperationOperation", "Create/Update"])) {
                    $message = $error->getResponseBody() . "<br><br> You have to go into the Company <b>Settings</b> -> <b>Sales</b>-> <b>Sales form Content</b> section then tick <b>Deposit</b> under the Sales. Once this is done, you'll be able to make add other programs or services to update this tuition enrollment invoice to QBO.";
                }
                return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
            }

            SelectedOtherProgram::where('id', $enrollment_id)->update(['invoice_no' => $resultingObj->DocNumber]);
            $selectedOtherProgram->invoice_no = $resultingObj->DocNumber;
            $selectedOtherProgram->save();

            \Alert::success("Successfully Invoiced")->flash();
            return redirect()->back();

        } catch (\Exception $e) {
            $status  = "ERROR";
            $message = $e->getMessage();
            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
        }
    }

    public function deleteInvoiceOtherProgram ($id, Request $request)
    {
        if(request()->input('enrollment_id') == null) {
            return "Missing Parameters";
        }

        if((int)request()->input('enrollment_id') == 0) {
            return "Invalid Format";
        }

        $enrollment = Enrollment::where('id', request()->input('enrollment_id'))->with('student')->first();
        if($enrollment == null) {
            return "This Enrollment Fee Is Not Yet Invoiced On QuickBooks";
        }

       $delete = SelectedOtherProgram::where('id', $id)->whereNull('invoice_no')->delete();

       if($delete) {
            \Alert::success("Successfully Deleted")->flash();
            return back();
       }

       \Alert::success("Error Deleting...")->flash();
        return back();
    }

    public function addInvoiceOtherService ($id, Request $request)
    {
        // CHECK IF ENROLLMENT IS IN THE URL PARAMETER
        if(request()->input('enrollment_id') == null) 
        {
            return abort(400);
        }

        try {

            $qbo = new QuickBooksOnline;
            $qbo->initialize();

            if($qbo->dataService() === null)
            {
                $status  = "ERROR";
                $message = "Unauthorized QuickBooks";

                return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
            }

            if($qbo->dataService() === null)
            {
                $status  = "ERROR";
                $message = "Unauthorized QuickBooks";

                return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
            }

            $selectedOtherService = SelectedOtherService::where('id', $id)->with(['student', 'otherService'])->first();

            // VALIDATE IF THIS PAYMENT HAS ALREADY INVOICED
            if($selectedOtherService->invoice_no !== null) {
                \Alert::warning("This Other Program Was Already Invoiced")->flash();
                return redirect()->back();
            }

            // GET THE ENROLLMENT AND OTHER PROGRAM QBO ID AND QUERY TO QBO
            $enrollment_id       = request()->input('enrollment_id');
            $enrollment          = Enrollment::where('id', $enrollment_id)->with('student')->firstOrFail();
            $student_qbo_id      = $enrollment->student->qbo_customer_id;
            $enrolmentFeeProduct = $qbo->dataService()->Query("SELECT * FROM Item WHERE Name = 'Enrolment Fee' AND Type = 'Service'");

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

                if(strpos($message, "OperationOperation")) {
                    $message = $error->getResponseBody() . "<br><br> You have to go into the Company <b>Settings</b> -> <b>Sales</b>-> <b>Sales form Content</b> section then tick <b>Deposit</b> under the Sales. Once this is done, you'll be able to make add other programs or services to update this tuition enrollment invoice to QBO.";
                }
                return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
            }

            $selectedOtherService->invoice_no = $resultingObj->DocNumber;
            $selectedOtherService->save();

            \Alert::success("Successfully Invoiced")->flash();
            return redirect()->back();

        } catch (\Exception $e) {
            $status  = "ERROR";
            $message = $e->getMessage();
            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
        }
    }

    public function deleteInvoiceOtherService ($id, Request $request)
    {
        if(request()->input('enrollment_id') == null) {
            return "Missing Parameters";
        }

        if((int)request()->input('enrollment_id') == 0) {
            return "Invalid Format";
        }

        $enrollment = Enrollment::where('id', request()->input('enrollment_id'))->with('student')->first();
        if($enrollment == null) {
            return "This Enrollment Fee Is Not Yet Invoiced On QuickBooks";
        }

       $delete = SelectedOtherService::where('id', $id)->whereNull('invoice_no')->delete();
       
       if($delete) {
            \Alert::success("Successfully Deleted")->flash();
            return back();
       }

       \Alert::success("Error Deleting...")->flash();
        return back();
    }

    public function addInvoiceAdditionalFee ($id, Request $request)
    {
        // CHECK IF ENROLLMENT IS IN THE URL PARAMETER
        if(request()->input('enrollment_id') == null) 
        {
            return abort(400);
        }

        try {

            $qbo = new QuickBooksOnline;
            $qbo->initialize();

            if($qbo->dataService() === null)
            {
                $status  = "ERROR";
                $message = "Unauthorized QuickBooks";

                return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
            }

            if($qbo->dataService() === null)
            {
                $status  = "ERROR";
                $message = "Unauthorized QuickBooks";

                return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
            }

            $additionalFee = AdditionalFee::where('id', $id)->first();

            // VALIDATE IF THIS PAYMENT HAS ALREADY INVOICED
            if($additionalFee->invoice_no !== null) {
                \Alert::warning("This Additional Fee Was Already Invoiced")->flash();
                return redirect()->back();
            }

            // GET THE ENROLLMENT AND OTHER PROGRAM QBO ID AND QUERY TO QBO
            $enrollment_id       = request()->input('enrollment_id');
            $enrollment          = Enrollment::where('id', $enrollment_id)->with('student')->firstOrFail();
            $student_qbo_id      = $enrollment->student->qbo_customer_id;

             $items =    [
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
                                            "value" => config('settings.taxrate'),
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
                $status  = "ERROR";
                $message = $error->getResponseBody();

                if(str_contains($message, ["Unsupported", "OperationOperation", "Create/Update"])) {
                    $message = $error->getResponseBody() . "<br><br> You have to go into the Company <b>Settings</b> -> <b>Sales</b>-> <b>Sales form Content</b> section then tick <b>Deposit</b> under the Sales. Once this is done, you'll be able to make add other programs or services to update this tuition enrollment invoice to QBO.";
                }
                return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
            }

            AdditionalFee::where('id', $additionalFee->id)->update(['invoice_no' => $resultingObj->DocNumber]);

            \Alert::success("Successfully Invoiced")->flash();
            return redirect()->back();

        } catch (\Exception $e) {
            $status  = "ERROR";
            $message = $e->getMessage();
            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
        }
    }

    public function deleteInvoiceAdditionalFee ($id, Request $request)
    {
        if(request()->input('enrollment_id') == null) {
            return "Missing Parameters";
        }

        if((int)request()->input('enrollment_id') == 0) {
            return "Invalid Format";
        }

        $enrollment = Enrollment::where('id', request()->input('enrollment_id'))->with('student')->first();
        if($enrollment == null) {
            return "This Enrollment Fee Is Not Yet Invoiced On QuickBooks";
        }

       $delete = AdditionalFee::where('id', $id)->whereNull('invoice_no')->delete();
       
       if($delete) {
            \Alert::success("Successfully Deleted")->flash();
            return back();
       }

       \Alert::success("Error Deleting...")->flash();
        return back();
    }

    public function printReceipt ($id)
    {
        $payment_history = PaymentHistory::where('id', $id)->with('user', 'paymentMethod', 'enrollment', 'enrollment.student')->first();

        if(!$payment_history) { abort(404, 'Payment not found.'); }
        if(!$payment_history->enrollment) { abort(404, 'Enrollment not found.'); }

        // // Create Reference No.
        $year = date('y', strtotime($payment_history->created_at));
        $id   = str_pad($payment_history->id,4,'0',STR_PAD_LEFT);
        $receipt_no = $year.$id;

        $enrollment = $payment_history->enrollment;
        // Get Student Section
        $payment_history->student_section  =   self::getStudentSection($enrollment->id);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper(array(0, 0, 612, 936), 'portrait');

        $pdf->loadHTML(view('studentAccount.newReceipt', compact('payment_history', 'receipt_no')));
        return $pdf->stream(config('settings.schoolabbr') . 'receipt' . '.pdf');
    }

    // GET STUDENT SECTION
    public function getStudentSection ($enrollment_id)
    {
        $enrollment                 =   Enrollment::where('id', $enrollment_id)->with('schoolYear')->first();
        $student_section            =   null;
        if(!$enrollment){
            return null;
        }
        // Get All Sections Where Level/Track is Equal To Enrollment Level/Track
        $sections                   =   SectionManagement::where('level_id', $enrollment->level_id)
                                                        ->where('track_id', $enrollment->track_id)
                                                        ->get();
        if(!$sections){
            return null;
        }
        $sections_ids               =   collect($sections)->pluck('id');
        // Get All Student Section Assignment Where SY is Equal To Enrollment SY
        $studentSectionAssignments  =   StudentSectionAssignment::whereIn('section_id', $sections_ids)
                                                        ->where('school_year_id', $enrollment->school_year_id)
                                                        ->with('section')
                                                        ->get();
        if(!$studentSectionAssignments){
            return null;
        }
        // Check Student if in sections
        foreach ($studentSectionAssignments as $key => $studentSectionAssignment) {

            $students   = Student::whereIn('studentnumber', json_decode($studentSectionAssignment->students))
                                ->where('studentnumber', $enrollment->studentnumber)
                                ->get();

            if(count($students) > 0){
                $student_section = $studentSectionAssignment;
            }
        }
        
        if(!$student_section){
             return null;
        }

        return $student_section;
    }

    public function sendSoa(Request $request) 
    {
        $response       =   [
            'error'         =>  true,
            'title'         =>  'Error',
            'message'       =>  'Error, Something Went Wrong, Please Try To Reload The Page.',
            'data'          =>   null
        ];

        if(!$request->enrollment_id) {
            $response['message']    =   'Required data not found.';
            return $response; 
        }

        if(!$request->email && !$request->father_email && !$request->mother_email && !$request->legal_guardian_email && !$request->emergency_email) {
            $response['message']    =   'Required data not found.';
            return $response; 
        }

        $enrollment     =   Enrollment::where('id', $request->enrollment_id)->first();
        $student        =   $enrollment->student;

        if(!$enrollment) { 
            $response['message']    =   'Enrollment not found.';
            return $response; 
        } 

        if (!$enrollment->student) {
            $response['message']    =   'Student not found.';
            return $response;
        }

        $tuition        =   Tuition::where('id', $enrollment->tuition_id)->first();

        if(!$tuition) { 
            $response['message']   =   'Tuition not found.';
            return $response;
        }

        $payment_histories      = PaymentHistory::where('enrollment_id', $request->enrollment_id)->get();
        $selectedOtherServices  = $enrollment->selectedOtherServices;

        $send_to = [];
        // Send Email To Email Entered
        if($request->email) {
            $send_to[] = $request->email;
        }

        // Send Email To Father's Email
        if($request->father_email && $student->father_email) {
            $send_to[] = $student->father_email;
        }

        // Send Email To Mother's Email
        if($request->mother_email && $student->mother_email) {
            $send_to[] = $student->mother_email;
        }

        // Send Email To Legal Guardian's Email
        if($request->legal_guardian_email && $student->legal_guardian_email) {
            $send_to[] = $student->legal_guardian_email;
        }

        // Send Email To Emergency Contact Email
        if($request->emergency_email && $student->emergency_email) {
            $send_to[] = $student->emergency_email;
        }

        if(count($send_to)>0) {
            // ini_set('max_execution_time', 1200);
            // ini_set('memory_limit', -1);
            try {
                Mail::to($send_to)->send(new SendMailableSOA($enrollment, $tuition, $payment_histories));

                $response['error']      =   false;
                $response['title']      =   'Mail Sent';
                $response['message']    =   'SOA has been sent successfully!';

                return $response;
            } catch (Exception $e) {
                return $response;
            }
        }

        $response['error']      =   false;
        $response['title']      =   'Mail Sent';
        $response['message']    =   'SOA has been sent successfully!';

        return $response;

        // return view('studentAccount.mail_soa')
        //             ->with(['enrollment' => $enrollment, 'tuition' => $tuition, 'payment_histories' => $payment_histories]);
    }
}