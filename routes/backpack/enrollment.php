<?php 

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:Enrollment', 'first_time_login', 'verify_schoolyear'],
    // 'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

	CRUD::resource('enrollment', 'EnrollmentCrudController')->with(function () {
	    Route::get('enrollment/{id}/set/invoice', 'EnrollmentCrudController@setInvoice');
	    // Route::get('enrollment/search', 'EnrollmentCrudController@search');
	    Route::get('enrollment/{id}/invoice/download', 'EnrollmentCrudController@downloadInvoice');
	    Route::get('enrollment/{invoce_no}/invoice/delete', 'EnrollmentCrudController@deleteInvoice');
	    Route::get('enrollment/{id}/print', 'EnrollmentCrudController@print');
	    Route::get('enrollment/tuition-forms', 'EnrollmentCrudController@getTuitions');
	    Route::get('enrollment/{id}/drop-or-transfer', 'EnrollmentCrudController@dropTransfer');
	    Route::post('enrollment/{id}/drop-or-transfer', 'EnrollmentCrudController@submitDropTransfer');
	    Route::get('enrollment/{id}/enroll', 'EnrollmentCrudController@enroll');
	    
	    Route::get('api/student/search/{string}', 'EnrollmentCrudController@searchStudent');
	    Route::get('api/get/student/{stuentNumber}', 'EnrollmentCrudController@getStudent');
	    // Route::get('api/get/sections', 'EnrollmentCrudController@getSections');

	    Route::get('enrollment/{id}/enroll', 'EnrollmentCrudController@enroll');
	    Route::post('enrollment/{id}/enroll', 'EnrollmentCrudController@submitEnrollmentForm');

	    Route::get('enrollment/{id}/tuition', 'EnrollmentCrudController@showTuition');
	    
	    // Search Student
        Route::get('enrollment/api/get/student', 'EnrollmentCrudController@getEnrolled');

        Route::post('enrollment/{id}/commitment-payment/update', 'EnrollmentCrudController@changePaymentPlan');
        Route::get('enrollment/{id}/qr-code/generate', 'EnrollmentCrudController@generateQRCode')->where('id', '[0-9]+');
        Route::get('enrollment/{id}/enable-account', 'EnrollmentCrudController@enableAccount')->where('id', '[0-9]+');
        Route::get('enrollment/{id}/disable-account', 'EnrollmentCrudController@disableAccount')->where('id', '[0-9]+');

        // Get Subject Mapping Subjects
        Route::get('enrollment/subject-mapping/api/get-subjects', 'SubjectMappingCrudController@getSubjects');
	});

    CRUD::resource('enrollment-report', 'EnrollmentReportCrudController')->with(function () {
        Route::post('enrollment-report/{action}/report', 'EnrollmentReportCrudController@generateReport')
        	->where(['action' => '^(show|download)$']);
    });

    CRUD::resource('enrollment-status', 'EnrollmentStatusCrudController')->with(function () {
    	Route::post('enrollment-status/update-item', 'EnrollmentStatusCrudController@updateItem');
    });


    CRUD::resource('enrollment-applicant', 'EnrollmentApplicantCrudController')->with(function () {
    	Route::get('enrollment-applicant/{id}/enroll', 'EnrollmentApplicantCrudController@enroll');
	    Route::post('enrollment-applicant/{id}/enroll', 'EnrollmentApplicantCrudController@submitEnrollmentForm');
	    Route::get('enrollment-applicant/{id}/print', 'EnrollmentApplicantCrudController@print')->where(['id' => '[0-9]+']);
    });

    CRUD::resource('kiosk-setting', 'KioskSettingCrudController')->with(function () {
        Route::post('kiosk-setting/kiosk-settings/update', 'KioskSettingCrudController@updateKioskSettings');
    	Route::post('kiosk-setting/announcement/update', 'KioskSettingCrudController@updateAnnouncement');
    	Route::post('kiosk-setting/additional-page/update', 'KioskSettingCrudController@updateAdditionalPage');
        Route::post('kiosk-setting/terms-and-condition/update', 'KioskSettingCrudController@updateTermsConditions');
    });


    CRUD::resource('other-program-applicant', 'SelectedOtherProgramApplicantCrudController')->with(function () {
        Route::get('other-program-applicant/{id}/approve', 'SelectedOtherProgramApplicantCrudController@approve')->where(['id' => '[0-9]+']);
    });

    CRUD::resource('other-service-applicant', 'SelectedOtherServiceApplicantCrudController')->with(function () {
        Route::get('other-service-applicant/{id}/approve', 'SelectedOtherServiceApplicantCrudController@approve')->where(['id' => '[0-9]+']);
    });
});

?>