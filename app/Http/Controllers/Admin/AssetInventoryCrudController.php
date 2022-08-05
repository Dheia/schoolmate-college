<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AssetInventoryRequest as StoreRequest;
use App\Http\Requests\AssetInventoryRequest as UpdateRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Room;
use App\Models\AssetInventory;
use App\Models\AssetInventoryMovementsLogs;

/**
 * Class AssetInventoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AssetInventoryCrudController extends CrudController
{
    public function setup()
    {
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
        $this->crud->setModel('App\Models\AssetInventory');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/asset-inventory');
        $this->crud->setEntityNameStrings('Asset', 'Asset Inventories');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        // add asterisk for fields that are required in AssetInventoryRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->orderFields([
            'building_id',
            'room_id'
        ]);

        $this->crud->addField([
            'name' => 'assetScript',
            'type' => 'assetInventory.asset_script',
            'label' => ''
        ]);


        // FiELDS
        $this->crud->addField([
            'label'     => 'Building',
            'type'      => 'select2',
            'name'      => 'building_id',
            'entity'    => 'building',
            'attribute' => 'name',
            'model'     => 'App\Models\Building',
            'wrapperAttributes' => ['class' => 'form-group col-md-6']
        ]);

        $this->crud->addField([
            'label'             => "Room Deployed At",
            'type'              => 'select2_from_array',
            'name'              => 'room_id', // the db column for the foreign key
            'options'           => [],
            'wrapperAttributes' => ['class' => 'form-group col-md-6']
        ]);

        $this->crud->addField([
            'name' => "user_id",
            'type' => 'hidden',
            'value' => backpack_auth()->user()->id,
        ]);



        $this->crud->addField([
            'name' => "updated_by",
            'type' => 'hidden',
            'value' => backpack_auth()->user()->id,
        ]);

        $this->crud->addField([
            'name' => 'condition',
            'label' => "Condition",
            'type' => 'select_from_array',
            'options' => ['Good Condition' => 'Good Condition', 'Not in Good Condition' => 'Not in Good Condition'],
        ]);

        // COLUMNS
        $this->crud->addColumn(
            [
               'name' => 'items', // The db column name
               'label' => "Parts", // Table column heading
               'type' => 'table',
               'columns' => [
                    'name' => 'Name',
                    'description' => 'Description',
                    'serialno' => 'Serial No.'
                ]
            ]
        );

         $this->crud->addColumn([
               'label' => "Room Deployed At",
               'type' => 'select',
               'name' => 'room_id', // the db column for the foreign key
               'entity' => 'room', // the method that defines the relationship in your Model
               'attribute' => 'name', // foreign key attribute that is shown to user
               'model' => "App\Models\Room",
        ]);        

        $this->crud->addColumn([
            'name' => 'room_id',
            'type' => 'select',
            'label' => 'Room',
            'model' => "App\Models\Room",
            'entity' => 'room', // the method that defines the relationship in your Model
            'attribute' => 'name',
            'pivot' => true

        ]);

        $this->crud->addColumn([
            'name' => 'building_id',
            'type' => 'select',
            'label' => 'Room',
            'model' => "App\Models\Building",
            'entity' => 'room', // the method that defines the relationship in your Model
            'attribute' => 'name',
            'pivot' => true

        ]);

        $this->crud->addColumn([
            'name' => 'building_name',
            'type' => 'text',
            'label' => 'Building',

        ]);

        $this->crud->addColumn([
            'name' => 'user_name',
            'type' => 'text',
            'label' => 'Created by',

        ]);

        $this->crud->addColumn([
            'name' => 'user_update',
            'type' => 'text',
            'label' => 'Last Update by',

        ]);


        // $this->crud->with('revisionHistory');




        // $this->crud->addFields($array_of_arrays, 'update/create/both');
        $this->crud->removeColumns(['serialno','items','user_id','updated_by']);
        // $this->crud->removeField('updated_by', 'both');


        $this->crud->child_resource_included = ['select' => false, 'number' => false];

        $this->crud->addField([
            'name' => 'items',
            'label' => 'Parts',
            'type' => 'child_inventory',
            'entity_singular' => 'item', // used on the "Add X" button
            'columns' => [
                            [
                                'label' => 'Name',
                                'type' => 'text_table',
                                'name' => 'name',
                                'entity' => 'name',

                                
                            ],
                            // ['label' => 'Type',
                            //     'type' => 'child_select_inventory',
                            //     'name' => 'type',
                            //     'entity' => 'type',
                            //     'attribute' => 'name',
                            //     'size' => '3',
                            //     'model' => "App\Models\Type"
                            // ],
                            [
                                'label' => 'Description',
                                'type' => 'text_table',
                                'name' => 'description',
                                'entity' => 'description',
                                
                            ],
                            [
                                'label' => 'Serial No',
                                'type' => 'text_table',
                                'name' => 'serial',
                                'entity' => 'serial',
                                // 'options' => ['one' => 'One', 'two' => 'Two'],
                                
                            ],
                        ],
            'max' => 10, // maximum rows allowed in the table
            'min' => 0 // minimum rows allowe
        ]);


        $this->crud->addFilter([ // select2 filter
          'name' => 'room_id',
          'type' => 'select2',
          'label'=> 'Room'
        ], function() {
            return \App\Models\Room::all()->pluck('name', 'id')->toArray();
        }, function($value) { // if the filter is active
                $this->crud->addClause('where', 'room_id', $value);
        });


        $this->crud->addFilter([ // select2 filter
          'name' => 'building_id',
          'type' => 'select2',
          'label'=> 'Bulding'
        ], function() {
            return \App\Models\Building::all()->pluck('name', 'id')->toArray();
        }, function($value) { // if the filter is active
                $this->crud->addClause('where', 'building_id', $value);
        });
        
        $this->crud->enableAjaxTable();

        $this->crud->enableExportButtons();


        $this->crud->setDefaultPageLength(10);
        $this->crud->allowAccess('show');
        $this->crud->allowAccess('search');
        $this->crud->allowAccess(['qr']);

        $this->crud->addButtonFromView('line', 'qr', 'assetInventory.qr', 'end');
        $this->crud->addButtonFromView('top', 'Print', 'assetInventory.print', 'end');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here

        // CHECK BUILDING ID AND ROOM ID ARE RELATED TO EACH OTHER
        $building_rooms = Room::where('building_id', $request->building_id)->pluck('id')->flatten();

        if(!$building_rooms->contains($request->room_id))
        {
            \Alert::warning("Invalid Room")->flash();
            return redirect()->back();
        }

        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here

        // CHECK BUILDING ID AND ROOM ID ARE RELATED TO EACH OTHER
        $building_rooms = Room::where('building_id', $request->building_id)->pluck('id')->flatten();

        if(!$building_rooms->contains($request->room_id))
        {
            \Alert::warning("Invalid Room")->flash();
            return redirect()->back();
        }

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
        if(backpack_auth()->check()){
            $rooms     = Room::all();
            $logs      = AssetInventoryMovementsLogs::with('room')->with('oldRoom')
                                                    ->where('asset_inventory_id', $id)
                                                    ->orderBy('created_at', 'desc')
                                                    ->get();

            $inventory = AssetInventory::with('room')
                                        ->where('id', $id)
                                        ->first();
                                    
        return view('custom.show_inventory', compact('inventory', 'rooms', 'logs'));
        }else{
            return redirect()->route('login');
        }

    }


    public function updateRoom ($id, Request $request)
    {   

        if(backpack_auth()->check()){
            $asset          = AssetInventory::findOrFail($id);
            $old_room_id    = $asset->room_id;

            $asset->room_id = $request->room_id;
            $saveAsset      = $asset->save();

            $log            = new AssetInventoryMovementsLogs;

            if($saveAsset) {

                $log->user_id            = backpack_auth()->user()->id;
                $log->room_id            = $request->room_id;
                $log->old_room_id        = $old_room_id;
                $log->asset_inventory_id = $id;
                $log->description        = $request->description;
                $saveLog                 = $log->save();

                if($saveLog) {
                    return redirect()->back();
                }
            }
        }

    }

    public function QRRender ($id)
    {
        $model = $this->crud->model::findOrFail($id);
        if($this->crud->hasAccess('qr'))
        {
            $qrCode = \QrCode::size(150)->generate(request()->getSchemeAndHttpHost() . '/asset-inventory/' . $id. '/show');
            return $qrCode;
        } else {
            abort(401);
        }
    }

    public function buildingRooms ($building_id)
    {
        $rooms = Room::where('building_id', $building_id)->select('id', 'name')->get();
        return response()->json($rooms);
    }

    public function print ()
    {
        $datas = $this->crud->model::all();

        $schoollogo             =   config('settings.schoollogo') 
        ? (string)\Image::make(config('settings.schoollogo'))->encode('data-url') 
        : null;
        $schoolmate_logo        =   (string)\Image::make('images/schoolmate_logo.jpg')->encode('data-url');
        
        if(count($datas) == 0) {
            \Alert::warning('Tangible Asset is empty.')->flash();
            return redirect()->back();
        }
        
        return view('custom.generateReport', compact('datas','schoollogo', 'schoolmate_logo'));
    }
}
