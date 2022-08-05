<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TransmutationRequest as StoreRequest;
use App\Http\Requests\TransmutationRequest as UpdateRequest;

/**
 * Class TransmutationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TransmutationCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Transmutation');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/transmutation');
        $this->crud->setEntityNameStrings('transmutation', 'transmutations');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in TransmutationRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->removeField('active');

        $this->crud->addField([
            'name' => 'transmutation_table',
            'label' => 'Transmutation Table',
            'type' => 'table',
            'entity_singular' => 'Transmutation', // used on the "Add X" button
            'columns' => [
                'min' => 'Min',
                'max' => 'Max',
                'transmuted_grade' => 'Transmuted Grade'
            ],
            'min' => 1,
        ]);

        $this->crud->removeColumn('transmutation_table');

        $this->crud->addColumn([
            'label' => 'Active',
            'type' => 'check',
            'name' => 'active'
        ]);

        $this->crud->addButtonFromView('line', 'setActive', 'transmutation.setActive', 'end');
        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('details_row');
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

    // public function destroy ($id)
    // {
    //     $model = $this->crud->model::findOrFail($id);
    //     $model->delete();
    // }

    public function setActive ($id, $action)
    {
        // DISABLED ALL ACTIVE
        $disabled = $this->crud->model::query()->update(['active' => 0]);
        // dd( $this->crud->model::where(['schoolyear_id' => $schoolyear_id, 'grade_level_id' => $grade_level_id])->get());
        // SET ACTIVE TO TRUE THE GIVEN ID
        $actionMessage = '';
        if($action == 'activate')
        {
            $updateActive  = $this->crud->model::find($id)->update(['active' => 1]);
            $actionMessage = 'Activated'; 
        } else 
        {
            $updateActive  = $this->crud->model::find($id)->update(['active' => 0]);
            $actionMessage = 'Deactivated';
        }

        if($updateActive) {
            \Alert::success("Successfully " . $actionMessage)->flash();
            return redirect()->back();
        }
            \Alert::warning("Error " . $actionMessage . "! Please Try Again...")->flash();
            return redirect()->back();
    }

    public function getActive ()
    {
        return $this->crud->model::where('active', 1)->first();
    }

    public function showDetailsRow($id)
    {
        $this->crud->hasAccessOrFail('details_row');
        $this->crud->setOperation('list');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('transmutation.details_row', $this->data);
    }
}
