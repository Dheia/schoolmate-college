<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\BookReportRequest as StoreRequest;
use App\Http\Requests\BookReportRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

// MODELS
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\BookTransaction;

/**
 * Class BookReportCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class BookReportCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\BookReport');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/library/book-report');
        $this->crud->setEntityNameStrings('Book Report', 'Book Report');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in BookReportRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->denyAccess(['create', 'update', 'delete', 'reorder']);
        $this->crud->setListView('bookReport.home');


         $this->crud->addField([
            'label' => 'Generate Report',
            'name' => 'generate_report',
            'type' => 'select2_from_array',
            'options' => [
                'All Books'   => 'All Books',
                // 'Unique Books'   => 'Unique Books',
                'Available Books'   => 'Available Books',
                'Borrowed Books'   => 'Borrowed Books'
            ],
            'default'   =>  'enrollments.level_id',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
            'allows_null' => false
        ]);
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

    public function generateReport (StoreRequest $request)
    {
        ini_set('max_execution_time', 300);
        
        if($request->generate_report == "All Books") {
            $title  =   "BOOK LIST";
            $books  =   Book::with('category')->orderBy('title')->get();

            if(count($books)>0) {
                \Alert::warning('Book Record is Empty.')->flash();
                return redirect()->back();
            }

            return $this->printAllBooks($title, $books);
        }
        else if($request->generate_report == "Available Books") {
            $title              =   "AVAILABLE BOOKS";
            $borrowed_books     =   BookTransaction::where('date_returned', null)->get();

            $books              =   Book::with('category')->whereNotIn('id', collect($borrowed_books)->pluck('book_id'))->orderBy('title')->get();

            if(count($books)>0) {
                \Alert::warning('Book Record is Empty.')->flash();
                return redirect()->back();
            }

            return $this->printAllBooks($title, $books);
        }
        else if($request->generate_report == "Borrowed Books") {
            $title          =   "BORROWED BOOKS";
            $books          =   BookTransaction::with('book', 'student', 'employee')
                                            ->orderBy('date_borrowed')
                                            ->orderBy('due_date')
                                            ->where('date_returned', null)
                                            ->get();

            if(count($books)>0) {
                \Alert::warning('Book Record is Empty.')->flash();
                return redirect()->back();
            }
            
            return $this->printBorrowedBooks();
        }
    }

    public function printAllBooks($title, $books)
    {
        $title = $title;
        $books = $books;

        $pdf = \App::make('dompdf.wrapper');

        $pdf->loadHTML( view('bookReport.pdfAllBooks', compact('books', 'title')));
        return $pdf->stream();
    }

    public function printBorrowedBooks($title, $books)
    {
        $title = $title;
        $books = $books;

        $pdf = \App::make('dompdf.wrapper');

        $pdf->loadHTML( view('bookReport.pdfBorrowedBooks', compact('books', 'title')));
        return $pdf->stream();
    }
}
