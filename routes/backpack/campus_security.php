<?php 

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:Campus', 'first_time_login', 'verify_schoolyear'],
    // 'namespace'  => 'App\Http\Controllers\Admin',
], function () {

	CRUD::resource('rfid-connect', 'RfidCrudController');

    Route::get('updateredis','RfidCrudController@updateRedis');
    Route::get('updateredisin','RfidCrudController@updateRedisAllowIn');

    CRUD::resource('rfid-connect', 'RfidCrudController');
    Route::get('rfid-connect/search', 'RfidCrudController@search');
    CRUD::resource('turnstile-log', 'TurnstileLogCrudController')->with(function () {
        Route::get('turnstile-log/search', 'TurnstileLogCrudController@search');
    });
    CRUD::resource('turnstile', 'TurnstileCrudController')->with(function () {
        Route::get('turnstile/ping', 'TurnstileCrudController@ping');
        Route::get('turnstile/reboot', 'TurnstileCrudController@reboot');    
    });

    // Smart Card
    CRUD::resource('smartcard/card-setup', 'CardSetupCrudController')->with(function () {
    Route::post('card-setup/save', 'CardSetupCrudController@save');
    Route::get('smartcard/card-setup/{action}/{id}', 'CardSetupCrudController@setActive')
        ->where([ 'action' => '(activate|deactivate)$', 'id' => '[0-9]+', ]);
    });

    CRUD::resource('smartcard/card-printing', 'CardPrintingCrudController')->with(function () {
        Route::get('smartcard/card-printing/student/{studentnumber}', 'CardPrintingCrudController@selectStudent')->where(['studentnumber' => '[0-9]+']);
        Route::post('smartcard/card-printing/print', 'CardPrintingCrudController@printPDF');
        Route::get('smartcard/card-printing/save-logs', 'CardPrintingCrudController@saveLogs');
        Route::get('smartcard/card-printing/change-template', 'CardPrintingCrudController@changeTemplate');

        Route::get('smartcard/api/student-columns', 'CardSetupCrudController@getStudentColumns');
    });

    CRUD::resource('smartcard/print-logs', 'SmartCardPrintLogsCrudController');

});

?>