<?php 

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:Admission', 'first_time_login', 'verify_schoolyear'],
    // 'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    CRUD::resource('student', 'StudentCrudController')->with(function () {
        Route::get('student/{id}/print', 'StudentCrudController@print')->where(['id' => '[0-9]+']);
        // Route::get('student/search', 'StudentCrudController@search');
        Route::get('student/{id}/register/quickbooks', 'StudentCrudController@registerStudentQBO')->name('register.qbo')->where(['id' => '[0-9]+']);
        Route::get('student/{id}/portal/{action}', 'StudentCrudController@portalAuthorization')->where(['id' => '[0-9]+', 'action' => '^(enable|disable)$']);
        Route::get('student/{id}/create-email/', 'StudentCrudController@createEmail')->where(['id' => '[0-9]+']);
        Route::get('student/get-track', 'StudentCrudController@getTrack');
        Route::delete('student/{id}', 'StudentCrudController@delete');
        Route::get('student/activate-all-student-portal', 'StudentCrudController@activateAllStudentPortal');

        // Province, City/Municipality, and Barangay
        Route::get('student/api/provinces', '\App\Http\Controllers\GeographicController@getProvinces');
        Route::get('student/api/cities', '\App\Http\Controllers\GeographicController@getCities');
        Route::get('student/api/barangay', '\App\Http\Controllers\GeographicController@getBarangay');

        // Search Student
        Route::get('student/api/get/student', 'StudentCrudController@getStudents');
        Route::get('api/get/student/{id}', 'StudentCrudController@getStudent');

        Route::get('student/{id}/record', 'StudentCrudController@getRecord');

        // Enable / Disable Portal Account
        Route::get('student/{id}/account/enable', 'StudentCrudController@enableAccount');
        Route::get('student/{id}/account/disable', 'StudentCrudController@disableAccount');
    });

   
    CRUD::resource('student-report', 'StudentReportCrudController')->with(function () {
        Route::post('student-report/{action}/report', 'StudentReportCrudController@generateReport')
        	->where(['action' => '^(show|download)$']);
    });

    CRUD::resource('requirement', 'RequirementCrudController');

    Route::get('student/search', 'StudentCrudController@search');

    CRUD::resource('quipper-student-account', 'QuipperStudentAccountCrudController')->with(function () {
        Route::get('quipper-student-account/api/student', 'QuipperStudentAccountCrudController@searchStudent');
        
    });

    CRUD::resource('referral', 'ReferralCrudController');

});

?>