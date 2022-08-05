<?php 

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:HR', 'first_time_login', 'verify_schoolyear'],
    // 'namespace'  => 'App\Http\Controllers\Admin',
], function () {

    CRUD::resource('employee', 'EmployeeCrudController')->with(function () {
        Route::get('api/employee/search/{string}', 'EmployeeCrudController@searchEmployee');
        Route::get('api/get/employee/{stuentNumber}', 'EmployeeCrudController@getEmployee')->where('studentnumber', '[0-9]+');
        Route::get('employee/{id}/qbo-connect', 'EmployeeCrudController@QBORegisterEmployee')->where('id', '[0-9]+');

        Route::get('employee/{id}/create-email', 'EmployeeCrudController@createEmail')->where('id', '[0-9]+');
        Route::post('employee/{id}/add-leave', 'EmployeeCrudController@addLeave')->where('id', '[0-9+]');
        Route::get('employee/{id}/reset-password', 'EmployeeCrudController@resetPassword')->where('id', '[0-9]+');
        Route::get('employee/{id}/review-status', 'EmployeeCrudController@reviewStatus')->where('id', '[0-9]+');
        Route::get('employee/{id}/review-status/update', 'EmployeeCrudController@updateStatus')->where('id', '[0-9]+');
        Route::get('employee/{id}/generate_coe','EmployeeCrudController@printcoe');

        Route::get('employee/{id}/qr-code/generate', 'EmployeeCrudController@generateQRCode')->where('id', '[0-9]+');
        Route::get('/employee/print', 'EmployeeCrudController@print');
    });

    CRUD::resource('employee-attendance', 'EmployeeAttendanceCrudController')->with(function () {
        Route::get('employee-attendance/full-run-report', 'EmployeeAttendanceCrudController@fullRunReport');
        Route::post('employee-attendance/{employee_id}/download','EmployeeAttendanceCrudController@downloadEmployeeAttendance');
        Route::get('api/employee-attendance/{id}/attendance-logs', 'EmployeeAttendanceCrudController@employeeAttendanceLogs');
    });
    CRUD::resource('employment-status','EmploymentStatusCrudController');
    CRUD::resource('leave','LeaveCrudController');

    CRUD::resource('schedule-template', 'ScheduleTemplateCrudController');
    CRUD::resource('schedule-tagging', 'ScheduleTaggingCrudController');
    CRUD::resource('employment-status-history', 'EmploymentStatusHistoryCrudController');

});

?>