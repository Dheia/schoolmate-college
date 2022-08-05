<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SmartCardPrintLogsRequest as StoreRequest;
use App\Http\Requests\SmartCardPrintLogsRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class SmartCardPrintLogsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SmartCardPrintLogsCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SmartCardPrintLogs');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/smartcard/print-logs');
        $this->crud->setEntityNameStrings('Smart Cart Print Logs', 'Smart Cart Print Logs');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->denyAccess(['create', 'update', 'delete']);
        $this->crud->orderBy('created_at', 'DESC');

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();
        $this->crud->addColumn([
            'label' => 'Student Number',
            'type' => 'text',
            'key' => 'studentnumber',
            'name' => 'studentnumber',
            'prefix' => \Setting::get('schoolabbr') . '-',
        ]);

        $this->crud->addColumn([
            // 1-n relationship
            'label' => "Full Name", // Table column heading
            'type' => "text",
            'name' => 'full_name', // the column that contains the ID of that connected entity;
            'entity' => "student", 
            'attribute' => "full_name", 
            'model' => "App\Models\Student", 
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('students', function ($q) use ($column, $searchTerm) {
                    $q->where('firstname', 'like', '%'.$searchTerm.'%')
                        ->orWhere('lastname', 'like', '%'.$searchTerm.'%')
                        ->orWhere('studentnumber', 'like', '%'.$searchTerm.'%');
                })->orWhereHas('studentsById', function ($q) use ($column, $searchTerm) {
                    $q->where('firstname', 'like', '%'.$searchTerm.'%')
                        ->orWhere('lastname', 'like', '%'.$searchTerm.'%')
                        ->orWhere('studentnumber', 'like', '%'.$searchTerm.'%');
                });
            },
            'priority' => 1,
        ])->afterColumn('studentnumber');

        $this->crud->addColumn([
           'name' => "printed",
           'label' => "Printed", // Table column heading
           'type' => "model_function",
           'function_name' => 'getPrintedWithBadge',
        ]);

        $this->crud->addColumn([
            'label'     => 'Date & Time',
            'type'      => 'datetime',
            'name'      => 'created_at',
            'format'    => 'MMMM DD YYYY, hh:mm a'
        ]);

        // add asterisk for fields that are required in SmartCardPrintLogsRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
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
}
