<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EnrollmentStatusRequest as StoreRequest;
use App\Http\Requests\EnrollmentStatusRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\SchoolYear;
use App\Models\Department;
use App\Models\EnrollmentStatus;
use App\Models\EnrollmentStatusItem;

/**
 * Class EnrollmentStatusCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EnrollmentStatusCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\EnrollmentStatus');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/enrollment-status');
        $this->crud->setEntityNameStrings('Enrollment Status', 'Enrollment Status');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in EnrollmentStatusRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        // $this->crud->setListView('enrollmentStatus.list');
        $this->crud->setListView('enrollmentStatus.dashboard');
        $this->data['schoolYears'] = SchoolYear::with('enrollment_status', 'enrollment_status.department', 'enrollment_status.items')->get();
        $this->data['enrollmentStatusItems'] = EnrollmentStatusItem::with('enrollment_status')->get();
        $this->data['enrollmentStatuses'] = $this->crud->model::with('items')->get();
        // dd( $this->data['schoolYears']);

        // COLUMNS
        $this->crud->addColumn([
            'label' => 'School Year',
            'type' => 'select',
            'name' => 'school_year_id',
            'entity' => 'schoolYear',
            'attribute' => 'schoolYear',
            'model' => 'App\Models\SchoolYear'
        ]);

        $this->crud->addColumn([
            'label' => 'Department',
            'type' => 'select',
            'name' => 'department_id',
            'entity' => 'department',
            'attribute' => 'name',
            'model' => 'App\Models\Department',
            'wrapperAttributes' => [ 
                'class' => 'form-group col-md-6' 
            ]
        ]);


        // FIELDS
        $this->crud->addField([
            'label' => 'School Year',
            'type' => 'select2',
            'name' => 'school_year_id',
            'entity' => 'schoolYear',
            'attribute' => 'schoolYear',
            'model' => 'App\Models\SchoolYear'
            // 'options' => SchoolYear::active()->get()->pluck('schoolYear', 'id'),
        ]);

        $this->crud->addField([
            'label' => 'Department',
            'type' => 'select_from_array',
            'name' => 'department_id',
            'options'           => Department::active()->pluck('name', 'id'),
            // 'wrapperAttributes' => [ 
            //     'class' => 'form-group col-md-6' 
            // ]
        ]);

        $this->crud->addField([   // color_picker
            'label' => 'Summer',
            'name' => 'summer',
            'type' => 'checkbox',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6 col-xs-12'
            ]
        ]);
        
        // $this->crud->show
        $this->crud->enableDetailsRow();
        $this->crud->allowAccess("details_row");
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here

        // CHECK IF ALREADY EXIST
        $isExists = $this->crud->model::where('school_year_id', $request->school_year_id)
                                    ->where('department_id', $request->department_id)
                                    ->where('summer', $request->summer)
                                    ->exists();

        if($isExists) {
            \Alert::warning('School Year & Department Is Already Exists')->flash();
            return redirect()->back()->withInput();
        }
        $redirect_location = parent::storeCrud($request);

        $schoolYear = SchoolYear::where('id', $request->school_year_id)->first();
        $department = Department::with('term')->where('id', $request->department_id)->first();

        if($department->term->no_of_term > 1 && !$request->summer)
        {
            foreach ($department->term->ordinal_terms as $key => $term) {
                $enrollmentStatusItems = new EnrollmentStatusItem;
                $enrollmentStatusItems->enrollment_status_id = $this->crud->entry->id;
                $enrollmentStatusItems->term        = $term;
                $enrollmentStatusItems->start_date  = $schoolYear->start_date;
                $enrollmentStatusItems->end_date    = $schoolYear->end_date;
                $enrollmentStatusItems->active      = 0;
                $enrollmentStatusItems->save();
            }
        }
        else
        {
            $enrollmentStatusItems = new EnrollmentStatusItem;
            $enrollmentStatusItems->enrollment_status_id = $this->crud->entry->id;
            $enrollmentStatusItems->term        = $request->summer ? 'Summer' : 'Full';
            $enrollmentStatusItems->start_date  = $schoolYear->start_date;
            $enrollmentStatusItems->end_date    = $schoolYear->end_date;
            $enrollmentStatusItems->active      = 0;
            $enrollmentStatusItems->save();
        }
       
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

    public function showDetailsRow($id)
    {
        $this->crud->hasAccessOrFail('details_row');
        $this->data['entry'] = $this->crud->model::where('id', $id)->first();

        // $parent = [];
        // $children = [];

        // if($this->data['entry'] !== null) {
        //     $subjects = $this->data['entry']->subjects;
        //     foreach ($subjects as $subject) {

        //         $subject->subject_id = $subject->subject_code;
        //         $subject_code = SubjectManagement::where('id', $subject->subject_code)->first();
        //         $subject->subject_code = $subject_code->subject_code;

        //         if(!isset($subject->parent_of)) {

        //             $parent[] = $subject;
        //         } else {
        //             $children[] = $subject;
        //         }
        //     }
        // }

        $this->data['crud']     = $this->crud;
        // $this->data['parent']   = $parent;
        // $this->data['children'] = $children;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('crud::enrollmentStatus.details_row', $this->data);
    }

    public function updateItem()
    {
        $response = [
                        'error' => false,
                        'message' => null,
                        'data' => null,
                        'off' => []
                    ];
        $item = EnrollmentStatusItem::with('enrollment_status')->where('id', request()->id)->first();
        if(!$item)
        {
            $response['error'] = true;
            $response['title']   = 'Error';
            $response['message'] = 'Error Updating, Something Went Wrong, Please Try To Reload The Page.';
            return $response;
        }
        if(request()->checked == 'true')
        {
            $enrollmentStatuses =   EnrollmentStatus::where('department_id', $item->enrollment_status->department_id)
                                    ->orWhere('id', $item->enrollment_status_id)
                                    ->get()->pluck('id');

            if( $item->term != 'Summer' ) {
                $ids    =   EnrollmentStatusItem::whereIn('enrollment_status_id', $enrollmentStatuses)
                                ->where('id', '!=', $item->id)
                                ->where('active', '!=', 0)
                                ->get()->pluck('id');
            } 
            else {
                $ids    =   EnrollmentStatusItem::whereIn('enrollment_status_id', $enrollmentStatuses)
                                ->where('id', '!=', $item->id)
                                ->where('active', '!=', 0)
                                ->where('term', 'Summer')
                                ->get()->pluck('id');
            }

            EnrollmentStatusItem::wherein('id', $ids)->where('id', '!=', $item->id)->update(['active' => 0]);
            $item->active = 1;

            if($item->update())
            {
                $response['title']   = 'Enrollment Status Updated';
                $response['message'] = 'Enrollment Status Successfully Updated.';
                $response['off'] = $ids;
                return $response;
            }
            else{
                $response['error'] = true;
                $response['title']   = 'Error';
                $response['message'] = 'Error Updating, Something Went Wrong, Please Try To Reload The Page.';
                return $response;
            }
        }
        else if(request()->checked == 'false'){
            $item->active = 0;
            if($item->update())
            {
                $response['title']   = 'Enrollment Status Updated';
                $response['message'] = 'Enrollment Status Successfully Updated.';
                return $response;
            }
            else{
                $response['error'] = true;
                $response['title']   = 'Error';
                $response['message'] = 'Error Updating, Something Went Wrong, Please Try To Reload The Page.';
                return $response;
            }
        }
    }
}
