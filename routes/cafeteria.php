<?php

Route::group([
    'prefix'     => 'cafeteria',
], function () { // custom admin routes

    Route::get('/', 'CafeteriaController@index');
    Route::get('{id}/{category_name}', 'CafeteriaController@getItems');
    // Route::post('cafeteria/submit-order', 'CafeteriaController@submitOrder');
    Route::match(['get', 'post'], 'submit-order', 'CafeteriaController@submitOrder');
    Route::post('student/login', 'CafeteriaController@studentLogin');
});