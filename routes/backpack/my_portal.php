<?php 

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'first_time_login', 'verify_schoolyear'],
    // 'namespace'  => 'App\Http\Controllers\Admin',
], function () {
    CRUD::resource('attendance-log', 'EmployeeAttendanceLogCrudController');
    CRUD::resource('my-payroll', 'MyPayrollCrudController');
	CRUD::resource('incident-report', 'IncidentReportCrudController');
	CRUD::resource('my-goal', 'GoalCrudController');
});

?>