<?php 

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:Assets', 'first_time_login', 'verify_schoolyear'],
    // 'namespace'  => 'App\Http\Controllers\Admin',
], function () {

    CRUD::resource('building', 'BuildingCrudController');
    CRUD::resource('room', 'RoomCrudController');
    CRUD::resource('asset-inventory', 'AssetInventoryCrudController')->with(function() {
        Route::get('asset-inventory/{id}/update', 'AssetInventoryCrudController@updateRoom')->where('id', '[0-9]+');
        Route::get('asset-inventory/search', 'AssetInventoryCrudController@search');
        Route::get('asset-inventory/qr-view/{id}', 'AssetInventoryCrudController@QRRender')->where('id', '[0-9]+');
        Route::post('asset-inventory/building/{building_id}/rooms', 'AssetInventoryCrudController@buildingRooms')->where('id', '[0-9]+');
        Route::get('asset-inventory/print', 'AssetInventoryCrudController@print');
    });

    CRUD::resource('locker', 'LockerInventoryCrudController')->with(function(){
        Route::post('locker/{id}/assign', 'LockerInventoryCrudController@assign')->where('id', '[0-9]+');
        Route::get('locker/{id}/read', 'LockerInventoryCrudController@read')->where('id', '[0-9]+');
        Route::get('locker-inventory/qr-view/{id}', 'LockerInventoryCrudController@QRRender')->where('id', '[0-9]+');
        // Route::get('locker/search', 'LockerInventoryCrudController@search');
    });
    
});

?>