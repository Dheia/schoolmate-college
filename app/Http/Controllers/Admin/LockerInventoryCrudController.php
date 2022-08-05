<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LockerInventoryRequest as StoreRequest;
use App\Http\Requests\LockerInventoryRequest as UpdateRequest;
use App\Http\Requests\LockerInventoryLogRequest;

use Validator;

use Illuminate\Http\Request;

use App\Models\LockerInventory;
use App\Models\Student;
use App\LockerInventoryLog;

/**
 * Class LockerInventoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LockerInventoryCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setDefaultPageLength(10); 
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\LockerInventory');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/locker');
        $this->crud->setEntityNameStrings('locker', 'locker management');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in LockerInventoryRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->orderFields(['building_id', 'name', 'description', 'is_active']);

        $this->crud->addField(
            [
               // 1-n relationship
               'label' => "Building", // Table column heading
               'type' => "select",
               'name' => 'building_id', // the column that contains the ID of that connected entity;
               'entity' => 'building', // the method that defines the relationship in your Model
               'attribute' => "name", // foreign key attribute that is shown to user
               'model' => "App\Models\Building", // foreign key model
            ]
        );

        if($this->crud->getActionMethod() == "create")
        {

            $this->crud->addField([
                'name' => 'searchInput',
                'type' => 'searchStudent',
                'label' => 'Search',
                'attributes' => [
                    'id' => 'searchInput',
                    'placeholder' => 'Search For Name or ID Number (ex. 1100224)'
                ]
            ])->beforeField('studentnumber');

            $this->crud->addField([
                'name' => 'studentnumber',
                'type' => 'hidden',
                'attributes' => [
                    'id' => 'studentNumber'
                ]
            ]);
        
        } else {
            $this->crud->removeField('studentnumber');
        }


        $this->crud->addField(
            [
               'name' => "is_active", // The db column name
               'label' => "Active", // Table column heading
               'type' => "checkbox",
            ]
        );
        
        $this->crud->addColumn([
            'name' => 'studentnumber',
            'type' => 'text',
            'label' => 'Student No.',
            'prefix' => \Setting::get('schoolabbr') . '-',
        ]);

       $this->crud->addColumn([
            'label'  => 'Fullname',
            'name'   => 'student.fullname',
            'type'   => 'text',
            'searchLogic' => function ($query, $column, $searchTerm) {
                // $query->with('student');
                // $query->orWhere('student.firstname', 'like', '%'.$searchTerm.'%');
                // $query->orWhere('student.lastname', 'like', '%'.$searchTerm.'%');
            }
        ])->afterColumn('studentnumber');
        
        $this->crud->addColumn(
            [
               'name' => "is_active", // The db column name
               'label' => "Occupied", // Table column heading
               'type' => "check",
            ]
        );

        $this->crud->addColumn(
            [
               'label' => "Building", // Table column heading
               'type' => "select",
               'name' => 'building_id', // the method that defines the relationship in your Model
               'entity' => 'building', // the method that defines the relationship in your Model
               'attribute' => "name", // foreign key attribute that is shown to user
               'model' => "App\Models\Building", // foreign key model
            ]
        );


        
        $this->crud->allowAccess('qr');
        $this->crud->addButtonFromView('line', 'qr', 'qr_locker', 'end');


        $this->crud->addFilter([ // select2 filter
          'name' => 'building_id',
          'type' => 'select2',
          'label'=> 'Building'
        ], function() {
            return \App\Models\Building::all()->pluck('name', 'id')->toArray();
        }, function($value) { // if the filter is active
                $this->crud->addClause('where', 'building_id', $value);
        });

        $this->crud->addFilter([ // simple filter
          'type' => 'simple',
          'name' => 'is_active',
          'label'=> 'Active'
        ], 
        false, 
        function() { // if the filter is active
            $this->crud->addClause('where','is_active', 1); // apply the "active" eloquent scope 
        } );

        $this->crud->addButtonFromView('line', 'AssignLocker', 'assetInventory.assignLocker', 'end');
        $this->crud->addButtonFromView('line', 'read', 'lockerInventory.read', 'beginning');

        // dd($this);
    }

    public function read($id)
    {
        $crud        = $this->crud;
        $locker      = LockerInventory::with(['student', 'building'])->where('id',$id)->first();
        $logs        = LockerInventoryLog::with('user')->where('locker_id',$id)->get();
        
        return view('lockerInventory.read', compact('crud','locker', 'logs'));
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
    
    public function showQrCode ($id)
    {
        $inventory = LockerInventory::with(['building','student.yearManagement','student.schoolYear'])->where('id', $id)->get();
        dd("Hello");
        return view('custom.show_locker_inventory', compact('inventory'));
    }


    public function assign ($id, LockerInventoryLogRequest $request)
    {
        $locker     = $this->crud->model->where('id',$id)->first();
        $student    = Student::where('studentnumber', $request->studentnumber)->first();
        $oldStudent = $locker->studentnumber;

          /*************************/
         /*      VALIDATION      */
        /************************/

        // Check If Locker Is Not Existing Then, Redirect To Intended Route
        if(!$locker->exists()) 
        {
            \Alert::warning("Locker Not Found")->flash();
            return redirect()->to($this->crud->route);
        }

        // Check If Student Is Not Exist Then, Redirect To Intended Route
        if(!$student->exists())
        {
            \Alert::warning("Student Not Found")->flash();
            return redirect()->to($this->crud->route);
        }

        $updateLocker = $locker->update(['studentnumber' => $request->studentnumber]);
        
        if($updateLocker)
        {
            $lockerLog                  = new LockerInventoryLog;
            $lockerLog->user_id         = backpack_auth()->user()->id;
            $lockerLog->locker_id       = $id;
            $lockerLog->old_student_no  = $oldStudent;
            $lockerLog->new_student_no  = $request->studentnumber;
            $lockerLog->description     = $request->description;

            if($lockerLog->save())
            {
                \Alert::success("Successfully Updated Locker")->flash();
                return redirect()->to($this->crud->route);
            }
        }
    }


    public function QRRender ($id)
    {

        $model = $this->crud->model::findOrFail($id);
        if($this->crud->hasAccess('qr'))
        {
            $qrCode = \QrCode::size(150)->generate(request()->getSchemeAndHttpHost() . '/admin/locker-inventory/' . $id . '/show');

            // QrCode::size(150)->generate(Request::root() . '/admin/locker-inventory/' . $entry->getKey() . '/show'
            return $qrCode;
        } else {
            abort(401);
        }
    }




    // public function search()
    // {
    //     $this->crud->hasAccessOrFail('list');
    //     $this->crud->setOperation('list');

    //     $totalRows = $this->crud->model->count();
    //     $filteredRows = $this->crud->count();
    //     $startIndex = $this->request->input('start') ?: 0;
    //     // if a search term was present
    //     if ($this->request->input('search') && $this->request->input('search')['value']) {
    //         // filter the results accordingly
    //         $this->crud->applySearchTerm($this->request->input('search')['value']);
    //         // recalculate the number of filtered rows
    //         $filteredRows = $this->crud->count();
    //     }
    //     // start the results according to the datatables pagination
    //     if ($this->request->input('start')) {
    //         $this->crud->skip($this->request->input('start'));
    //     }
    //     // limit the number of results according to the datatables pagination
    //     if ($this->request->input('length')) {
    //         $this->crud->take($this->request->input('length'));
    //     }
    //     // overwrite any order set in the setup() method with the datatables order
    //     if ($this->request->input('order')) {
    //         $column_number = $this->request->input('order')[0]['column'];
    //         $column_direction = $this->request->input('order')[0]['dir'];
    //         $column = $this->crud->findColumnById($column_number);
    //         if ($column['tableColumn']) {
    //             // clear any past orderBy rules
    //             $this->crud->query->getQuery()->orders = null;
    //             // apply the current orderBy rules
    //             $this->crud->query->orderBy($column['name'], $column_direction);
    //         }

    //         // check for custom order logic in the column definition
    //         if (isset($column['orderLogic'])) {
    //             $this->crud->customOrderBy($column, $column_direction);
    //         }
    //     }
    //     $entries = $this->crud->getEntries();

    //     return $this->crud->getEntriesAsJsonForDatatables($entries, $totalRows, $filteredRows, $startIndex);
    // }
}
