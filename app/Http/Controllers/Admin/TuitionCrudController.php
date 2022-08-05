<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TuitionRequest as StoreRequest;
use App\Http\Requests\TuitionRequest as UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Student;
use App\Models\SchoolYear;
use App\Models\Department;
use App\Models\YearManagement;
use App\Models\TrackManagement;
use App\Models\CommitmentPayment;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

// Chart of Accounts
use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Account;
use App\Http\Controllers\QuickBooks\QuickBooksOnline as QBO;

class TuitionCrudController extends CrudController 
{
    public $qbItems = [];
    public $chartOfAccountsSub = [];

    private function getMonthsForPaymentScheme ()
    {
        $months         = null;
        $schoolYear     = SchoolYear::active()->first();
        if($schoolYear !== null)
        {
            $sy_start_date  = $schoolYear->start_date;
            $sy_end_date    = $schoolYear->end_date;
            
            if($sy_start_date !== null && $sy_end_date !== null)
            {
                do {
                    $start            = Carbon::parse($sy_start_date)->month;
                    $months[]         = Carbon::parse($sy_start_date)->format('F');
                    $sy_start_date    = Carbon::parse($sy_start_date)->addMonths(1);
                } while ($sy_start_date->lte($sy_end_date));
            }
        }
        return $months;
    }

    public function getQBItems() 
    {
        $qbo =  new QBO;
        $qbo->initialize();

        $name = "Mandatory Fee " .  request()->school_year_id;
        // dd( $name);
      
        $chartOfAccounts = $qbo->dataService->Query("SELECT Id, Name FROM Item maxresults 1000");
        $chartOfAccounts = $chartOfAccounts == null ? [] : $chartOfAccounts;
        
        $error = $qbo->dataService->getLastError();
        if ($error) {
            // $message = "The Status code is: " . $error->getHttpStatusCode() . "\n";
            // $message .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
            $message = $error->getResponseBody();
            // abort($error->getHttpStatusCode(), $error->getResponseBody());
            $action = $this->crud->getActionMethod();
            if($action == 'create' || $action == 'edit') {
                $this->data['status'] = "Error";
                $this->data['message'] = $message;
                // $setView = 'set' . ucfirst($action == 'index' ? 'list' : $action) . 'View';
                $setView = 'set' . ucfirst($action) . 'View';
                $this->crud->{ $setView }('quickbooks.layouts.fallbackMessage');
            }
        }
        $collection = collect($chartOfAccounts);
        $collection = $collection->pluck('Name','Id');

        return $collection ?? [];
    }

    public function chartOfAccountsSub() 
    {

        $qbo =  new QBO;
        $qbo->initialize();
        if($qbo->dataService() === null)
        {
            $status  = "ERROR";
            $message = "Unauthorized QuickBooks";

            return view('quickbooks.layouts.fallbackMessage', compact('status', 'message'));
        }

        $name = "Mandatory Fee " .  request()->school_year_id;
        // dd( $name);
      
        $chartOfAccounts = $qbo->dataService->Query("SELECT * FROM Account");
        // $chartOfAccounts = $qbo->dataService->Query("SELECT * FROM Account WHERE AccountType = 'Income' and AccountSubType = 'ServiceFeeIncome' and SubAccount = true and Active = true");
    
        $chartOfAccounts = $chartOfAccounts == null ? [] : $chartOfAccounts;
        
        $error = $qbo->dataService->getLastError();
        if ($error) {
            echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
            echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
            echo "The Response message is: " . $error->getResponseBody() . "\n";
            exit();
        }

        $collection = collect($chartOfAccounts);

        // $chartArray = [];

        // foreach($chartOfAccounts as $key => $value){

        //     $array_pluck = $value->Id: $value->Name;
        //     array_push($chartArray, $array_pluck);
         
        // }n 
        $collection = $collection->pluck('FullyQualifiedName','Id');

        return $collection;
    }

    public function setup()
    {
        $this->qbItems = $this->getQBItems();
        // dd($this->chartOfAccounts());
        // $user = \Auth::user();
        // $permissions = collect($user->getAllPermissions());

        // $plucked = $permissions->pluck('name');
        // $this->allowed_method_access = $plucked->all();

        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);
        // $this->crud->allowAccess($this->allowed_method_access);
        /*
        |--------------------------------------------------------------------------
        | LOAD ALL THE MONTHS
        |--------------------------------------------------------------------------
        */
        $this->data['initial_months'] = self::getMonthsForPaymentScheme();

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Tuition');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/tuition');
        $this->crud->setEntityNameStrings('tuition', 'tuitions');

        /*
        |--------------------------------------------------------------------------
        | ADDITIONAL BUTTONS
        |--------------------------------------------------------------------------
        */
       
        // $this->crud->addButtonFromView('top', 'view_tuition_archive', 'tuition.viewTuitionArchive', 'end'); // add a button whose 
        // $this->crud->addButtonFromView('top', 'update_active_tuition', 'tuition.updateActiveTuition', 'end'); // add a button whose 
        $this->crud->addButtonFromView('line', 'clone', 'tuition.cloneAndUpdate', 'end'); // add a button whose 
        // dd($this->chartOfAccountsTF());
        // $this->crud->setFromDb();

        $this->crud->setDefaultPageLength(10);

        $this->crud->child_resource_included['select'] = false;


        $this->crud->addField([
            'label' => 'Form Name',
            'name' => 'form_name',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-xs-12',
            ] 
        ]);

        $this->crud->addField(
            [
                'label' => "School Year",
                'type' => 'select2',
                'name' => 'schoolyear_id', // the db column for the foreign key //schoolyearid
                'entity' => 'school_year', // the method that defines the relationship in your Model
                'attribute' => 'schoolYear', // foreign key attribute that is shown to user
                'model' => "App\Models\SchoolYear",
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6 col-xs-12'
                ],
            ],   
            'update/create/both'
        );

        $this->crud->addField([
            'name' => 'department_id',
            'type' => 'select_from_array',
            'label' => 'Select Department',
            'options' => Department::active()->pluck('name', 'id'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 col-xs-12',
            ]
        ]);

        $this->crud->addField([
            'label' => 'Grade/Level',
            'type' => 'select_from_array',
            'name' => 'grade_level_id',
            'options' => [],

            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 col-xs-12',
            ] 
        ]);

        $this->crud->addField([
            'label' => 'Track',
            'type' => 'select_from_array',
            'name' => 'track_id',
            'options' => [],

            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 col-xs-12',
            ] 
        ]);

        // $this->crud->addField([
        //     'name' => 'seperator',
        //     'type' => 'custom_html',
        //     'value' => '<hr>'
        // ]);

        // TUITION FEES
        $this->crud->addField(
            [
                'label' => '<h3 class="text-center" style="margin-bottom: 20px;">MANDATORY FEES UPON ENROLLMENT</h3>',
                'type' => 'tuition.child_tuition_payment_type',
                'name' => 'tuition_fees',
                'entity_singular' => 'Mandatory Fee', // used on the "Add X" button
                'columns' => [
                    [
                        'label' => 'Payment Type',
                        'type' => 'child_select',
                        'name' => 'payment_type',
                        'entity' => 'commitment_payment_active',
                        'attribute' => 'name',
                        'model' => 'App\Models\CommitmentPayment',
                        // 'options' => CommitmentPayment::active()->get()->pluck('name', 'id'),
                        'attributes' => [
                            'required' => 'required',
                            'readonly' => 'readonly'
                        ]
                    ],
                    [
                        'label' => 'Tuition Fees',
                        'type' => 'child_number',
                        'name' => 'tuition_fees',
                        'attributes' => [
                            'required' => 'required'
                        ],
                    ],
                    [
                        'label' => 'Less : Early Bird Discount',
                        'type' => 'child_number',
                        'name' => 'discount',
                        'ng-model' => 'discount',
                        'attributes' => [
                            'id'        => 'payment_scheme_amount',
                            'required'  => 'required',
                            // 'ng-model' => 'payment_scheme_amount'
                        ]
                    ],
                    [
                        'label' => 'QB Map',
                        'type' => 'tuition.child_coa',
                        'name' => 'qb_discount',
                        'options' => $this->qbItems,
                        'attributes' => [
                            'required'  => 'required'
                        ]
                    ],
                    [
                        'label' => 'Total',
                        'type' => 'child_text',
                        'name' => 'total',
                        'attributes' => [
                            'id'        => 'total_payment',
                            'readonly'  => 'readonly',
                        ],
                    ],
                    [
                        'label' => 'QB Map',
                        'type' => 'tuition.child_coa',
                        'name' => 'qb_map',
                        'options' => $this->qbItems,
                        'attributes' => [
                            'required'  => 'required'
                        ]
                    ],

                ],
                'max' => count(CommitmentPayment::active()->get()),
                'min' => count(CommitmentPayment::active()->get())
        ]);

        // MISCELLANEOUS
        $this->crud->addField([
            'name' => 'miscellaneous',
            'label' => '<h3 class="text-center" style="margin-bottom: 20px;">MISCELLANEOUS</h3>',
            'type' => 'child_misc',
            'entity_singular' => 'Add Misc', // used on the "Add X" button
            'columns' => [
                [
                    'label' => 'Code',
                    'type' => 'child_text',
                    'name' => 'code',
                    'attributes' => [
                        'required' => 'required'
                    ]
                ],
                [
                    'label' => 'Description',
                    'type' => 'child_text',
                    'name' => 'description',
                ],
                [
                    'label' => 'Amount',
                    'type' => 'child_number',
                    'name' => 'amount',
                    'attributes' => [
                        'name'      => 'currency',
                        'id'        => 'misc_amount',
                        'required'  => 'required'
                    ]

                ],
                [
                    'label' => 'QB Map',
                    'type' => 'tuition.child_coa',
                    'name' => 'qb_map',
                    'options' => $this->qbItems,
                    'attributes' => [
                        'required'  => 'required'
                    ]
                ],
            ],
            'max' => 20, // maximum rows allowed in the table
            'min' => 1 // minimum rows allowed in the table
        ]);

        // ACTIVITIE FEES
        $this->crud->addField([
            'name' => 'activities_fee',
            'label' => '<h3 class="text-center" style="margin-bottom: 20px;">ACTIVITY FEE</h3>',
            'type' => 'child_activities_fee',
            'entity_singular' => 'Add Misc', // used on the "Add X" button
            'columns' => [
                [
                    'label' => 'Code',
                    'type' => 'child_text',
                    'name' => 'code',
                    'attributes' => [
                        // 'required' => 'required'
                    ]
                ],
                [
                    'label' => 'Description',
                    'type' => 'child_text',
                    'name' => 'description',
                ],
                [
                    'label' => 'Amount',
                    'type' => 'child_number',
                    'name' => 'amount',
                    'attributes' => [
                        'name'      => 'currency',
                        'id'        => 'misc_amount',
                        'required'  => 'required'
                    ]
                ],
                [
                    'label' => 'QB Map',
                    'type' => 'tuition.child_coa',
                    'name' => 'qb_map',
                    'options' => $this->qbItems,
                    'attributes' => [
                        'required'  => 'required'
                    ]
                ],
            ],
            // 'min' => 0 // minimum rows allowed in the table
        ]);

        // OTHER FEES
        $this->crud->addField([
            'name' => 'other_fees',
            'label' => '<h3 class="text-center" style="margin-bottom: 20px;">OTHER FEE</h3>',
            'type' => 'child_other_fee',
            'entity_singular' => 'Add Misc', // used on the "Add X" button
            'columns' => [
                [
                    'label' => 'Code',
                    'type' => 'child_text',
                    'name' => 'code',
                    'attributes' => [
                        // 'required' => 'required'
                    ]
                ],
                [
                    'label' => 'Description',
                    'type' => 'child_text',
                    'name' => 'description',
                ],
                [
                    'label' => 'Amount',
                    'type' => 'child_number',
                    'name' => 'amount',
                    'attributes' => [
                        'name'      => 'currency',
                        'id'        => 'misc_amount',
                        'required'  => 'required'
                    ]

                ],
                [
                    'label' => 'QB Map',
                    'type' => 'tuition.child_coa',
                    'name' => 'qb_map',
                    'options' => $this->qbItems,
                    'attributes' => [ 'required'  => 'required' ]
                ],
            ],
            // 'min' => 0 // minimum rows allowed in the table
        ]);

        //  PAYMENT CASH
        
        // PAYMENT SCHEME
        $paymentScheme = [   // Table
            'name' => 'payment_scheme',
            'label' => '<h3 class="text-center" style="margin-bottom: 20px;">TUITION FEE (PAYMENT SCHEME)</h3>',
            'type' => 'child_payment_scheme',
            'entity_singular' => 'Scheme', // used on the "Add X" button
            'columns' => [
                [
                    'label' => 'Scheme Date',
                    'name' => 'scheme_date',
                    // 'type' => 'child_select_from_array',
                    'type' => 'child_date',
                    'attributes' => [ 'required' => 'required' ],
                    // 'options' => [
                    //     'January'   => 'January',
                    //     'February'  => 'February',
                    //     'March'     => 'March',
                    //     'April'     => 'April',
                    //     'May'       => 'May',
                    //     'June'      => 'June',
                    //     'July'      => 'July',
                    //     'August'    => 'August',
                    //     'September' => 'September',
                    //     'October'   => 'October',
                    //     'November'  => 'November',
                    //     'December'  => 'December',
                    // ],
                ],
            ],
            'max' => 12, // maximum rows allowed in the table
            // 'min' => 8 // minimum rows allowed in the table
        ];
        $commitmentPayments = CommitmentPayment::active()->get();

        foreach ($commitmentPayments as $commitmentPayment) {
            $paymentScheme['columns'][] = [
                'label' => $commitmentPayment->name,
                'type' => 'child_number',
                'name' =>  Str::snake(Str::studly(strtolower($commitmentPayment->name))) . '_amount',
                'attributes' => [ 'required' => 'required' ]
            ];
        }

        $this->crud->addField($paymentScheme);

        $this->crud->addField([
            'type' => 'tuition.tuition_script',
            'name' => 'tuition_script'
        ]);    


        $this->crud->removeColumn(['tuition_fees', 'payment_scheme', 'miscellaneous', 'other_fees']); // remove a column from the stack
        $this->crud->removeFields(['active']); // remove a fields


        $this->crud->addColumn([
            'label' => 'Form Name',
            'type' => 'text',
            'name' => 'form_name',
        ]);

        $this->crud->addColumn([
            'label' => "School Year",
            'type' => 'select',
            'name' => 'schoolyear_id', // the db column for the foreign key //schoolyearid
            'entity' => 'school_year', // the method that defines the relationship in your Model
            'attribute' => 'schoolYear', // foreign key attribute that is shown to user
            'model' => "App\Models\SchoolYear",
        ]);

        $this->crud->addColumn([
            'label' => 'Department',
            'type' => 'select',
            'name' => 'department_id',
            'entity' => 'department',
            'attribute' => 'name',
            'model' => 'App\Models\Department',
        ]);

        $this->crud->addColumn([
            'label' => 'Grade/Level',
            'type' => 'select',
            'name' => 'grade_level_id',
            'entity' => 'year_management',
            'attribute' => 'year',
            'model' => 'App\Models\YearManagement',
        ]);

        $this->crud->addColumn([
            'label' => 'Track',
            'type' => 'select',
            'name' => 'track_id',
            'entity' => 'track',
            'attribute' => 'code',
            'model' => 'App\Models\TrackManagement',
        ]);

        $this->crud->addColumn([   // Checkbox
            'name' => 'active',
            'label' => 'Active',
            'type' => 'check'
        ]);

        $this->crud->addFilter([ // select2 filter
          'name' => 'schoolyear_id',
          'type' => 'select2',
          'label'=> 'School Year'
        ], function() {
            return SchoolYear::all()->keyBy('id')->pluck('schoolYear', 'id')->toArray();
        }, function($value) { // if the filter is active
            $this->crud->addClause('where', 'schoolyear_id', $value);
        });

        $this->crud->addFilter([ // select2 filter
          'name' => 'department_id',
          'type' => 'select2',
          'label'=> 'Department'
        ], function() {
            return Department::all()->pluck('name', 'id')->toArray();
        }, function($value) { // if the filter is active
            $this->crud->addClause('where', 'department_id', $value);
        });

        $this->crud->addFilter([ // select2 filter
          'name' => 'grade_level_id',
          'type' => 'select2',
          'label'=> 'Level'
        ], function() {
            return YearManagement::all()->pluck('year', 'id')->toArray();
        }, function($value) { // if the filter is active
            $this->crud->addClause('where', 'grade_level_id', $value);
        });

        $this->crud->addFilter([ // select2 filter
            'name' => 'track_id',
            'type' => 'select2',
            'label'=> 'Strand'
        ], function() {
            return TrackManagement::distinct()->pluck('code', 'code')->toArray();
        }, function($value) { // if the filter is active
            $this->crud->addClause('trackCode', $value);
        });

        $this->crud->removeColumns(['payment_scheme', 'activities_fee', 'miscellaneous']);
        $this->crud->addButtonFromView('line', 'setActive', 'tuition.setActive', 'end'); // add a button whose HTML is in a view placed at 
        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');
        // $this->crud->allowAccess('revisions');
        $this->crud->allowAccess('clone');
       
        $this->crud->orderBy('active', 'desc');
    }

    public function setActive (Request $request, $action)
    {
        // DISABLED ALL ACTIVE
        $disabled = $this->crud->model::where([
            'schoolyear_id'  => $request->schoolyear_id, 
            'department_id'  => $request->department_id,
            'grade_level_id' => $request->grade_level_id,
            'track_id'       => $request->track_id
        ])->update(['active' => 0]);
        // dd( $this->crud->model::where(['schoolyear_id' => $schoolyear_id, 'grade_level_id' => $grade_level_id])->get());
        // SET ACTIVE TO TRUE THE GIVEN ID
        $actionMessage = '';
        if($action == 'activate')
        {
            $updateActive  = $this->crud->model::find($request->tuition_id)->update(['active' => 1]);
            $actionMessage = 'Activating'; 
        } else 
        {
            $updateActive  = $this->crud->model::find($request->tuition_id)->update(['active' => 0]);
            $actionMessage = 'Deactivating';
        }

        if($updateActive) {
            \Alert::success("Successfully " . $actionMessage)->flash();
            return redirect()->back();
        }
            \Alert::warning("Error " . $actionMessage . "! Please Try Again...")->flash();
            return redirect()->back();
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }


    public function edit ($id)
    {

        $this->crud->hasAccessOrFail('update');
        $this->crud->setOperation('update');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);

        $this->data['id'] = $id;

        if($this->crud->getEntry($id)->active)
        {
            \Alert::warning("Unable To Edit This Item Once The Active Is Set")->flash();
            return redirect()->back();
        }

        if (config('app.tuition_validation')) {
            if($this->crud->getEntry($id)->enrollments->count() > 0)
            {
                \Alert::warning("Unable to Edit the Tuition Form when a Student has already been enrolled with it.")->flash();
                return redirect()->back();
            }
        }

        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.edit').' '.$this->crud->entity_name;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
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

    public function clone ($id)
    {
        $this->crud->hasAccessOrFail('clone');
        $this->crud->setOperation('clone');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.clone').' '.$this->crud->entity_name;

        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('tuition.clone', $this->data);
    }
}
