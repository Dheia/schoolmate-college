<?php

// ADMIN ROUTE
Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:Accounting', 'first_time_login', 'verify_schoolyear'],
    'namespace'  => '\App\Http\Controllers\Admin',
], function () { // custom admin routes

    CRUD::resource('online-payments', 'PaynamicsPaymentCrudController')->with(function () {
        Route::post('online-payments/{id}/publish', 'PaynamicsPaymentCrudController@publishPayment');
        Route::get('api/online-payments/{id}/payment-types/get', 'PaynamicsPaymentCrudController@getPaymentTypes');
    });

});

// PUBLIC ROUTE
Route::group([
    'prefix' => 'online-payment',
], function () {


    Route::get('/', 'OnlinePaymentController@index');
    Route::post('/', 'OnlinePaymentController@submitForm');
    Route::get('execute-payment', 'OnlinePaymentController@executePayment');
    Route::post('student/{studentnumber}/tuition', 'OnlinePaymentController@getTuition');

    Route::post('webhooks', 'OnlinePaymentController@webhooks');

    // Route::post('paynamics/notification', '\App\Http\Controllers\Admin\PaynamicsPaymentCrudController@notification');
    // Route::get('paynamics/notification', '\App\Http\Controllers\PaynamicV2@notification');
    Route::post('paynamics/notification', '\App\Http\Controllers\PaynamicV2@notification');
    // Route::get('paynamics/response/{prefix}/{studentnumber}/{request_id}', '\App\Http\Controllers\PaynamicV2@response');
    Route::get('paynamics/cancel/{prefix}/{studentnumber}', '\App\Http\Controllers\PaynamicV2@cancel');

    Route::get('paynamics/response', '\App\Http\Controllers\Admin\PaynamicsPaymentCrudController@response');
    // Route::get('paynamics/cancel', '\App\Http\Controllers\Admin\PaynamicsPaymentCrudController@cancel');
    Route::get('paynamics/query', '\App\Http\Controllers\Admin\PaynamicsPaymentCrudController@createQuery');
});
