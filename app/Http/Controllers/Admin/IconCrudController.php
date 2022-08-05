<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\IconRequest as StoreRequest;
use App\Http\Requests\IconRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class IconCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class IconCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Icon');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/icon');
        $this->crud->setEntityNameStrings('icon', 'icons');

       
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();
        $this->crud->addField([ 
            'name' => 'image',
            'label' => 'Image',
            'type' => 'browse'
                 
        ]);

        $this->crud->addColumn([
            'name' => 'image', // The db column name
            'label' => "Icon", // Table column heading
            'type' => 'image',
            'height' => '50px',
            'width' => '50px'
            ]);
        // add asterisk for fields that are required in IconRequest
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
