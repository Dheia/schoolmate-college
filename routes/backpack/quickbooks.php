<?php


// QUICKBOOKS GROUP
Route::group([
    'prefix' => 'admin/quickbooks',
    'middleware' => ['admin', 'school_module:Accounting'],
    'namespace' => '\App\Http\Controllers\QuickBooks'
], function () {

//     // QBO AUTHORIZING ROUTES
    Route::get('authorize', '\App\Http\Controllers\QuickBooks\QuickBooksOnline@QBOAuthorize');
    Route::get('token', '\App\Http\Controllers\QuickBooks\QuickBooks@token'); // Redirect uri callback

//     // QBO REGULAR ROUTES
//     Route::resource('all-sales', 'SalesController');
//     Route::resource('customer', 'CustomerController');
//     Route::resource('employee', 'EmployeeController');
//     Route::resource('reports', 'ReportController');
//     Route::resource('payment', 'PaymentController');
//     Route::resource('products-and-services', 'ProductsAndServicesController');
//     Route::resource('payment-method', 'PaymentMethodController');
//     Route::resource('chart-of-accounts', 'ChartOfAccountController');
//     Route::resource('vendor', 'VendorController');
    Route::resource('term', 'TermController');
    Route::resource('query', 'QueryController');

    
//     // DEV
//     Route::get('invoice/update/all', 'QueryController@invoiceUpdate');
//     /**
//      * ENPLOYEES RESOURCES
//      */
//     // Route::get('employee/{id}/connect', 'QuickBooks@employeeCreate')->name('employee.connect');
//     // Route::get('employee/create', 'QuickBooks@employeeCreate')->name('employees.create');
//     // Route::get('employee/create/submit', 'QuickBooks@employeeCreateSubmit')->name('employees.createSubmit');
//     // Route::get('employee/edit/{id}', 'QuickBooks@employeeEdit')->name('employees.edit');
//     // Route::get('employee/delete/{id}', 'QuickBooks@employeeDelete')->name('employees.delete');


//     // END OF EMPLOYEES RESOURCES

//     // Route::get('quickbooks/chart-of-accounts', 'QuickBooks@chartOfAccounts')->name('chartOfAccounts.get');


//     // Quickbook Reports

//     Route::get('reporting', 'ReportController@select');
//     Route::post('reporting', 'ReportController@reports')->name('post.report');
    

});