<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EmploymentStatusRequest as StoreRequest;
use App\Http\Requests\EmploymentStatusRequest as UpdateRequest;

/**
 * Class EmploymentStatusCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EmploymentStatusCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\EmploymentStatus');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/employment-status');
        $this->crud->setEntityNameStrings('Status', 'Employment Status Management');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in EmploymentStatusRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->allowAccess('clone');
        $this->crud->allowAccess('show');


        $this->crud->addField([
            'name' => 'updated_by',
            'type' => 'hidden',
            'value' => backpack_auth()->user()->id
        ]);

        $this->crud->addField([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Name'
        ]);

        $this->crud->addField([
            'name' => 'description',
            'type' => 'textarea',
            'label' => 'Description'
        ]);

        $this->crud->addField([
            'label' => 'Resigned',
            'type' => 'checkbox',
            'name' => 'resigned'
        ]);

        $this->crud->addField(
            [ // Table
                'name' => 'benefits',
                'label' => 'Benefits',
                'type' => 'table',
                'entity_singular' => 'option', // used on the "Add X" button
                'columns' => [
                    'name' => 'Name',
                    'description' => 'Description',
                    'days_given' => 'Days Given'
                ],
                'max' => 5, // maximum rows allowed in the table
                'min' => 0, // minimum rows allowed in the table
            ]
        );

        $this->crud->addColumn([
            'label' => 'Name',
            'type' => 'text',
            'name' => 'name'
        ]);

        $this->crud->addColumn([
            'label' => 'Description',
            'type' => 'textarea',
            'name' => 'description'
        ]);

        $this->crud->addColumn([
            'label' => 'Resigned',
            'type' => 'check',
            'name' => 'resigned'
        ]);

        if($this->crud->getActionMethod() == 'show')
        {
            $this->crud->addColumn([
                'label' => 'Benefits',
                'name' => 'benefits',
                'type' => 'table', 
                'columns' => [
                    'name' => 'Name',
                    'description' => 'Description',
                    'days_given' => 'Days Given'
                ],
            ]);
        }

        $this->crud->removeColumns(['updated_by']);
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumns(['updated_by']);

        return $content;
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
