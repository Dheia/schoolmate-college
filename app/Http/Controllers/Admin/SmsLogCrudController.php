<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SmsLogRequest as StoreRequest;
use App\Http\Requests\SmsLogRequest as UpdateRequest;

/**
 * Class SmsLogCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SmsLogCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setDefaultPageLength(10);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SmsLog');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/smslog');
        $this->crud->setEntityNameStrings('smslog', 'sms_logs');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in SmsLogRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->removeAllButtons();
        $this->crud->denyAccess(['delete', 'update']);

        $this->crud->orderBy('created_at', 'DESC');

        $this->crud->addColumn([
            'label' => 'ID',
            'type' => 'text',
            'name' => 'studentnumber',
            'prefix' => config('settings.schoolabbr') . ' - '
        ]);

        $this->crud->addColumn([
            'label' => 'Full Name',
            'name' => 'full_name',
            'type' => 'text'
        ])->afterColumn('studentnumber');

        $this->crud->addColumn([
            'label' => 'Created At',
            'name' => 'created_at',
            'type' => 'datetime'
        ]);
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
}
