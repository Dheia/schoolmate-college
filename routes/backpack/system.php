<?php 

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:System', 'first_time_login'],
    // 'namespace'  => 'App\Http\Controllers\Admin',
], function () {

    CRUD::resource('schoolyear', 'SchoolYearCrudController')->with(function () {
        Route::get('schoolyear/{id}/active', 'SchoolYearCrudController@setActive')->name('SchoolYearActive')->where('id', '[0-9]+');
        Route::get('schoolyear/{id}/deactive', 'SchoolYearCrudController@setDeactive')->name('SchoolYearDective')->where('id', '[0-9]+');
       
        Route::get('schoolyear/{id}/enrollment/active', 'SchoolYearCrudController@enableEnrollment');
        Route::get('schoolyear/{id}/enrollment/deactive', 'SchoolYearCrudController@disableEnrollment');

        // RESEQUENCE
        Route::get('schoolyear/resequence', 'SchoolYearCrudController@resequence');
        Route::post('schoolyear/resequence/save', 'SchoolYearCrudController@saveSequence');
    });

    Route::group([
        'middleware' => ['verify_schoolyear']
    ], function () {
        CRUD::resource('non-academic-department', 'NonAcademicDepartmentCrudController');
        CRUD::resource('department', 'DepartmentCrudController')->with(function () {
            Route::get('department/{id}/get', 'DepartmentCrudController@getDepartment')->where(['id' => '[0-9]+']);
            Route::get('department/{id}/activate', 'DepartmentCrudController@setActive')->name('DepartmentActivate')->where('id', '[0-9]+');
            Route::get('department/{id}/deactivate', 'DepartmentCrudController@setDeactive')->name('DepartmentDeactivate')->where('id', '[0-9]+');
       
        });
        
    	CRUD::resource('level', 'LevelCrudController');
        CRUD::resource('year_management', 'YearManagementCrudController')->with(function () {
    	    Route::get('year_management/resequence', 'YearManagementCrudController@resequence');
    	    Route::post('year_management/resequence/save', 'YearManagementCrudController@saveSequence');
        });

    	CRUD::resource('strand', 'TrackManagementCrudController')->with(function () {
            Route::get('strand/{id}/activate', 'TrackManagementCrudController@setActive')->name('StrandActivate')->where('id', '[0-9]+');
            Route::get('strand/{id}/deactivate', 'TrackManagementCrudController@setDeactive')->name('StrandDeactivate')->where('id', '[0-9]+');
        });

        CRUD::resource('curriculum_management', 'CurriculumManagementCrudController')->with(function () {
            Route::get('curriculum_management/{id}/print', 'CurriculumManagementCrudController@print')->where(['id' => '[0-9]+']);
            Route::get('curriculum_management/{id}/subjects', 'CurriculumManagementCrudController@getCurriculumWithSubjects')->where(['id' => '[0-9]+']);
            Route::get('curriculum_management/{id}/active', 'CurriculumManagementCrudController@setActive')->name('CurriculumActive')->where('id', '[0-9]+');
            Route::get('curriculum_management/{id}/deactive', 'CurriculumManagementCrudController@setDeactive')->name('CurriculumDeactive')->where('id', '[0-9]+');
        });

        CRUD::resource('setting', 'SettingCrudController');
        CRUD::resource('permission', 'PermissionCrudController');
        CRUD::resource('role', 'RoleCrudController');
        CRUD::resource('user', 'UserCrudController')->with(function () {
            Route::post('user/reset-password/{id}', 'UserCrudController@resetPassword')
                ->name('user.reset-password')->where('id', '[0-9]+');
            Route::get('api/user/notification', 'UserCrudController@unreadNotifications')
                ->name('admin.user.notification');
        });
        CRUD::resource('action', 'ActionCrudController');
        CRUD::resource('announcement', 'AnnouncementCrudController');

        CRUD::resource('calendar-event', 'CalendarEventCrudController');
        CRUD::resource('authentication-log', 'AuthenticationLogCrudController');

        CRUD::resource('system-attendance', 'SystemAttendanceCrudController')->with(function () {
            Route::post('system-attendance/tap-in', 'SystemAttendanceCrudController@userTapIn');
            Route::post('system-attendance/tap-out', 'SystemAttendanceCrudController@userTapOut');
        });
        Route::get('system-attendance-report', 'Reports\SystemAttendanceController@index');
        Route::post('system-attendance-report/download', 'Reports\SystemAttendanceController@generateReport');
        Route::get('system-attendance-report/attendance-logs', 'Reports\SystemAttendanceController@logs');

        CRUD::resource('email-log', 'EmailLogCrudController');

        CRUD::resource('zoom-user', 'ZoomUserCrudController');
        CRUD::resource('icon', 'IconCrudController');
    });

    
});

?>