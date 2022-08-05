<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Models\Misc;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LevelRequest as StoreRequest;
use App\Http\Requests\LevelRequest as UpdateRequest;

class LevelCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setDefaultPageLength(10);
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
        $this->crud->setModel('App\Models\Level');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/level');
        $this->crud->setEntityNameStrings('level', 'levels');
        $this->crud->setCreateView('crud.createLevel');

        $misc = Misc::all();
        $this->data['misc'] = $misc;
        $this->crud->misc = $misc;

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        $this->crud->addField(
            [
            'label' => "Year Level",
               'type' => 'select2',
               'name' => 'year_level_id', // the db column for the foreign key //schoolyearid
               'entity' => 'yearLevel', // the method that defines the relationship in your Model
               'attribute' => 'year',// foreign key attribute that is shown to user
               'model' => "App\Models\YearManagement",
            'wrapperAttributes' => [
                        'class' => 'form-group col-md-12'
                    ],]
            , 'update/create/both');

        $this->crud->addField(
            [
            'label' => "Misc",
               'type' => 'select2_level',
               'name' => 'misc_id', // the db column for the foreign key //schoolyearid
               'entity' => 'misc', // the method that defines the relationship in your Model
               'attribute' => 'name', // foreign key attribute that is shown to user
               'model' => "App\Models\Misc",
            'wrapperAttributes' => [
                        'class' => 'form-group col-md-8'
                    ],]
            , 'update/create/both');

            $this->crud->addField(
            [   // Text
            'name' => 'amount',
            'label' => "Amount",
            'type' => 'text',
            'attributes' => [
                'id' => 'amount',
                'readonly' => 'readonly'
            ],
            'wrapperAttributes' => [
                        'class' => 'form-group col-md-4'
                    ],]
            , 'update/create/both')->afterField('misc_id');

            $this->crud->addField(
            [   // Number
                'name' => 'payment',
                'label' => 'Payment',
                'type' => 'number',
                'attributes' => [
                    'onblur' => 'changeTotal2()',
                ],
            'wrapperAttributes' => [
                        'class' => 'form-group col-md-12'
                    ],]
            , 'update/create/both');

            $this->crud->addField([   // Enum
                'name' => 'payment_type',
                'label' => 'Payment Type',
                'type' => 'enum_level',
                'attributes' => [
                    'id' => 'paymenttypeid',
                ],
            ], 'update/create/both');
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
