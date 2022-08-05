<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TrackManagementRequest as StoreRequest;
use App\Http\Requests\TrackManagementRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use App\Models\YearManagement;

/**
 * Class TrackManagementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TrackManagementCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\TrackManagement');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/strand');
        $this->crud->setEntityNameStrings('Strand', 'Strands');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in TrackManagementRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->removeButton('delete');
        $this->crud->denyAccess(['delete']);
        $this->crud->addButtonFromView('line', 'active', 'active', 'end');
        $this->crud->removeField('active');

        $this->crud->addColumn([
              // Select
               'label' => "Level",
               'type' => 'select',
               'name' => 'level_id', // the db column for the foreign key
               'entity' => 'level', // the method that defines the relationship in your Model
               'attribute' => 'year', // foreign key attribute that is shown to user
               'model' => "App\Models\YearManagement",
        ]);

        $year = YearManagement::join('departments', function ($query) {
          $query->on('year_managements.department_id', '=', 'departments.id');
          $query->where('with_track', 1);
        })->orderBy('year_managements.sequence')->pluck('year_managements.year', 'year_managements.id');

        $this->crud->addField([
          // Select
           'label' => "Level",
           'type' => 'select2_from_array',
           'name' => 'level_id', // the db column for the foreign key
           'options' => $year ?? [],
           // 'options' => YearManagement::orderBy('sequence')->pluck('year', 'id')->toArray(),
           // 'entity' => 'level', // the method that defines the relationship in your Model
           // 'attribute' => 'year', // foreign key attribute that is shown to user
           // 'model' => "App\Models\YearManagement",
           'wrapperAttributes' => [
                'class' => 'col-md-6'
           ]
        ]);

        $this->crud->addField(
            [   // Table
                'label' => 'Code',
                'name' => 'code',
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
               ]
            ],'update/create/both'
        );

        $this->crud->addField([   // Table
            'label' => 'Description',
            'type' => 'textarea',
            'name' => 'description',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
           ]
        ]);

        $this->crud->addColumn([
            'label' => 'Active',
            'type'  => 'check',
            'name'  => 'active'
        ]);


        $this->crud->orderBy('created_at', 'DESC');

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

    public function setActive ($id) 
    {
        $this->crud->model::where('id', $id)->update(['active' => 1]);
        \Alert::success("The item has been set to active.")->flash();
        return redirect()->back();
    }

    public function setDeactive ($id) 
    {
        $this->crud->model::where('id', $id)->update(['active' => 0]);
        \Alert::success("The item has been set to deactive.")->flash();
        return redirect()->back();
    }
}
