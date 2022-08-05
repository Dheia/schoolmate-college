<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/zoom/meeting/create', 'ZoomMeetingController@createMeeting');
Route::get('/zoom/user/list', 'ZoomMeetingController@createUser');
Route::get('/zoom/user/create', 'ZoomMeetingController@createUser');
Route::get('/zoom/create', 'ZoomMeetingController@create');
Route::get('/zoom/list/users', 'ZoomMeetingController@listUsers');

Route::get('/zoom/webhooks', 'ZoomMeetingController@webhooks');
Route::post('/zoom/webhooks', 'ZoomMeetingController@webhooks');


Route::get('teacher-online-class/video_conference', 'Admin\OnlineClassCrudController@videoConference');

Route::get('online-attendance', 'OnlineAttendanceController@index');
Route::post('online-attendance', 'OnlineAttendanceController@postAttendance');


Route::get('get-redis', 'Admin\RfidCrudController@getRedisData');
Route::get('admin/holiday/search', 'App\HttpControllers\Admin\HolidayCrudController@search');
Route::get('/', function () { return redirect('student/login'); });
Route::get('unauthorized', function () { return view('unsubscription'); });
Route::get('grade-encoding', function(){ return view('grade_encode'); });

Route::get('online-enrollment', function () { return redirect('kiosk'); });

Route::get('trigger', 'TriggerController@trigger')->name('trigger');
// Route::get('rfidlogs', 'Admin\RfidCrudController@rfidlogs');
Route::get('rfidlogs', function () {
    return redirect('elogs');
});
Route::get('elogs', 'Admin\EmployeeLogCrudController@displayLogs');


Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'Admin',
], function () { // custom admin routes

    Route::get('receipt-template', function () { return view('studentAccount.receipt'); });
    Route::resource('payroll', '\App\Http\Controllers\PayrollController');

});

Route::get('rfidlogsingle', function () {
    return view('rfidlogsingle');
});


Route::get('library', 'LibraryController@index');
Route::get('library/get/books', 'LibraryController@getBooks');

Route::post('smstag', 'SmsApiController@smspost');
Route::get('smstag', 'SmsApiController@smstag');

Route::get('asset-inventory/{id}/show', 'Admin\AssetInventoryCrudController@showQrCode');
Route::get('locker-inventory/{id}/show', 'Admin\LockerInventoryCrudController@showQrCode')->where('id', '[0-9]+');

Route::group([
    'prefix'     => 'admin',
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:System', 'first_time_login'],
    // 'namespace'  => 'App\Http\Controllers\Admin',
], function () {
    Route::get('/file/storage/{path}', function ($path) {
        dd($path);
        return 'Hello World';
    });
});



Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['admin'],
    'namespace' => 'Admin',
], function () {

    // General Used For QuickBooks
    Route::get('quickbooks/bind/{name}/services', '\App\Http\Controllers\QuickBooks\QuickBooksOnline@bindParentServices');

});


// Reports

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['admin'],
], function () {
    
    // Reports
    Route::get('reports/main-filter', 'ReportController@mainFilter')->name('reports.main-filter');
    Route::get('reports/enrollment-list', 'ReportController@enrollmentList')->name('reports.enrollment-list');

    // Filters
    Route::get('reports/filter-department', 'ReportController@filtersDepartment')->name('reports.filters.department');
    Route::get('reports/filter-track', 'ReportController@filtersTrack')->name('reports.filters.track');

});

use App\Http\Controllers\API\SettingsController;

Route::group([
    'prefix' => 'api'
], function () {
    Route::get('settings/school-information', [SettingsController::class, 'getSchoolInformation'])->name('setting.school_information');
});

// use App\Models\Enrollment;
// use Illuminate\Support\Facades\Storage;
// Route::get('enrollments-accounts', function () {
//     $enrollments    =   Enrollment::get()->map(function ($enrollment) {
//                             return collect($enrollment)
//                                 ->only(['id', 'student_id', 'studentnumber', 'full_name', 'firstname', 'middlename', 'lastname', 'is_applicant', 'date_enrolled', 'deleted_at', 'school_year_id', 'school_year_name', 'department_id', 'department_name', 'term_type', 'level_id', 'level_name', 'track_id', 'track_name', 'gender', 'enrollment_status', 'lrn', 'remaining_balance'])
//                                 ->all();
//                         });

//     // dd($enrollments);
//     Storage::disk('local')->put('enrollment_accounts.json', json_encode($enrollments));
//     return response()->json($enrollments);
// });