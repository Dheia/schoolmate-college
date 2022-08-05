<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'first_time_login', 'verify_schoolyear'],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    //Dashboard
    Route::get('dashboard', 'DashboardController@index')->middleware('widget_exception');
    
}); // this should be the absolute last line of this file
Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    // if not otherwise configured, setup the "my account" routes
    // if (config('backpack.base.setup_my_account_routes')) {
    Route::get('change-password', '\App\Http\Controllers\Auth\MyAccountController@getChangePasswordForm')->name('backpack.account.password');
    Route::post('change-password', '\App\Http\Controllers\Auth\MyAccountController@postChangePasswordForm');
    // }

    Route::get('smartcard', 'CardSetupCrudController@smartcard');

    Route::group([
        'middleware' => ['first_time_login']
    ], function () {
        CRUD::resource('meeting', 'MeetingCrudController')->with(function () {
            Route::get('meeting/video_conference', 'MeetingCrudController@videoConference');
            Route::get('meeting/video_conference_status', 'MeetingCrudController@getVideoConferenceStatus');
            Route::get('meeting/video/{code}','MeetingCrudController@joinConference');
            Route::get('meeting/{class_code}/recordings', 'MeetingCrudController@getRecordings');
            Route::post('meeting/{class_code}/set-archive', 'MeetingCrudController@setArchive');
            Route::post('meeting/{class_code}/restore-archive', 'MeetingCrudController@restoreArchive');
            Route::get('meeting/api/students', 'MeetingCrudController@getStudents');
        });

        Route::post('meeting/{id}', 'MeetingCrudController@destroy')->where('id', '[0-9]+');

        Route::get('archive-meeting', 'MeetingCrudController@showArchiveMeetings');

        CRUD::resource('zoom-meeting', 'ZoomMeetingCrudController');
    });

    /*
    |--------------------------------------------------------------------------
    | Post Announcement In Dashboard
    |--------------------------------------------------------------------------
    */
    Route::post('api/announcement/post', 'AnnouncementCrudController@postAnnouncement');
}); // this should be the absolute last line of this file