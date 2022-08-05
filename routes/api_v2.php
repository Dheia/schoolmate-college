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

    /*
	|--------------------------------------------------------------------------
	| AUTH
	|--------------------------------------------------------------------------
	*/
	Route::group([
		'namespace' => 'API\Student',
		'middleware' => ['auth:api'],
	], function () {

		Route::get('get-csrf-token', 'StudentV2Controller@getCSRFToken');
		Route::get('profile', 'StudentV2Controller@profile');
		Route::get('notification', 'StudentV2Controller@notifications');
		Route::get('unread-notification', 'StudentV2Controller@unreadNotifications');

		Route::get('notification/{notification_id}/read', 'StudentV2Controller@readNotification');
		Route::get('notification/{notification_id}/unread', 'StudentV2Controller@unreadNotification');
	});

	/*
	|--------------------------------------------------------------------------
	| ENROLLMENT
	|--------------------------------------------------------------------------
	*/
	Route::group([
		'namespace' => 'API\Student',
		'middleware' => ['auth:api'],
	], function () {
		Route::get('enrollments', 'StudentV2Controller@enrollmentList');
		Route::get('enrollments/{enrollment_id}', 'StudentV2Controller@enrollment');
		// Enrollment Tuition
		Route::get('enrollments/{enrollment_id}/tuition', 'StudentV2Controller@enrollmentTuition');
		// Payment Histories
		Route::get('enrollments/{enrollment_id}/payment-histories', 'StudentV2Controller@paymentHistories');
	});

    /*
	|--------------------------------------------------------------------------
	| ONLINE CLASS
	|--------------------------------------------------------------------------
	*/
	Route::group([
		'namespace' => 'API\Student',
		'middleware' => ['auth:api'], 
	], function () {
		Route::get('online-classes', 'OnlineClassController@onlineClasses');
		Route::get('online-posts', 'OnlineClassController@onlineClassesPosts');
		Route::get('online-assignments', 'OnlineClassController@onlineAssignments');

        Route::get('online-classes/{class_code}', 'OnlineClassController@onlineClass');
        Route::get('online-posts/{class_code}', 'OnlineClassController@onlineClassPosts');
		Route::get('online-post/{post_id}', 'OnlineClassController@onlinePost');
        Route::get('online-assignments/{class_code}', 'OnlineClassController@onlineClassAssigments');
        Route::get('online-class/{class_code}/student-list', 'OnlineClassController@studentList')->middleware('check_student_class');

        Route::get('online-course/{class_code}', 'OnlineClassController@onlineClassCourse');
        Route::get('online-course/{class_code}/modules', 'OnlineClassController@onlineCourseModules');
        Route::get('online-course/{class_code}/modules/{module_id}/topics', 'OnlineClassController@onlineCourseTopics');
        Route::get('online-course/{class_code}/modules/{module_id}/topics/{topic_id}/pages', 'OnlineClassController@onlineCourseTopicPages');
	});

	/*
	|--------------------------------------------------------------------------
	| ONLINE CLASS POSTS
	|--------------------------------------------------------------------------
	*/
	Route::group([
		'namespace' => 'API\Student',
		'middleware' => ['auth:api'], 
	], function () {
		Route::get('online-posts', 'OnlineClassController@onlineClassesPosts');
        Route::get('online-posts/{class_code}', 'OnlineClassController@onlineClassPosts');

		/** 
		 * Like / Unlike Post
		 */
		Route::post('online-class/post/{post_id}/like', 'OnlineClassController@likePost');

		/** 
		 * Store / Delete Comment To Post
		 */
		Route::post('online-class/post/{post_id}/comment', 'OnlineClassController@storeComment');
		Route::post('online-class/post/{post_id}/comment/{comment_id}/delete', 'OnlineClassController@deleteComment');
	});

    /*
	|--------------------------------------------------------------------------
	| STUDENT ATTENDANCE
	|--------------------------------------------------------------------------
	*/
	Route::group([
		'namespace' => 'API\Student',
		'middleware' => ['auth:api'],
	], function () {

		/* Class Attendance */
		Route::get('class-attendance', 'AttendanceController@classAttendance');
		Route::post('class-attendance/{class_code}', 'AttendanceController@tapClassAttendance');
		Route::get('class-attendance/{class_code}', 'AttendanceController@singleClassAttendance');

		/* System Attendance */
		Route::get('system-attendance', 'AttendanceController@systemAttendance');
		Route::post('system-attendance', 'AttendanceController@tapSystemAttendance');
        
	});

    /*
	|--------------------------------------------------------------------------
	| ANNOUNCEMENTS
	|--------------------------------------------------------------------------
	*/
	Route::group([
		'namespace' => 'API',
		'middleware' => ['auth:api'],
		'prefix' => 'announcement'
	], function () {

		Route::get('', 'AnnouncementController@announcements');
		Route::get('/{announcement_id}', 'AnnouncementController@announcement');

	});

	/*
	|--------------------------------------------------------------------------
	| ONLINE PAYMENT
	|--------------------------------------------------------------------------
	*/
	Route::group([
		'namespace'  => 'API\Student',
		'middleware' => ['auth:api'],
	], function () {

		Route::get('online-payment/payment-categories', 'OnlinePaymentController@getPaymentCategories');
		Route::get('online-payment/payment-method/{id}', 'OnlinePaymentController@getPaymentMethod');
		Route::get('online-payment/{request_id}/information', 'OnlinePaymentController@getInformation');
		Route::post('online-payment/submit', 'OnlinePaymentController@submitPayment');

	});
	
});
/***********************************/
