<?php


Route::get('kiosk', function () { return redirect('kiosk/enlisting'); });

Route::group([
    'prefix'     => 'kiosk',
    'namespace'  => 'Kiosk',
], function () { // custom admin routes

    Route::get('enlisting', 'EnlistingController@index');
    // Route::match(['get', 'post'], 'enlisting/{type}/{studentnumber?}', 'EnlistingController@displayAccordingStudentType')->where(['type' => '^(old|new)$', 'studentnumber' => '[0-9]+']);
    Route::match(['get', 'post'], 'enlisting/{type}/{item_id?}/{studentnumber?}', 'EnlistingController@displayAccordingStudentType')->where(['type' => '^(old|new)$', 'studentnumber' => '[0-9]+', 'item_id' => '[0-9]+']);

    Route::post('enlisting/old/{item_id?}/submit', 'EnlistingController@enrollmentSubmit')->where(['item_id' => '[0-9]+']);
    Route::post('enlisting/new/{item_id?}/student', 'EnlistingController@newStudentStore')->where(['item_id' => '[0-9]+']);

    // Route::get('enlisting/api/level-track/{level_id}', 'EnlistingController@levelTrack')->where(['id' => '[0-9]+']);
    Route::get('enlisting/api/department/{department_id}', 'EnlistingController@department')->where(['department_id' => '[0-9]+']);

    Route::get('download/enlist-form/{id}', 'EnlistingController@downloadPdfForm');

    // Province, City/Municipality, and Barangay
    Route::get('enlisting/api/provinces', '\App\Http\Controllers\GeographicController@getProvinces');
    Route::get('enlisting/api/cities', '\App\Http\Controllers\GeographicController@getCities');
    Route::get('enlisting/api/barangay', '\App\Http\Controllers\GeographicController@getBarangay');
    Route::get('enlisting/privacy', 'EnlistingController@privacy');

    Route::get('enlisting/tuition-setting', 'EnlistingController@getKioskTuitionSetting');
});
