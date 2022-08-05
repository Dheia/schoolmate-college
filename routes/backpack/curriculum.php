<?php 

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:Curriculum', 'first_time_login', 'verify_schoolyear'],
    // 'namespace'  => 'App\Http\Controllers\Admin',
], function () {

    CRUD::resource('curriculum_management', 'CurriculumManagementCrudController')->with(function () {
        Route::get('curriculum_management/{id}/print', 'CurriculumManagementCrudController@print')->where(['id' => '[0-9]+']);
        Route::get('curriculum_management/{id}/subjects', 'CurriculumManagementCrudController@getCurriculumWithSubjects')->where(['id' => '[0-9]+']);
    });


    CRUD::resource('course_management', 'CourseManagementCrudController')->with(function () {

    });
    CRUD::resource('subject_management', 'SubjectManagementCrudController')->with(function () {
        Route::get('subject_management/search', 'SubjectManagementCrudController@search');
        Route::post('api/subject/{id}', 'SubjectManagementCrudController@subject')->where(['id' => '[0-9]+']);
    });

    CRUD::resource('term-management', 'TermManagementCrudController');
    CRUD::resource('subject-mapping', 'SubjectMappingCrudController');
    Route::get('subject-mapping/{id}/print', 'SubjectMappingCrudController@print');
});

?>