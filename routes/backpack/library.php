<?php 

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin') . '/library',
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin'), 'school_module:Library', 'first_time_login', 'verify_schoolyear'],
    // 'namespace'  => 'App\Http\Controllers\Admin',
], function () { 

	CRUD::resource('book', 'BookCrudController');
    CRUD::resource('borrowed-book', 'BorrowedBookCrudController');
    CRUD::resource('student-fine', 'BookFineCrudController');
    CRUD::resource('author', 'BookAuthorCrudController');
    CRUD::resource('subject-tag', 'BookSubjectTagCrudController');
    CRUD::resource('category', 'BookCategoryCrudController');
    Route::post('student-fine/paid','BookFineCrudController@paid');
    // CRUD::resource('book-transaction', 'BookTransactionCrudController');
    Route::get('librarian', 'BookTransactionCrudController@library');
    Route::get('librarian-transaction','BookTransactionCrudController@searchBooks');
    Route::get('librarian-mybooks','BookTransactionCrudController@reserveBooks');
    Route::get('librarian-unreturn','BookTransactionCrudController@unreturnBooks');
    Route::post('librarian-release','BookTransactionCrudController@releaseBooks');

    Route::post('book/add-book-copy', 'BookCrudController@addBookCopy');

    CRUD::resource('book-transaction', 'BookTransactionCrudController')->with(function () {
        // Book Transactions Student
        Route::get('book-transaction/api/get/student', 'BookTransactionCrudController@getStudents');
        Route::get('book-transaction/api/get/borrowed-books/{studentnumber}', 'BookTransactionCrudController@getBorrowedBooks');
        Route::get('book-transaction/api/get/book-transactions/{studentnumber}', 'BookTransactionCrudController@getUserTransactions');
        Route::post('book-transaction/api/borrow/book', 'BookTransactionCrudController@borrowBook');
        Route::post('book-transaction/api/return/book', 'BookTransactionCrudController@returnBook');
        Route::post('book-transaction/api/renew/book', 'BookTransactionCrudController@renewBook');
        Route::post('book-transaction/api/paid-fine/book', 'BookTransactionCrudController@paidBookFine');
        Route::get('book-transaction/api/get/books', 'BookTransactionCrudController@getBooks');

        // Book Transactions Employee
        Route::get('book-transaction/api/get/employee', 'BookTransactionCrudController@getEmployees');
        Route::get('book-transaction/api/get/employee-borrowed-books/{employee_id}', 'BookTransactionCrudController@getEmployeeBorrowedBooks');
        Route::get('book-transaction/api/get/employee-book-transactions/{employee_id}', 'BookTransactionCrudController@getEmployeeTransactions');
        Route::post('book-transaction/api/employee-borrow/book', 'BookTransactionCrudController@employeeBorrowBook');
    });

    CRUD::resource('book-report', 'BookReportCrudController')->with(function () {
        Route::post('book-report/generate-report', 'BookReportCrudController@generateReport');
    });

});