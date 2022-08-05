<?php

use App\Http\Controllers\Parent\AttendanceController;
use App\Http\Controllers\PaynamicV2;

Route::get('parent', function () { return redirect('parent/login'); });
Route::prefix('parent')->group(function() {
    Route::get('register', 'Auth\ParentLoginController@showRegistrationForm')->name('parent.register');  
    Route::post('register', 'Auth\ParentLoginController@register')->name('parent.register.submit');
    Route::get('/', 'Auth\ParentLoginController@showLoginForm')->name('parent.login');
    Route::get('login', 'Auth\ParentLoginController@showLoginForm')->name('parent.login');
    Route::post('login', 'Auth\ParentLoginController@login')->name('parent.login.submit');

    Route::get('/logout', 'Auth\ParentLoginController@logout')->name('parent.logout'); 

});

Auth::guard('parent');

Route::group([
    'prefix' => 'parent',
    'middleware' => ['auth:parent', 'parent_first_time_login'],
    'namespace' => 'Parent'
], function () {
    Route::get('dashboard', 'ParentController@index')->name('parent.dashboard');
    Route::get('add-student', 'ParentController@showAddStudentForm')->name('parent.add-student');
    Route::post('add-student', 'ParentController@addStudent')->name('parent.add-student.submit');
    Route::get('student_pa', 'ParentController@studentInsurance');

    // MY ACCOUNT
    Route::get('my-account','MyAccountController@index');

    // Announcement
    Route::get('announcement', 'MyAccountController@getAnnouncements')->name('parent.announcements');
    Route::get('announcement/{id}', 'MyAccountController@showAnnouncement');

    // Atttendance
    Route::get('system-attendance', [AttendanceController::class, 'systemAttendance'])->name('parent.systemAttendance');
    Route::get('system-attendance/logs', [AttendanceController::class, 'systemAttendanceLogs'])->name('parent.systemAttendanceLogs');

    Route::get('class-attendance', [AttendanceController::class, 'classAttendance'])->name('parent.classAttendance');
    Route::get('class-attendance/logs', [AttendanceController::class, 'classAttendanceLogs'])->name('parent.classAttendanceLogs');

    /*
    |--------------------------------------------------------------------------
    | ONLINE PAYMENT
    |--------------------------------------------------------------------------
    */
    Route::get('online-payment/{enrollment_id}', 'OnlinePaymentController@enrollmentPayment');
    Route::get('online-payment/{enrollment_id}/list', 'OnlinePaymentController@enrollmentPaymentList');
    Route::get('online-payment/{paynamics_payment_id}/information', 'OnlinePaymentController@showInformation');
    Route::post('online-payment', 'OnlinePaymentController@submitPayment')->name('online_payment.parent.submit');

    Route::get('online-payment/paynamics/response/{prefix}/{studentnumber}/{request_id}', [PaynamicV2::class, 'response']);
});

Route::group([
    'prefix' => 'parent',
    'middleware' => ['auth:parent', 'parent_first_time_login', 'student_of_parent'],
    'namespace' => 'Parent'
], function () {

    // Student Information
    Route::get('student-information/{studentnumber}', 'ParentController@showStudentInformation')->name('parent.student-information');

    /*
    |--------------------------------------------------------------------------
    | ENROLLMENT / TUITION / GRADE..
    |--------------------------------------------------------------------------
    */
    Route::get('student-enrollments/{studentnumber}', 'StudentController@showStudentEnrollments')->name('parent.student-enrollment-list');
    Route::get('student-enrollments/{studentnumber}/tuition/{enrollment_id}', 'StudentController@showTuition')->name('parent.student-enrollment.tuition');
    Route::get('student-enrollments/{studentnumber}/grade/{enrollment_id}', 'StudentController@showGrades')->name('parent.student-enrollment.grade');
});

/*
|--------------------------------------------------------------------------
| CHANGE PASSWORD
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'parent',
    'middleware' => ['auth:parent'],
    'namespace' => 'Parent'
], function () {
    Route::get('change-password','MyAccountController@getChangePasswordForm');
    Route::post('change-password/submit','MyAccountController@postsChangePasswordForm');
});

/*
|--------------------------------------------------------------------------
| NOTIFICATION
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'parent',
    'middleware' => ['auth:parent', 'parent_first_time_login'],
    'namespace' => 'Parent'
], function () {
    /*
    |--------------------------------------------------------------------------
    | NOTIFICATION
    |--------------------------------------------------------------------------
    */
    Route::get('api/notification','MyAccountController@unreadNotifications');
});