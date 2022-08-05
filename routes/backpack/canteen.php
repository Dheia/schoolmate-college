<?php 

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:Canteen', 'first_time_login', 'verify_schoolyear'],
    // 'namespace'  => 'App\Http\Controllers\Admin',
], function () { 

	CRUD::resource('item-category', 'ItemCategoryCrudController');

    CRUD::resource('item-inventory-quantity-logs', 'ItemInventoryQuantityLogCrudController');

    CRUD::resource('item-inventory', 'ItemInventoryCrudController')->with(function () {
        Route::get('json-items', 'ItemInventoryCrudController@json_items');
        Route::get('pos', 'ItemInventoryCrudController@pos');
        Route::get('item-inventory/favorite/{id}', 'ItemInventoryCrudController@setFavorite')->where('id', '[0-9]+');
        Route::post('item-inventory/{id}/add-quantity', 'ItemInventoryCrudController@addQuantity')->where('id', '[0-9]+');

        Route::get('item-inventory/sales-report', 'ItemInventoryCrudController@salesReport');
        Route::get('item-inventory/sales-report/{reportPeriod}', 'ItemInventoryCrudController@salesReport');
        Route::get('item-inventory/inventory-report', 'ItemInventoryCrudController@inventoryReport');
        Route::get('item-inventory/inventory-report/{reportPeriod}', 'ItemInventoryCrudController@inventoryReport');

        Route::get('item-inventory/start-item-inventory-for-today', 'ItemInventoryCrudController@startInventoryForToday');
        Route::get('item-inventory/end-item-inventory-for-today', 'ItemInventoryCrudController@endInventoryForToday');
    });

    CRUD::resource('fund', 'FundCrudController');
    CRUD::resource('pos-transaction', 'PosTransactionCrudController');

    CRUD::resource('item-order', 'ItemOrderCrudController')->with(function () {
        Route::get('item-order/api/get/items', 'ItemOrderCrudController@getItems');
        Route::post('item-order/pickup', 'ItemOrderCrudController@pickup');
        Route::post('item-order/approved', 'ItemOrderCrudController@approved');
    });
    CRUD::resource('item-order-summary', 'ItemOrderSummaryCrudController');

});

?>