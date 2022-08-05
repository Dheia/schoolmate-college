<?php 

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:Payroll', 'first_time_login', 'verify_schoolyear'],
    // 'namespace'  => 'App\Http\Controllers\Admin',
], function () {

    Route::get('payroll-dashboard', 'PayrollDashBoardController@index');

    CRUD::resource('employee-tax-status', 'EmployeeTaxStatusCrudController');
    CRUD::resource('employee-tax-management', 'EmployeeTaxManagementCrudController');
    CRUD::resource('employment_status','EmploymentStatusCrudController');
    CRUD::resource('employee-salary-management', 'EmployeeSalaryCrudController')->with(function(){
        Route::get('employee-salary-management/print', 'EmployeeSalaryCrudController@print');
        
    });
    CRUD::resource('leave','LeaveCrudController');
    CRUD::resource('employee-mandatory-sss', 'EmployeeMandatorySSSCrudController');
    CRUD::resource('employee-mandatory-phil-health', 'EmployeeMandatoryPhilHealthCrudController');
    CRUD::resource('employee-mandatory-pag-ibig', 'EmployeeMandatoryPagIbigCrudController')->with(function () {
        Route::get('employee-mandatory-pag-ibig/{id}/{action}', 'EmployeeMandatoryPagIbigCrudController@setActive')->where(['action' => '(activate|deactivate)$']);
    });
    CRUD::resource('holiday', 'HolidayCrudController');
    CRUD::resource('attendance-rule', 'EmployeeAttendanceRuleCrudController');
    CRUD::resource('tag-rule', 'TagRuleCrudController');
    CRUD::resource('employee-salary-report', 'EmployeeSalaryReportCrudController')->with(function () {
        Route::post('employee-salary-report/run/report', 'EmployeeSalaryReportCrudController@payroll');
        Route::post('api/employee-salary-report/run/{id}', 'EmployeeSalaryReportCrudController@getEmployeePayroll');
        Route::post('api/employee-salary-report/adjustment', 'EmployeeSalaryReportCrudController@addAdjustment');
        Route::post('api/employee-salary-report/publish/{id}', 'EmployeeSalaryReportCrudController@publishPayroll');
        Route::post('api/employee-salary-report/get-adjustment/{payroll_run_id}/{employee_id}', 'EmployeeSalaryReportCrudController@getAdjustment');
    });

    Route::get('applied-days', 'ScheduleTemplateCrudController@appliedDays');

    // LOANS
    CRUD::resource('loan', 'LoanCrudController');
    CRUD::resource('sss-loan', 'SSSLoanCrudController');
    CRUD::resource('pagibig-loan', 'PagIbigLoanCrudController');
    CRUD::resource('philhealth-loan', 'PhilHealthLoanCrudController');
    CRUD::resource('payroll-run', 'PayrollRunCrudController')->with(function () {
        Route::get('payroll-run/{id}/open', 'PayrollRunCrudController@displayPayroll');
        Route::get('payroll-run/{id}/publish', 'PayrollRunCrudController@publish');
    });
        
});

?>