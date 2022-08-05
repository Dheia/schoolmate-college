<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CardSetupRequest as StoreRequest;
use App\Http\Requests\CardSetupRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use App\Models\Student;
/**
 * Class CardSetupCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CardSetupCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\CardSetup');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/smartcard/card-setup');
        $this->crud->setEntityNameStrings('Card Template', 'Templates');
        $this->crud->setCreateView('smartCard.createTemplate');
        $this->crud->setEditView('smartCard.editTemplate');
        // dd(get_class_methods($this->crud));
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in CardSetupRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->removeAllFields();
        $this->crud->removeColumns(['front_card', 'rear_card']);

        // GET ALL COLUMN TABLE AND APPEND
        $studentColumns = new Student;
        // dd(get_class_methods($studentColumns));
        $appends = collect($studentColumns->getMutatedAttributes());
        $columns = $studentColumns->getFillable();
        $studentColumn = $appends->merge($columns);        
        $this->data["studentColumns"] = $studentColumn;

        // ADD BUTTONS
        $this->crud->addButtonFromView('line', 'Active', 'cardSetup.active', 'ending');

        $this->crud->addColumn([
            'label' => 'Active',
            'type' => 'check',
            'name' => 'active',
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

    public function save ()
    {
        $model = $this->crud->model::create([
            'template_name' => request()->name,
            'front_card'    => request()->front,
            'rear_card'     => request()->back,
        ]);

    }

    public function setActive ($action, $id)
    {
        // DISABLED ALL ACTIVE
        $this->crud->model::find($id)->update(['active' => 0]);

        // SET ACTIVE TO TRUE THE GIVEN ID
        $actionMessage = '';
        if($action == 'activate')
        {
            $this->crud->model::where('active', 1)->update(['active' => 0]);
            $updateActive  = $this->crud->model::find($id)->update(['active' => 1]);
            $actionMessage = 'Activating'; 
        } else 
        {
            $updateActive  = $this->crud->model::find($id)->update(['active' => 0]);
            $actionMessage = 'Deactivating';
        }

        if($updateActive) {
            \Alert::success("Successfully " . $actionMessage)->flash();
            return redirect()->back();
        }
            \Alert::warning("Error " . $actionMessage . "! Please Try Again...")->flash();
            return redirect()->back();
    }

    public function smartcard ()
    {
        return view('smartCard.smartcard');
    }

    public function getStudentColumns ()
    {
        // GET ALL COLUMN TABLE AND APPEND
        $studentColumns = new Student;
        $appends        = collect($studentColumns->getMutatedAttributes());
        $columns        = $studentColumns->getFillable();
        $studentColumns = $appends->merge($columns);  

        $newArray = [];
        foreach($studentColumns as $field) {
            $newArray[] = ['column_key' => $field, 'column_name' => ucwords( str_replace('_', ' ', str_replace('_id', '', $field)) )];
        }

        return response()->json($newArray);    
    }
}
