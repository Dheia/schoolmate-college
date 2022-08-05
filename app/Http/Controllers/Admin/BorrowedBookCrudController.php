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
use App\Models\Book;

use Carbon\Carbon;
use Config;

/**
 * Class BookTransactionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class BorrowedBookCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\BookTransaction');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/library/borrowed-book');
        $this->crud->setEntityNameStrings('borrowedbook', 'Borrowed Books');
        $this->crud->addClause('where', 'date_returned', '=', null);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();

        // add asterisk for fields that are required in BookTransactionRequest

        $this->crud->removeAllButtons();
        $this->crud->addColumn([  // Select
            'name'      => 'accession_number',
            'label'     => "Accession No.",
            'type'      => 'select', // the db column for the foreign key
            'entity'    => 'book', // the method that defines the relationship in your Model
            'attribute' => 'accession_number', // foreign key attribute that is shown to user
            'model'     => "App\Models\Book", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('book', function ($q) use ($column, $searchTerm) {
                    $q->where('accession_number', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);
        $this->crud->addColumn([  // Select
            'name'      => 'book_title',
            'label'     => "Book Title",
            'type'      => 'select', // the db column for the foreign key
            'entity'    => 'book', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model'     => "App\Models\Book", // foreign key model
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('book', function ($q) use ($column, $searchTerm) {
                    $q->where('title', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);
        $this->crud->addColumn([  // Select
            'name'      => 'borrower_fullname',
            'label'     => "Borrower",
            'type'      => 'text',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('student', function ($q) use ($column, $searchTerm) {
                    $q->where('firstname', 'like', '%'.$searchTerm.'%')
                    ->orWhere('middlename', 'like', '%'.$searchTerm.'%')
                    ->orWhere('lastname', 'like', '%'.$searchTerm.'%');
                });
                $query->orWhereHas('employee', function ($q) use ($column, $searchTerm) {
                    $q->where('firstname', 'like', '%'.$searchTerm.'%')
                    ->orWhere('middlename', 'like', '%'.$searchTerm.'%')
                    ->orWhere('lastname', 'like', '%'.$searchTerm.'%');
                });
            }
        ]);
        $this->crud->addColumn([
            'name' => 'id_number',
            'type' => 'text',
            'label' => 'ID No.'
        ]);
        $this->crud->addColumn([  // Select
            'name'      => 'current_level',
            'label'     => "Grade Level",
            'type'      => 'select', // the db column for the foreign key
            'entity'    => 'student', // the method that defines the relationship in your Model
            'attribute' => 'current_level', // foreign key attribute that is shown to user
            'model'     => "App\Models\Student"
        ]);
        $this->crud->addColumn([  // Select
            'name'      => 'position',
            'label'     => "Position",
            'type'      => 'select', // the db column for the foreign key
            'entity'    => 'employee', // the method that defines the relationship in your Model
            'attribute' => 'position', // foreign key attribute that is shown to user
            'model'     => "App\Models\Employee"
        ]);
        $this->crud->addColumn([
            'name' => 'date_borrowed',
            'type' => 'datetime',
            'label' => 'Borrowed Date',
            'format' => 'MMMM DD, YYYY - hh:mm A'
        ]);
        $this->crud->addColumn([
            'name' => 'due_date',
            'type' => 'datetime',
            'label' => 'Due Date',
            'format' => 'MMMM DD, YYYY - hh:mm A'
        ]);
        $this->crud->addColumn([
            'name' => 'fine',
            'type' => 'text',
            'label' => 'Fine',
            'prefix' => '₱',
        ]);
        $this->crud->addColumn([
            'name' => 'status',
            'type' => 'text',
            'label' => 'Status',
        ]);
        $this->crud->addFilter([ // date filter
          'type' => 'date',
          'name' => 'date_borrowed',
          'label'=> 'Borrowed Date'
        ],
        false,
        function($value) { // if the filter is active, apply these constraints
            $this->crud->addClause('where', 'date_borrowed', '>=', $value);
            $this->crud->addClause('where', 'date_borrowed', '<=', $value . ' 23:59:59');
        });
        $this->crud->addFilter([ // date filter
          'type' => 'date',
          'name' => 'due_date',
          'label'=> 'Due Date'
        ],
        false,
        function($value) { // if the filter is active, apply these constraints
            $this->crud->addClause('where', 'due_date', '>=', $value);
            $this->crud->addClause('where', 'due_date', '<=', $value . ' 23:59:59');
        });
        $this->crud->addFilter([ // dropdown filter
            'name' => 'status',
            'type' => 'dropdown',
            'label'=> 'Status'
          ], [
            1 => 'Over Due'
          ], function($value) { // if the filter is active
                if($value == 1){
                    $this->crud->addClause('where', 'due_date', '<', Carbon::now());
                    $this->crud->addClause('where', 'date_returned', null);
                }
        });


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
