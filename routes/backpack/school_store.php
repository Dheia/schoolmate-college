<?php 

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:School Store', 'first_time_login', 'verify_schoolyear'],
], function () { 

	CRUD::resource('schoolstore/category', 'SchoolStoreCategoryCrudController');

    CRUD::resource('schoolstore/inventory-quantity-logs', 'SchoolStoreInventoryQuantityLogCrudController');

    CRUD::resource('schoolstore/inventory', 'SchoolStoreInventoryCrudController')->with(function () {
        Route::get('json-items', 'SchoolStoreInventoryCrudController@json_items');
        // Route::get('pos', 'SchoolStoreInventoryCrudController@pos');
        Route::get('schoolstore/inventory/favorite/{id}', 'SchoolStoreInventoryCrudController@setFavorite')->where('id', '[0-9]+');
        Route::post('schoolstore/inventory/{id}/add-quantity', 'SchoolStoreInventoryCrudController@addQuantity')->where('id', '[0-9]+');

        Route::get('schoolstore/inventory/sales-report', 'SchoolStoreInventoryCrudController@salesReport');
        Route::get('schoolstore/inventory/sales-report/{reportPeriod}', 'SchoolStoreInventoryCrudController@salesReport');
        Route::get('schoolstore/inventory/inventory-report', 'SchoolStoreInventoryCrudController@inventoryReport');
        Route::get('schoolstore/inventory/inventory-report/{reportPeriod}', 'SchoolStoreInventoryCrudController@inventoryReport');

        Route::get('schoolstore/inventory/start-item-inventory-for-today', 'SchoolStoreInventoryCrudController@startInventoryForToday');
        Route::get('schoolstore/inventory/end-item-inventory-for-today', 'SchoolStoreInventoryCrudController@endInventoryForToday');
    });

});

?>