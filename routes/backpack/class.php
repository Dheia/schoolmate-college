<?php 

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:Class', 'first_time_login', 'verify_schoolyear'],
    // 'namespace'  => 'App\Http\Controllers\Admin',
], function () { 

    CRUD::resource('teacher-subject', 'TeacherSubjectCrudController')->with(function () {
        Route::get('teacher-subject/get-tracks', 'TeacherSubjectCrudController@getTracks');
        Route::get('teacher-subject/get-sections', 'TeacherSubjectCrudController@getSections');
        Route::get('teacher-subject/get-subjects', 'TeacherSubjectCrudController@getSubjects');
        Route::get('teacher-subject/get-terms', 'TeacherSubjectCrudController@getTerms');
        Route::get('teacher-subject/{id}/create-online-class', 'TeacherSubjectCrudController@createOnlineClass');
        Route::post('teacher-subject/transfer-class', 'TeacherSubjectCrudController@transferClass');
    });

    CRUD::resource('advisory-class', 'AdvisoryClassCrudController')->with(function () {
        Route::get('advisory-class/{id}/grades', 'AdvisoryClassCrudController@getGrades')->where(['id' => '[0-9]+']);
        Route::get('advisory-class/{id}/student-grades', 'AdvisoryClassCrudController@getStudentGrades');
        Route::get('advisory-class/{id}/print', 'AdvisoryClassCrudController@print');
    });
});

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:Class', 'first_time_login', 'verify_schoolyear', 'role:School Head'],
    // 'namespace'  => 'App\Http\Controllers\Admin',
], function () { 

    CRUD::resource('section_management', 'SectionManagementCrudController')->with(function () {
        Route::get('section_management/get-tracks', 'SectionManagementCrudController@getTracks');
    });

    CRUD::resource('student-section-assignment', 'StudentSectionAssignmentCrudController')->with(function () {
        Route::post('student-section-assignment/student/search', 'StudentSectionAssignmentCrudController@searchStudent');
        // Route::get('student-section-assignment/search', 'StudentSectionAssignmentCrudController@search');
        Route::get('student-section-assignment/{id}/student', 'StudentSectionAssignmentCrudController@getStudent');
        Route::get('student-section-assignment/{id}/print', 'StudentSectionAssignmentCrudController@print');
        // Route::get('student-section-assignment/{school_year_id}/{section_id}', 'StudentSectionAssignmentCrudController@viewSection');
        Route::get('student-section-assignment/section', 'StudentSectionAssignmentCrudController@getSection');

        Route::get('student-section-assignment/{id}/clone', 'StudentSectionAssignmentCrudController@clone');
        Route::post('student-section-assignment/{id}/clone', 'StudentSectionAssignmentCrudController@store');
    });
        
    CRUD::resource('teacher-assignment', 'TeacherAssignmentCrudController')->with(function () {
        Route::post('teacher-assignment/student/search', 'TeacherAssignmentCrudController@searchStudent');
        Route::get('teacher-assignment/{id}/print', 'TeacherAssignmentCrudController@print');
    });

    CRUD::resource('encode-grade-schedule', 'EncodeGradeScheduleCrudController');

    CRUD::resource('employee-encode-grade-schedule', 'EmployeeEncodeGradeScheduleCrudController')->with(function () {
        Route::get('employee-encode-grade-schedule/get-levels', 'EmployeeEncodeGradeScheduleCrudController@getLevels');
        Route::get('employee-encode-grade-schedule/get-sections', 'EmployeeEncodeGradeScheduleCrudController@getSections');
        Route::get('employee-encode-grade-schedule/get-subjects', 'EmployeeEncodeGradeScheduleCrudController@getSubjects');
    });

    CRUD::resource('school-calendar', 'SchoolCalendarCrudController')->with(function () {
        Route::get('school-calendar/print', 'SchoolCalendarCrudController@print');
    });
});

?>