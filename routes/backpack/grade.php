<?php 

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:Grade', 'first_time_login', 'verify_schoolyear'],
    // 'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    CRUD::resource('encode-grade', 'EncodeGradeCrudController')->with(function() {
        Route::get('encode-grade/encode', 'EncodeGradeCrudController@encode');
        Route::get('encode-grade/encode/load-data', 'EncodeGradeCrudController@loadData');
        Route::get('encode-grade/encode/is-data-exist', 'EncodeGradeCrudController@isDataExist');
        Route::get('encode-grade/fetch-tabs-period', 'EncodeGradeCrudController@getTabsPeriod');
        Route::post('encode-grade/encode', 'EncodeGradeCrudController@saveEncodeGrade');

        Route::post('encode-grade/submit-grades', 'EncodeGradeCrudController@submitGrades');
        Route::get('encode-grade/submitted-grades', 'EncodeGradeCrudController@getSubmittedGrades');
    });


    // ENCODE GRADES (SUMMARY GRADES API's)
    Route::get('summary-grade', 'EncodeGradeCrudController@getSummaryGrades');


    CRUD::resource('period', 'PeriodCrudController')->with(function () {
        Route::post('period/sequence/update', 'PeriodCrudController@updateSequence');
    });
    CRUD::resource('grade-template', 'GradeTemplateCrudController');

    CRUD::resource('setup-grade', 'SetupGradeCrudController')->with(function() {
        Route::get('setup-grade/reorder-grade', 'SetupGradeCrudController@reorder');
        Route::post('setup-grade/reorder-grade', 'SetupGradeCrudController@saveReorder');    
        Route::get('setup-grade/add-item', 'SetupGradeCrudController@addItem');        
        Route::delete('setup-grade/{id}/item', 'SetupGradeCrudController@deleteItem');
        Route::post('setup-grade/get-terms', 'SetupGradeCrudController@getTerms');
        Route::post('setup-grade/get-subjects', 'SetupGradeCrudController@getSubjects');
        Route::post('setup-grade/get-periods', 'SetupGradeCrudController@getPeriods');
        // Route::get('setup-grade/search', 'SetupGradeCrudController@search');
        Route::get('setup-grade/approve/{id}', 'SetupGradeCrudController@approve');
        // Route::delete('setup-grade/delete/{template_id}/{subject_id}/{section_id}', 'SetupGradeCrudController@destroy');

        Route::get('api/setup-grade/topcolumnheader', 'SetupGradeCrudController@topcolumnheader')->name('api.setupgrade.header');
        Route::get('api/setup-grade/subcolumnheader', 'SetupGradeCrudController@subcolumnheader')->name('api.setupgrade.header');
        Route::get('api/setup-grade/datafield', 'SetupGradeCrudController@datafield')->name('api.setupgrade.datafield');
        Route::get('api/setup-grade/studentdata', 'SetupGradeCrudController@studentdata')->name('api.setupgrade.studentdata');
        Route::get('api/setup-grade/studentroster', 'SetupGradeCrudController@studentroster')->name('api.setupgrade.studentroster');
        Route::get('api/setup-grade/topcolumnheaderroster', 'SetupGradeCrudController@topcolumnheaderroster')->name('api.setupgrade.headerroster');
        Route::get('api/setup-grade/hps', 'SetupGradeCrudController@hps')->name('api.setupgrade.hps');
        Route::get('api/setup-grade/check-data', 'SetupGradeCrudController@checkData')->name('api.setupgrade.checkData');
    });

    CRUD::resource('setup-grade-item', 'SetupGradeItemCrudController');

    CRUD::resource('transmutation', 'TransmutationCrudController')->with(function () {
        Route::get('transmutation/{id}/{action}', 'TransmutationCrudController@setActive')->where(['tuition_id' => '[0-9]+', 'action' => '(activate|deactivate)$']);
        Route::get('transmutation/get-active', 'TransmutationCrudController@getActive');
    });

    CRUD::resource('submitted-grade', 'SubmittedGradeCrudController')->with(function () {
        Route::get('submitted-grade/{id}/reopen', 'SubmittedGradeCrudController@reopen');
        Route::get('submitted-grade/{id}/{publish}', 'SubmittedGradeCrudController@publish')->where(['publish' => '^(publish|unpublish)$']);
    });
    CRUD::resource('submitted-grade/{school_year_id}/school-year', 'SubmittedGradeCrudController')->where(['school_year_id' => '[0-9]+']);
    CRUD::resource('submitted-grade/{school_year_id}/school-year/{department_id}/department', 'SubmittedGradeCrudController')->where(['school_year_id' => '[0-9]+', 'department_id' => '[0-9]+']);
    CRUD::resource('submitted-grade/{school_year_id}/school-year/{department_id}/department/{teacher_id}/records', 'SubmittedGradeCrudController')
        ->where(['school_year_id' => '[0-9]+', 'department_id' => '[0-9]+', 'teacher_id' => '[0-9]+']);

    
});

?>