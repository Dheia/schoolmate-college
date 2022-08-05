<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SystemAttendanceRequest as StoreRequest;
use App\Http\Requests\SystemAttendanceRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

use App\Models\SystemAttendance;

use Carbon\Carbon;

/**
 * Class SystemAttendanceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SystemAttendanceCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SystemAttendance');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/system-attendance');
        $this->crud->setEntityNameStrings('systemattendance', 'System Attendances');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in SystemAttendanceRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->denyAccess(['create', 'update', 'delete']);

        $this->crud->addColumn([
            'label' => 'User',
            'type' => 'select',
            'name' => 'user_id',
            'entity' => 'user',
            'attribute' => 'full_name',
            'model' => 'App\Models\User'
        ]);

        $this->crud->addColumn([
            'label' => 'User Type',
            'type' => 'text',
            'name' => 'user_type'
        ])->afterColumn('user_id');

        $this->crud->addColumn([
            'label' => 'Time in',
            'type' => 'date',
            'name' => 'time_in',
            'format' => 'hh:mm A'
        ]);

        $this->crud->addColumn([
            'label' => 'Time out',
            'type' => 'date',
            'name' => 'time_out',
            'format' => 'hh:mm A'
        ]);

        $this->crud->addColumn([
            'label' => 'Date',
            'type' => 'date',
            'name' => 'created_at',
            'format' => 'MMMM D,  Y'
        ]);

        if(!backpack_auth()->user()->hasRole('Administrator')) {
            $this->crud->denyAccess('details_row');
            $this->crud->addClause('where', 'user_id', backpack_auth()->user()->id);
        } else {
            $this->crud->addFilter([ 
              'type' => 'simple',
              'name' => 'show_my_items_only',
              'label'=> 'Show My Logs Only'
            ],
            false, // the simple filter has no values, just the "Draft" label specified above
            function() { // if the filter is active (the GET parameter "draft" exits)
                $this->crud->addClause('where', 'user_id', backpack_auth()->user()->id); 
            });

            $this->crud->addFilter([
              'type'  => 'date_range',
              'name'  => 'from_to',
              'label' => 'Date range'
            ],
            false,
            function ($value) { // if the filter is active, apply these constraints
              $dates = json_decode($value);
              $this->crud->addClause('where', 'created_at', '>=', $dates->from);
              $this->crud->addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');
            });
        }

        $this->crud->addClause('where', 'user_id', '>', 0);
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

    public function userTapIn(Request $request)
    {
        $response = [
                        'error' => false,
                        'message' => null,
                        'data' => null
                    ];
        $currentDate     =  Carbon::now()->toDateString();
        $currentTime     =  Carbon::now()->toTimeString();
        $currentDateTime =  Carbon::now();
        $userAttendance  =  $this->crud->model::where('user_id', backpack_auth()->user()->id)
                                ->where('created_at', '>=', $currentDate)
                                ->where('created_at', '<=', $currentDate . ' 23:59:59')
                                ->first();
        if ($userAttendance) {
            $response['error']   = true;
            $response['title']   = 'Error';
            $response['message'] = 'You already Tap In';
            return $response;
        }
        else {
            $userAttendance = new SystemAttendance;
            $userAttendance->user_id = backpack_auth()->user()->id;
            $userAttendance->user_type = 'App\Models\User';
            $userAttendance->time_in  = $currentDateTime;
            if($userAttendance->save())
            {
                $time_in = date("h:i:s A", strtotime($currentDateTime));
                $response['title']   = 'Time In';
                $response['message'] = $time_in . ' Successfully Time In.';
                $response['data'] = $time_in;
                return $response;
            }
            else{
                $response['error'] = true;
                $response['title']   = 'Error';
                $response['message'] = 'Error Tapping In, Something Went Wrong, Please Try To Reload The Page.';
                return $response;
            }

        }
    }

    public function userTapOut(Request $request)
    {
       $response = [
                        'error' => false,
                        'message' => null,
                        'data' => null
                    ];
        $currentDate     =  Carbon::now()->toDateString();
        $currentTime     =  Carbon::now()->toTimeString();
        $currentDateTime =  Carbon::now();
        $userAttendance  =  $this->crud->model::where('user_id', backpack_auth()->user()->id)
                                ->where('created_at', '>=', $currentDate)
                                ->where('created_at', '<=', $currentDate . ' 23:59:59')
                                ->first();
        if ($userAttendance) {
            $userAttendance->time_out = $currentDateTime;

            if($userAttendance->update())
            {
                $time_out = date("h:i:s A", strtotime($currentDateTime));
                $response['title']   = 'Time Out';
                $response['message'] = $time_out . ' Successfully Time Out.';
                $response['data'] = $time_out;
                return $response;
            }
            else 
            {
                $response['error'] = true;
                $response['title']   = 'Error';
                $response['message'] = 'Error Tapping Out, Something Went Wrong, Please Try To Reload The Page.';
                return $response;
            }
        }
        else {
            $response['error']   = true;
            $response['title']   = 'Error';
            $response['message'] = 'You are not yet In';
            return $response;
        }
    }
}
