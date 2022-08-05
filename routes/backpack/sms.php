<?php 

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:SMS', 'first_time_login', 'verify_schoolyear'],
    // 'namespace'  => 'App\Http\Controllers\Admin',
], function () {

    CRUD::resource('sms', 'StudentSmsTaggingCrudController');
    CRUD::resource('admin-sms-register', 'AdminSmsTaggingCrudController');
    CRUD::resource('turnstile-sms-receipent', 'AssignTurnstileSmsReceiverCrudController');
    CRUD::resource('smslog', 'SmsLogCrudController');
    CRUD::resource('text-blast', 'TextBlastCrudController');

    Route::get('sms/search', 'Sms@search');
    Route::get('smart/authorize', 'SmartJwtCredentialCrudController@authorize');
    Route::get('smart/sendsms', 'SmartJwtCredentialCrudController@sendSms');
    Route::get('smart/groups', 'SmartJwtCredentialCrudController@listGroups')->name('list.groups');
    Route::get('smart/groups/create', 'SmartJwtCredentialCrudController@createGroups')->name('create.groups');    

    Route::get('smart/student/{id}/subscribe', 'SmartJwtCredentialCrudController@subscribe')->name('subscribe');


});

?>