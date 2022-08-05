<?php
use App\Http\Controllers\Student\AttendanceController;
use App\Http\Controllers\PaynamicV2;

Route::get('login', function () { return redirect('student/login'); });
Route::prefix('student')->group(function() {

    Route::get('/', 'Auth\StudentLoginController@showLoginForm')->name('student.login');
    Route::get('login', 'Auth\StudentLoginController@showLoginForm')->name('student.login');
    Route::post('login', 'Auth\StudentLoginController@login')->name('student.login.submit');

    Route::get('/logout', 'Auth\StudentLoginController@logout')->name('student.logout');    

});

Auth::guard('student');

Route::group([
    'prefix' => 'student',
    'middleware' => ['auth:student', 'student_first_time_login'],
    'namespace' => 'Student'
], function () {

    Route::get('dashboard', 'StudentController@index2')->name('student.dashboard');
    Route::get('dashboard2', 'StudentController@index2')->name('student.dashboardv2');
    Route::get('student_pa', 'StudentController@studentInsurance');
    // MY ACCOUNT
    Route::get('my-account','MyAccountController@index');
    // ONLINE PAYMENT
    Route::get('online-payment','OnlinePaymentController@index');
    Route::get('online-payment/get-balance','OnlinePaymentController@getStudentBalance');
    // Route::post('online-payment/paynamics/notification', 'OnlinePaymentController@notification');
    // Route::get('online-payment/paynamics/notification', 'OnlinePaymentController@notification');

    Route::post('goal','MyAccountController@storeGoal');
    Route::patch('goal/done','MyAccountController@doneGoal');
    Route::delete('goal/delete','MyAccountController@deleteGoal');

    // Announcement
    Route::get('announcement', 'MyAccountController@getAnnouncements')->name('student.announcements');
    Route::get('announcement/{id}', 'MyAccountController@showAnnouncement');
});

/*
|--------------------------------------------------------------------------
| ONLINE PAYMENT
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'student',
    'middleware' => ['auth:student'],
    'namespace' => 'Student'
], function () {


    Route::get('api/payment-method/{id}/get', 'OnlinePaymentController@getPaymentMethod');
    Route::get('online-payment', 'OnlinePaymentController@index');
    Route::get('online-payment/{id}/information', 'OnlinePaymentController@showInformation');
    Route::get('online-payment/{enrollment_id}', 'OnlinePaymentController@enrollmentPayment');
    Route::get('online-payment/{enrollment_id}/list', 'OnlinePaymentController@enrollmentPaymentList');

    Route::get('online-payment/paynamics/response/{prefix}/{studentnumber}/{request_id}', [PaynamicV2::class, 'response']);
    Route::get('online-payment/paynamics/response/{prefix}/{studentnumber}/{request_id}', [PaynamicV2::class, 'response']);

    Route::post('online-payment', 'OnlinePaymentController@submitForm')->name('online_payment.student.submit');
    Route::get('online-payment/execute-payment', 'OnlinePaymentController@executePayment');

    Route::post('online-payment/webhooks', 'OnlinePaymentController@webhooks');

    Route::post('online-payment/paynamics/notification', '\App\Http\Controllers\Admin\PaynamicsPaymentCrudController@notification');
    Route::get('online-payment/paynamics/notification', '\App\Http\Controllers\Admin\PaynamicsPaymentCrudController@notification');

    Route::get('online-payment/paynamics/response', '\App\Http\Controllers\Admin\PaynamicsPaymentCrudController@response');
    // Route::get('paynamics/cancel', '\App\Http\Controllers\Admin\PaynamicsPaymentCrudController@cancel');
    Route::get('online-payment/paynamics/query', '\App\Http\Controllers\Admin\PaynamicsPaymentCrudController@createQuery');
});

/*
|--------------------------------------------------------------------------
| CHANGE PASSWORD
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'student',
    'middleware' => ['auth:student'],
    'namespace' => 'Student'
], function () {
    Route::get('change-password','MyAccountController@getChangePasswordForm');
    Route::post('change-password/submit','MyAccountController@postsChangePasswordForm');
});

Route::group([
    'prefix' => 'student',
    'middleware' => ['auth:student', 'student_first_time_login', 'check_student_disabled'],
    'namespace' => 'Student'
], function () {

    // Route::get('dashboard', 'StudentController@index')->name('student.dashboard');
    // Route::get('account', 'StudentController@account')->name('student.account');
    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE (Turnstile, Class and System Attendance)
    |--------------------------------------------------------------------------
    */
    Route::get('turnstile-attendance', 'StudentController@attendance')->name('student.attendance');
    Route::get('turnstile-attendance/logs', 'StudentController@attendanceLogs'); // API

    Route::get('class-attendance', [AttendanceController::class, 'classAttendance'])->name('student.classAttendance');
    Route::get('class-attendance/logs', [AttendanceController::class, 'classAttendanceLogs'])->name('student.classAttendanceLogs');

    Route::get('system-attendance', [AttendanceController::class, 'systemAttendance'])->name('student.systemAttendance');
    Route::get('system-attendance/logs', [AttendanceController::class, 'systemAttendanceLogs'])->name('student.systemAttendanceLogs');
    /*
    |--------------------------------------------------------------------------
    | GRADES
    |--------------------------------------------------------------------------
    */
    Route::get('grades', 'GradeController@getAllSchoolYearEnrolled');
    Route::get('grades/view', 'GradeController@viewGrades');
    Route::get('enrollments/{enrollment_id}/grade/', 'GradeController@viewGrades')->name('student.enrollment.tuition');
    /*
    |--------------------------------------------------------------------------
    | ENROLLMENT / TUITION
    |--------------------------------------------------------------------------
    */
    Route::get('enrollments', 'EnrollmentTuitionController@enrollments')->name('student.enrollments');
    Route::get('enrollments/tuition/{enrollment_id}', 'EnrollmentTuitionController@viewTuition')->name('student.enrollment.tuition');
    Route::get('api/all-tuition-fee-data/{studentnumber}', 'EnrollmentTuitionController@allTuitionFeeData');
    //  API TUITION TABLE
    Route::get('student-accounts/api/all-tuition-fee-data/{enrollment_id}', 'EnrollmentTuitionController@allTuitionFeeData')->where('enrollment_id', '[0-9]+');
    Route::get('api/payment-history/receipt-partials-layout', 'EnrollmentTuitionController@receiptLayouts');
    Route::get('api/get/payment-method', 'EnrollmentTuitionController@getPaymentMethodList');
    
    /*
    |--------------------------------------------------------------------------
    | LIBRARY
    |--------------------------------------------------------------------------
    */
    Route::get('library','LibraryTransactionController@library');
    Route::resource('library-transaction','LibraryTransactionController');
    Route::get('library-mybooks','LibraryTransactionController@mybooks');
    Route::get('library-cancel','LibraryTransactionController@cancel');
    Route::get('library-unreturn','LibraryTransactionController@unreturn');

    /*
    |--------------------------------------------------------------------------
    | MEETING
    |--------------------------------------------------------------------------
    */
    Route::get('meeting','MeetingController@index');
    Route::get('meeting/video/{code}','MeetingController@joinConference');
});

/*
|--------------------------------------------------------------------------
| ONLINE CLASS
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'student',
    'middleware' => ['auth:student', 'student_first_time_login', 'check_if_student_is_enrolled', 'check_student_disabled'],
    'namespace' => 'Student'
], function () {
    /*
    |--------------------------------------------------------------------------
    | ONLINE CLASS
    |--------------------------------------------------------------------------
    */
    Route::resource('online-class','OnlineClassController');
    Route::get('online-class/api/get/classes','OnlineClassController@searchClasses');
    Route::get('online-class/{code}','OnlineClassController@showClassPosts');
    Route::get('online-class/course/{code}','OnlineClassController@showClassCourse');
    Route::get('online-class/video/{code}','OnlineClassController@joinConference');
    Route::get('online-class/{code}/course/{module_id}','OnlineClassController@showClassModule')
            ->where(['module_id' => '[0-9]+']);
    Route::get('online-class/{code}/student-list','OnlineClassController@showStudentList');
    Route::get('online-class/{code}/recordings','OnlineClassController@showClassRecordings');

    // COURSE MODULE
    Route::get('online-class-module/{code}','OnlineClassController@showClassModules');

    // COURSE TOPIC
    Route::get('online-class-topic/{code}/{module_id}','OnlineClassController@showModuleTopics')->where(['module_id' => '[0-9]+']);
    Route::get('online-class-topic/{code}/{module_id}/{topic_id}','OnlineClassController@showTopic')
            ->where(['module_id' => '[0-9]+'])->where(['topic_id' => '[0-9]+']);
    Route::post('online-class-topic/{code}/{module_id}/{topic_id}/submit-progress','OnlineClassController@submitProgress')
            ->where(['module_id' => '[0-9]+'])->where(['topic_id' => '[0-9]+']);
    Route::get('online-class-topic/{code}/{module_id}/{topic_id}/{page_id}','OnlineClassController@showTopicPage')
            ->where(['module_id' => '[0-9]+'])->where(['topic_id' => '[0-9]+'])->where(['page_id' => '[0-9]+']);

    /*
    |--------------------------------------------------------------------------
    | CLASS POST
    |--------------------------------------------------------------------------
    */
    CRUD::resource('online-post','OnlineClassPostController');
    Route::get('online-post/{code}','OnlineClassController@showClassPosts');

    // API Get Class Post
    Route::get('online-post/api/get/class-posts', 'OnlineClassPostController@getPosts');
    Route::get('online-post/api/get/class-posts/{post_id}', 'OnlineClassPostController@getPost');

    // Like && Comment
    Route::post('online-post/{post_id}/like', 'OnlineClassPostController@likePost');
    Route::post('online-post/{post_id}/comment', 'OnlineClassPostController@storeComment');
    Route::post('online-post/{post_id}/comment/{comment_id}/delete', 'OnlineClassPostController@deleteComment');

    /*
    |--------------------------------------------------------------------------
    | ASSIGNMENT
    |--------------------------------------------------------------------------
    */
    CRUD::resource('online-class-assignments','AssignmentController');
    Route::get('online-class/{class_code}/assignments', 'AssignmentController@getClassAssignments');
    Route::get('online-class/assignments', 'AssignmentController@feed');

    /*
    |--------------------------------------------------------------------------
    | QUIZ
    |--------------------------------------------------------------------------
    */
    Route::get('online-class-quizzes','OnlineClassQuizController@showQuizzes');
    Route::get('online-class/{class_code}/quizzes', 'OnlineClassQuizController@showClassQuizzes');
    Route::post('online-class-quizzes/{class_quiz_id}/start', 'OnlineClassQuizController@startQuiz');
    // Route::post('online-class-quizzes/{class_quiz_id}', 'OnlineClassQuizController@submitQuiz');
    Route::get('online-class-quizzes/question', 'OnlineClassQuizController@getQuestions');
    Route::post('online-class-quizzes/submit', 'OnlineClassQuizController@submitQuiz');
    Route::get('online-class-quizzes/show_quiz_result/{class_quiz_id}','OnlineClassQuizController@showResult');
    Route::get('online-class-quizzes/getResult','OnlineClassQuizController@showStudentResult');

    /*
    |--------------------------------------------------------------------------
    | ONLINE CLASS ATTENDANCE
    |--------------------------------------------------------------------------
    */
    Route::post('online-class/qr-code/{qrcode}','OnlineClassController@submitClassAttendance');
    Route::post('online-class/submit-attendance/{class_code}','OnlineClassController@tapClassAttendance');
});

Route::group([
    'prefix' => 'student',
    'middleware' => ['auth:student', 'student_first_time_login', 'check_student_disabled'],
    'namespace' => 'Student'
], function () {
    /*
    |--------------------------------------------------------------------------
    | ENROLLMENT / ENROLL
    |--------------------------------------------------------------------------
    */
    Route::get('enrollments/{term_type}','EnrollmentController@showEnrollment');
    Route::post('enrollments/{term_type}','EnrollmentController@submitEnrollment');

    Route::post('enrollments/enroll/other-program', 'EnrollmentController@enrollOtherProgram');
    Route::post('enrollments/enroll/other-service', 'EnrollmentController@enrollOtherService');
});


/*
|--------------------------------------------------------------------------
| NOTIFICATION
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'student',
    'middleware' => ['auth:student', 'student_first_time_login', 'check_student_disabled'],
    // 'namespace' => 'Student'
], function () {
    /*
    |--------------------------------------------------------------------------
    | NOTIFICATION
    |--------------------------------------------------------------------------
    */
    Route::get('api/notification','API\Student\StudentV2Controller@unreadNotifications');
});