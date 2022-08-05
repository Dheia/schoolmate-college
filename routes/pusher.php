<?php 

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    // 'namespace'  => 'App\Http\Controllers\Admin',
], function () {

    /*
    |--------------------------------------------------------------------------
    | Admin Pusher Routes
    |--------------------------------------------------------------------------
    */
    Route::get('api/test', function() {
        dd("Hello");
    });
    Route::get('api/pusher/beams-auth', 'PusherBeamsController@generateAdminToken');
    Route::get('api/pusher/user-data', 'PusherBeamsController@getAdminPusherData');
});

Route::group([
    'middleware' => ['auth:api'],
], function () {

    Route::get('api/v2/pusher/beams-auth', 'PusherBeamsController@generateStudentToken');
});

?>