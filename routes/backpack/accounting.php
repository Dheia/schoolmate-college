<?php 

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:Accounting', 'first_time_login', 'verify_schoolyear'],
    // 'namespace'  => 'App\Http\Controllers\Admin',
], function () {

	CRUD::resource('student-account', 'StudentAccountCrudController')->with(function () {
        // Route::get('student-accounts/create', 'PaymentMethodCrudController');
        Route::get('api/get/student', 'StudentAccountCrudController@getStudents');
        Route::get('api/get/tuitions', 'StudentAccountCrudController@getTuitions');
        Route::get('api/get/student-account/{studentnumber}', 'StudentAccountCrudController@getStudentAccount')->where('studentnumber', '[0-9]+');
        Route::get('api/get/enrollments-year/{studentnumber}', 'StudentAccountCrudController@getEnrollmentsYear')->where('studentnumber', '[0-9]+');
        Route::get('api/student-account', 'StudentAccountCrudController@studentAccount');
        Route::get('student-account/{enrollment_id}', 'StudentAccountCrudController@viewTuition')->where('enrollment_id', '[0-9]+');
        Route::post('api/add-payment/add', 'StudentAccountCrudController@savePayment');
        Route::post('api/selected-other-program/add', 'StudentAccountCrudController@addOtherProgram');
        Route::post('api/selected-other-service/add', 'StudentAccountCrudController@addOtherService');
        Route::post('api/additional-fee/add', 'StudentAccountCrudController@addAdditionalFee');
        Route::post('api/special-discount/add', 'StudentAccountCrudController@addSpecialDiscount');
        Route::post('api/discrepancy/add', 'StudentAccountCrudController@addDiscrepancy');

        // INVOICE STUDENT ACCOUNTS payments, special discount, other programs and other services
        Route::get('api/student-account/invoice/{id}/payment', 'StudentAccountCrudController@addInvoicePayment')->where('id', '[0-9]+');
        Route::get('api/student-account/invoice/{id}/payment/delete', 'StudentAccountCrudController@deleteInvoicePayment')->where('id', '[0-9]+');

        Route::get('api/student-account/invoice/{id}/special-discount', 'StudentAccountCrudController@addInvoiceSpecialDiscount')->where('id', '[0-9]+');
        Route::get('api/student-account/invoice/{id}/special-discount/delete', 'StudentAccountCrudController@deleteInvoiceSpecialDiscount')->where('id', '[0-9]+');

        Route::get('api/student-account/invoice/{id}/discrepancy', 'StudentAccountCrudController@addInvoiceDiscrepancy')->where('id', '[0-9]+');
        Route::get('api/student-account/invoice/{id}/discrepancy/delete', 'StudentAccountCrudController@deleteInvoiceDiscrepancy')->where('id', '[0-9]+');

        Route::get('api/student-account/invoice/{id}/other-program', 'StudentAccountCrudController@addInvoiceOtherProgram')->where('id', '[0-9]+');
        Route::get('api/student-account/invoice/{id}/other-program/delete', 'StudentAccountCrudController@deleteInvoiceOtherProgram')->where('id', '[0-9]+');

        Route::get('api/student-account/invoice/{id}/other-service', 'StudentAccountCrudController@addInvoiceOtherService')->where('id', '[0-9]+');
        Route::get('api/student-account/invoice/{id}/other-service/delete', 'StudentAccountCrudController@deleteInvoiceOtherService')->where('id', '[0-9]+');

        Route::get('api/student-account/invoice/{id}/additional-fee', 'StudentAccountCrudController@addInvoiceAdditionalFee')->where('id', '[0-9]+');
        Route::get('api/student-account/invoice/{id}/additional-fee/delete', 'StudentAccountCrudController@deleteInvoiceAdditionalFee')->where('id', '[0-9]+');
        
        //  API TUITION TABLE
        Route::get('student-accounts/api/all-tuition-fee-data/{enrollment_id}', 'StudentAccountCrudController@allTuitionFeeData')->where('enrollment_id', '[0-9]+');
        // PRINT RECEIPT
        Route::get('student-account/receipt/{id}/print', 'StudentAccountCrudController@printReceipt')->where('id', '[0-9]+');
        // SEND SOA
        Route::post('student-account/send-soa', 'StudentAccountCrudController@sendSoa');
    });

    CRUD::resource('online-payment', 'OnlinePaymentCrudController');
  
    CRUD::resource('payment-method', 'PaymentMethodCrudController')->with(function () {
        Route::get('api/get/payment-method', 'PaymentMethodCrudController@getPaymentMethodList');
    });

    CRUD::resource('payment-method-category', 'PaymentMethodCategoryCrudController');

    CRUD::resource('commitment-payment', 'CommitmentPaymentCrudController')->with(function () {
        Route::get('commitment-payment/{action}/{id}', 'CommitmentPaymentCrudController@setActive')
            ->where([ 'action' => '(activate|deactivate)$', 'id' => '[0-9]+', ]);
    });
    // Route::get('tuition/search', 'TuitionCrudController@search');
	CRUD::resource('tuition', 'TuitionCrudController')->with(function () {
	    Route::get('tuition/{action}', 'TuitionCrudController@setActive')
	        ->where([
	                    'action'            => '(activate|deactivate)$',
	                    'tuition_id'        => '[0-9]+', 
	                    'schoolyear_id'     => '[0-9]+', 
	                    'department_id'     => '[0-9]+', 
	                    'grade_level_id'    => '[0-9]+',
	                    'track_id'          => '[0-9]+',
	                ]);
        Route::get('tuition/{id}/clone', 'TuitionCrudController@clone');
        Route::post('tuition/{id}/clone', 'TuitionCrudController@store');
	});

    CRUD::resource('other-programs', 'OtherProgramCrudController')->with(function () {
        // Route::get('api/get/other-programs', 'OtherProgramCrudController@getOtherPrograms');
        Route::get('other-programs/{id}/qbo-connect', 'OtherProgramCrudController@QBOConnect')->where('id', '[0-9]+');
        Route::get('other-programs/search', 'OtherProgramCrudController@search');
    });

    CRUD::resource('other-service', 'OtherServiceCrudController')->with(function () {
        Route::get('other-service/{id}/qbo-connect', 'OtherServiceCrudController@QBOConnect');
    });

    CRUD::resource('discrepancy', 'DiscrepancyCrudController');

    /*
    |--------------------------------------------------------------------------
    | PAYMENT HISTORY (PAYMENT REVIEW)
    |--------------------------------------------------------------------------
    */
    CRUD::resource('payment-history', 'PaymentHistoryCrudController')->with(function () {
        Route::get('payment-history/{id}/set-invoice', 'PaymentHistoryCrudController@setInvoice');
        Route::get('payment-history/search', 'PaymentHistoryCrudController@search');
        Route::get('api/payment-history/receipt-partials-layout', 'PaymentHistoryCrudController@receiptLayouts');
    });

    /*
    |--------------------------------------------------------------------------
    | SALES REPORT
    |--------------------------------------------------------------------------
    */
    Route::get('sales-report', 'Reports\SalesReportController@index');
    Route::get('sales-report/{period}/payment', 'Reports\SalesReportController@reportlogs');
    Route::post('sales-report/download', 'Reports\SalesReportController@generateReport');

    /*
    |--------------------------------------------------------------------------
    | STUDENT BALANCES REPORT
    |--------------------------------------------------------------------------
    */
    Route::get('students-balances-report', 'Reports\StudentBalancesReportController@index');
    Route::post('students-balances-report/download', 'Reports\StudentBalancesReportController@download');
    Route::get('students-balances-report/api/{department_id}/levels', 'Reports\StudentBalancesReportController@getLevels');
    Route::get('students-balances-report/api/enrollment-list', 'Reports\StudentBalancesReportController@getEnrollmentList');

    // CRUD::resource('cash-account', 'CashAccountCrudController')->with(function () {
    //     Route::get('cash-account/cleared-balance/{id}', 'CashAccountCrudController@clearedBalance');
    //     Route::get('api/cash-account/{id}', 'CashAccountCrudController@findCashAccount')->where('id', '[0-9]+');
    // });

    // // Payment History


    // // Payment Method

    // // CRUD::resource('payment-method', 'PaymentMethodCrudController')->with(function () {
    // //     Route::get('api/get/payment-method', 'PaymentMethodCrudController@getPaymentMethodList');
    // // });

    // // Miscelleneous Management

    // CRUD::resource('misc', 'MiscCrudController');
    // Route::get('misc/{students}/print', 'MiscPrintController@index');

    // // Chart of Accounts

    // CRUD::resource('chart-account', 'ChartAccountCrudController');

    // CRUD::resource('profits-loss-statement', 'ProfitsLossStatementCrudController')->with(function () {
    //     Route::get('api/profits-loss-statement/{id}/delete', 'ProfitsLossStatementCrudController@deleteTree')->where('id', '[0-9]+');
    // });
    // CRUD::resource('receive-money', 'ReceiveMoneyCrudController');
    // CRUD::resource('spend-money', 'SpendMoneyCrudController');
    // CRUD::resource('transfer-money', 'TransferMoneyCrudController');
    // CRUD::resource('sale', 'SaleCrudController');
    
    // BPI
    // Route::get('api/bpi/authorize', function () {
        // $headers = [
        //     'accept: application/json',
        //     'content-type: application/json',
        //     'x-ibm-client-id: 0f44c3ca-2580-4c03-87ff-a4edc8e8206c',
        //     'x-ibm-client-secret: N7uD4xD6cO1vD3yV5eS6eO6aL6jD2eK8pN5vL8aC7eM2wD5eC3',
        //     'authorization: Bearer ' . request()->access_token,
        //     'x-partner-id: 5dff2cdf-ef15-48fb-a87b-375ebff415bb'
        // ];


        // $fields = [
        //     "senderRefId" => "0032",
        //     "tranRequestDate" => "2017-10-10T12:11:50.333",
        //     "amount" => [
        //         "currency" => "PHP",
        //         "value" => "1000"
        //     ],
        //     "remarks" => "Payment remarks",
        //     "particulars" => "Payment particulars",
        //     "info" => [
        //         [
        //           "index" => 1,
        //           "name" => "Payor",
        //           "value" => "Juan Dela Cruz"
        //         ],
        //         [
        //           "index" => 2,
        //           "name" => "InvoiceNo",
        //           "value" => "12345"
        //         ]
        //     ]
        // ];

        // $curl = App\Http\Controllers\RestCurl::post('https://api-uat.unionbankph.com/partners/sb/merchants/v4/payments/single', $headers, json_encode($fields));
        // dd($curl);
    // });
});

?>