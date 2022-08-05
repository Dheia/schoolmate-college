<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\BookTransactionRequest as StoreRequest;
use App\Http\Requests\BookTransactionRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use Illuminate\Http\Request;

use App\Models\BookTransaction;
use App\Models\Student;
use App\Models\Employee;
use App\Models\Book;

use Carbon\Carbon;
use Config;

/**
 * Class BookTransactionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class BookTransactionCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\BookTransaction');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/library/book-transaction');
        $this->crud->setEntityNameStrings('booktransaction', 'Book Transactions');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in BookTransactionRequest
        $this->crud->setListView('library.transactions.list');
        $this->crud->setCreateView('library.transactions.list');

        $this->crud->removeAllButtons();

        $this->crud->addField([
            'label' => '',
            'type' => 'library.transactions.search_transaction_student_or_employee',
            'name' => 'search_student_or_employee'
        ]);
         $student_or_employee = isset($_GET['searchFor']) ? $_GET['searchFor'] : 'student';
        in_array($student_or_employee, ['student', 'employee']) ? '' : $student_or_employee = 'student';

        // dd($student_or_employee);
        $this->crud->addField([
            'name' => 'search',
            'type' => 'library.transactions.search'. title_case($student_or_employee),
            'label' => 'Search',
            'attributes' => [
                'id' => 'search',
                'placeholder' => 'Search For Name or ID Number (ex. 1100224)',
            ]
        ])->beforeField('student_no');

        $this->crud->addField([
            'name' => 'student_no',
            'type' => 'hidden',
            'label' => 'Student No.',
            'attributes' => [
                'id' => 'student_number'
            ],
            'wrapperAttributes' => [
                'class' => 'col-md-4'
            ]
        ]);

    }
    public function getStudents (Request $request) 
    {   
         $students = Student::where('studentnumber', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('firstname', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('middlename', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('lastname', 'LIKE', '%' . $request->search . '%')
                            ->with('schoolYear')
                            ->with('yearManagement')
                            ->paginate(5);
        $students->setPath(url()->current());
        return response()->json($students);
    }
    public function getEmployees (Request $request) 
    {  
         $employees = Employee::where('employee_id', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('firstname', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('middlename', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('lastname', 'LIKE', '%' . $request->search . '%')
                            ->paginate(5);
        $employees->setPath(url()->current());
        return response()->json($employees);
    }

    public function getBooks (Request $request) 
    {   
        $search = $request->search;
        $borrowedBooks      =   BookTransaction::where('date_returned', null)->get();
        $borrowedBooksIds   =   collect($borrowedBooks)->pluck('book_id')->toArray();
        $books              =   Book::whereNotIn('id', $borrowedBooksIds)
                                    ->where('deleted_at', null)
                                    ->where(function ($query) use ($search) {
                                            $query
                                                ->where('accession_number', 'LIKE', '%' . $search . '%')
                                                ->orWhere('title', 'LIKE', '%' . $search . '%');
                                    })
                                    ->paginate(5);
        $books->setPath(url()->current());
        return response()->json($books);
    }

    public function getBorrowedBooks($studentnumber){
        $UserBorrowedBooks      =   BookTransaction::where('studentnumber', $studentnumber)
                                    ->where('date_returned', null)
                                    ->with('book')
                                    ->get();
                                    // dd($UserBorrowedBooks);
         return response()->json($UserBorrowedBooks);
    }
     public function getEmployeeBorrowedBooks($employee_id){
        // dd($employee_id);
        $UserBorrowedBooks      =   BookTransaction::where('employee_id', $employee_id)
                                    ->where('date_returned', null)
                                    ->with('book')
                                    ->get();
                                    // dd($UserBorrowedBooks);
         return response()->json($UserBorrowedBooks);
    }

    public function getUserTransactions($studentnumber){
        $UserTransactions      =   BookTransaction::where('studentnumber', $studentnumber)
                                    ->where('date_returned', '!=', null)
                                    ->where('deleted_at', null)
                                    ->orderBy('date_returned', 'DESC')
                                    ->get();
         return response()->json($UserTransactions);
    }
    public function getEmployeeTransactions($employee_id){
        $EmployeeTransactions      =   BookTransaction::where('employee_id', $employee_id)
                                    ->where('date_returned', '!=', null)
                                    ->where('deleted_at', null)
                                    ->orderBy('date_returned', 'DESC')
                                    ->get();
         return response()->json($EmployeeTransactions);
    }


    public function borrowBook(Request $request) 
    {
        $borrowBook                 =    new BookTransaction();
        $borrowBook->studentnumber  =    $request->borrow_student_id;
        $borrowBook->book_id        =    $request->borrow_book_id;
        $borrowBook->date_borrowed  =    Carbon::now();
        $borrowBook->due_date       =    Carbon::now()->addDay($request->borrow_days);

        if($borrowBook->save()){
            return 1; 
        }else{
            return 0; 
        }
    }
    public function employeeBorrowBook(Request $request) 
    {
        $borrowBook                 =    new BookTransaction();
        $borrowBook->employee_id  =    $request->borrow_employee_id;
        $borrowBook->book_id        =    $request->borrow_book_id;
        $borrowBook->date_borrowed  =    Carbon::now();
        $borrowBook->due_date       =    Carbon::now()->addDay($request->borrow_days);

        if($borrowBook->save()){
            return 1; 
        }else{
            return 0; 
        }
    }
    public function returnBook(Request $request) 
    {
        $borrowBook                 =   BookTransaction::where('id', $request->return_book_id)->first();
        $borrowBook->is_returned    =   '1';
        $borrowBook->date_returned  =   Carbon::now();
        $borrowBook->fine           =   $request->return_book_fine;
        if($borrowBook->update()){
            return 1; 
        }else{
            return 0; 
        }
    }
    public function paidBookFine(Request $request){
        $borrowBook             =   BookTransaction::where('id', $request->paid_transaction_id)->first();
        $borrowBook->paid_date  =   Carbon::now();
        $borrowBook->paid       =   '1';
        $borrowBook->fine       =   $request->fine;
        if($borrowBook->date_returned == null){
            $borrowBook->date_returned     =   Carbon::now();
            $borrowBook->is_returned       =   '1';
        }
        if($borrowBook->update()){
            return 1; 
        }else{
            return 0; 
        }
    }
    public function renewBook(Request $request) 
    {
        $borrowBook = BookTransaction::where('id', $request->renew_transaction_id)->first();
        $borrowBook->is_returned   = '1';
        $borrowBook->date_returned = Carbon::now();
        $studentnumber = $borrowBook->studentnumber;
        $book_id = $borrowBook->book_id;
        if($borrowBook->update()){

            $renewBook                  =   new BookTransaction();
            $renewBook->studentnumber   =   $studentnumber;
            $renewBook->book_id         =   $book_id;
            $renewBook->date_borrowed   =   Carbon::now();
            if($request->renew_days ?? ''){
                $renewBook->due_date      = Carbon::now()->addDay($request->renew_days);
            }
            else{
                $renewBook->due_date      = Carbon::now()->addDay(3);
            }
            if($renewBook->save()){
                return 1; 
            }else{
                return 0; 
            }
        }else{
            return 0; 
        }
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
