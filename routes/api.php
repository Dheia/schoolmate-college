<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['middleware' => ['json.response']], function () {

	/******************
		STUDENT API 
	******************/
	Route::group([  // STUDENT NO MIDDLEWARE
		'namespace' => 'API\Student\Auth',
	], function () {
		Route::post('register', 'RegisterController@register');
		Route::post('login', 'LoginController@login');
		Route::post('refresh', 'LoginController@refresh');
	});

	Route::group([
		'namespace' => 'API\Student\Auth',
		'middleware' => ['auth:api'], 
	], function () {

	 	Route::post('logout', 'LoginController@logout');
	 	Route::get('profile', 'StudentController@profile');
	 	Route::get('accounts', 'StudentController@accounts');
	 	Route::get('funds', 'StudentController@funds');
		Route::get('account/{account_id}', 'StudentController@account')->where(['account_id' => '[0-9]+']);
		 
	 	Route::get('attendance-logs', 'StudentController@attendanceLogs');
		Route::get('attendance-logs2', 'StudentController@attendanceLogs2');
		Route::post('tapin', 'StudentController@tapIn');
		Route::post('tapout', 'StudentController@tapOut');
		 
		Route::get('attendance/range/{startdate}/{enddate}', 'StudentController@attendanceLogsRange');
		Route::get('attendance-today', 'StudentController@attendanceToday');
		Route::get('attendance-today2', 'StudentController@attendanceToday2');

		Route::get('next-enrollment', 'StudentController@nextEligibleEnrollment');

	 	Route::post('update-password', 'StudentController@updatePassword');

		Route::get('grade-records', 'StudentController@getGrades');
	    Route::get('grades', 'StudentController@getAllSchoolYearEnrolled');
		Route::post('grades/view', 'StudentController@viewGrades');
		
		Route::get('online-course', 'StudentController@onlineCourse');
		Route::get('lesson-page/{id}', 'StudentController@lessonPage');

		Route::get('incomplete-enrollments', 'StudentController@incompleteEnrollments');
		Route::post('capture-payment', 'StudentController@capturePayment');

		Route::get('join-meeting', 'StudentController@joinMeeting');
		

	 	// QBO Invoice
	 	Route::get('invoice', 'StudentController@invoice');

	 	// Online Payment
	 	Route::post('online-payment/submit-payment', 'OnlinePaymentController@submitPayment');
	});

	/****************************
		STUDENT ONLINE CLASS API 
	*****************************/
	Route::group([
		'namespace' => 'API\Student',
		'middleware' => ['auth:api'], 
	], function () {

		Route::get('online-classes', 'OnlineClassController@onlineClasses');
		Route::get('online-posts', 'OnlineClassController@onlineClassesPosts');
		Route::get('online-assignments', 'OnlineClassController@onlineAssignments');
	});

	

	/******************
		EMPLOYEE API 
	******************/
	Route::group([  // EMPLOYEE NO MIDDLEWARE
		'namespace' => 'API\Employee\Auth',
		'prefix'	=> 'employee',
	], function () {
		Route::post('login', 'LoginController@login');
	});

	Route::group([ // EMPLOYEE WITH MIDDLEWARE
		'namespace' => 'API\Employee\Auth',
		'middleware' => ['auth:employee-api'], 
		'prefix'	=> 'employee',
	], function () {

	 	Route::post('logout', 'LoginController@logout');

	 	Route::get('profile', 'EmployeeController@profile');
	 	Route::get('search/student/{searchTerm}', 'EmployeeController@searchStudent');
	 	Route::get('enrollments/student/{studentnumber}', 'EmployeeController@getEnrollmentsList')->where([ 'studentnumber' => '[0-9]+' ]);
	 	Route::get('enrollment/{enrollment_id}', 'EmployeeController@getEnrollment')->where([ 'studentnumber' => '[0-9]+', 'enrollment_id' => '[0-9]+' ]);
	 	Route::post('enrollment/submit-payment', 'EmployeeController@savePayment');
	 	// Route::get('accounts', 'EmployeeController@accounts');
	 	// Route::get('account/{account_id}', 'EmployeeController@account')->where(['account_id' => '[0-9]+']);
	 	// Route::get('attendance-logs', 'EmployeeController@attendanceLogs');
	 	// Route::get('attendance/range/{startdate}/{enddate}', 'EmployeeController@attendanceLogsRange');
	}); 
	/***********************************/


	/******************
		PARENT API 
	******************/
	Route::group([  // PARENT NO MIDDLEWARE
		'namespace' => 'API\Parent\Auth',
		'prefix'	=> 'parent',
	], function () {
		Route::post('register', 'RegisterController@register');
		Route::post('login', 'LoginController@login');
		Route::post('refresh', 'LoginController@refresh');
	});

	Route::group([ // PARENT WITH MIDDLEWARE
		'namespace' => 'API\Parent\Auth',
		'middleware' => ['auth:parent-api'], 
		'prefix'	=> 'parent',
	], function () {

		Route::post('logout', 'LoginController@logout');

	}); 

	
	/********************
		ANNOUNCEMENTS 
	*********************/
	Route::group([
		'namespace' => 'API',
		'prefix' => 'announcement'
	], function () {

		Route::get('', 'AnnouncementController@announcements');

	});

	Route::group([
		'namespace' => 'API',
		'prefix' => 'settings'
	], function () {

		Route::post('lms-link', 'SettingsController@lmsLink');
		Route::post('', 'SettingsController@getSettings');

	});


	
	Route::group([
		'namespace' => 'API',
		'prefix' => 'enrollment'
	], function () {

		Route::get('enrollment-collections', 'EnrollmentController@enrollmentCollections');
		 

	});

	
});
/***********************************/
