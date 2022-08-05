<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\BookRequest;
use App\Http\Requests\BookBulkRequest;
use App\Http\Requests\BookModalRequest;
use App\Http\Requests\BookRequest as StoreRequest;
use App\Http\Requests\BookRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\Book;
use App\Models\BookTransaction;

use DB;
use Redirect;

/**
 * Class BookCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class BookCrudController extends CrudController
{
    public $data=[];
    public $sample = ['isbn'];

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Book');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/library/book');
        $this->crud->setEntityNameStrings('Book', 'Books');
        $this->crud->allowAccess('show');
        $this->crud->allowAccess('add-copy');
        $this->crud->addButtonFromView('line', 'library.add-copy', 'library.add-copy', 'beginning');

        $this->data['total_books']          =   count($this->crud->model::all());
        $this->data['total_unique_books']   =   count($this->crud->model::where('deleted_at', null)
                                                    ->distinct()->select('title', 'book_category_id')->get());
        $this->data['total_borrowed_books'] =   count(BookTransaction::where('deleted_at', null)
                                                    ->where('date_returned', null)->get());

        // Get Accession Max Value and Plus One and Set It Default Accession Number
        $this->data['default_accession_number'] = str_pad($this->crud->model::max('accession_number')+1,6,'0',STR_PAD_LEFT);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
       
         $this->crud->addField([
            'label' => 'Call Number',
            'type' => 'text',
            'name' => 'call_number',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            'label' => 'Accession No.',
            'type' => 'number',
            'name' => 'accession_number',
            'default'    => $this->data['default_accession_number'],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            'label' => 'ISBN',
            'type' => 'text',
            'name' => 'isbn',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            'label' => 'Code',
            'type' => 'text',
            'name' => 'code',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Title',
            'type' => 'text',
            'name' => 'title',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Authors',
            'type' => 'table',
            'name' => 'authors',
            'entity_singular' => 'author', // used on the "Add X" button
            'columns' => [
                'name' => 'Name'
            ],
            'max' => 4, // maximum rows allowed in the table
            'min' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
        ]);

        $this->crud->addField([
            'label' => 'Category',
            'type' => 'select2',
            'name' => 'book_category_id',
            'entity' => 'category',
            'attribute' => 'title',
            'models' => 'App\Models\BookCategory',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Subject Tag',
            'type' => 'select2_multiple',
            'name' => 'subjectTags',
            'entity' => 'subjectTags',
            'attribute' => 'name',
            'model' => 'App\Models\BookSubjectTag',
            'pivot' => true,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ]
        ])->afterField('book_category_id');

        $this->crud->addField([
            'label' => 'Edition',
            'type' => 'text',
            'name' => 'edition',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Year Published',
            'type' => 'number',
            'name' => 'year_published',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => 'Publisher',
            'type' => 'text',
            'name' => 'publisher',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);
        //  $this->crud->addField([
        //     'label' => 'Quantity',
        //     'type' => 'number',
        //     'name' => 'quantity',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-12'
        //     ]
        // ]);
        
        /*
        |--------------------------------------------------------------------------
        | COLUMNS
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumn([
            'label' => 'Accession No.',
            'type' => 'text',
            'name' => 'accession_number',
        ]);
        $this->crud->addColumn([
            'label' => 'Call No.',
            'type' => 'text',
            'name' => 'call_number'
        ]);
        $this->crud->addColumn([
            'label' => 'Title',
            'type' => 'text',
            'name' => 'title'
        ]);
        $this->crud->addColumn([
            'label' => 'Category',
            'type' => 'select',
            'name' => 'book_category_id',
            'entity' => 'category',
            'attribute' => 'title',
            'models' => 'App\Models\BookCategory',
        ]);

         $this->crud->addColumn([
            'label' => 'Authors',
            'type' => 'text',
            'name' => 'authors_name'
        ]);

        $this->crud->addColumn([
                'name'  =>  'quantity',
                'type'  =>  'text',
                'label' =>  'Quantity'
        ])->afterColumn('book_category_id');


        /*
        |--------------------------------------------------------------------------
        | FILTERS
        |--------------------------------------------------------------------------
        */
         $this->crud->addFilter([ // select2 filter
          'name' => 'type',
          'type' => 'select2',
          'label'=> 'Type'
        ], function() {
            return ['All' => 'All', 'Unique' => 'Unique'];
        }, function($value) {
            if($value == 'All'){
                $this->crud->addClause('isAll');
            }
            else if ($value == 'Unique'){
                $this->crud->addClause('isUnique');
            }
            
        });

        if( request()->type == 'Unique' ){
            $this->crud->removeButton('delete');
            $this->crud->removeButton('update');
            $this->crud->denyAccess('show');
            $this->crud->denyAccess('add-copy');
        }

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in BookRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        
        $this->crud->removeColumn('authors');
        $this->crud->setListView('library.books.list');
        // $this->crud->setEditView('gradeSetup.edit');
        $this->crud->setCreateView('library.books.create');
        
    }
    public function show($id)
    {
        $this->crud->addColumn([
            'name'    => 'authors', 
            'label'   => 'Authors', 
            'type'    => 'table', 
            'columns' => [
                'name' => ''
            ]
        ]);
        $content = parent::show($id);
        $this->crud->denyAccess('add-copy');
        return $content;
    }

    public function store(StoreRequest $request)
    {
        if(request('quantity') < 1 || request('quantity') ==  null){
            // your additional operations before save here
            $redirect_location = parent::storeCrud($request);
            // your additional operations after save here
            // use $this->data['entry'] or $this->crud->entry
            return redirect($this->crud->route);
        }else{
            // $data[];
            $this->data['isbn']             =   $request->isbn;
            $this->data['code']             =   $request->code;
            $this->data['title']            =   $request->title;
            $this->data['edition']          =   $request->edition;
            $this->data['quantity']         =   $request->quantity;
            $this->data['publisher']        =   $request->publisher;
            $this->data['call_number']      =   $request->call_number;
            $this->data['year_published']   =   $request->year_published;
            $this->data['book_category_id'] =   $request->book_category_id;
            $this->data['accession_number'] =   $request->accession_number;
            $this->data['authors']          =   collect(json_decode($request->authors))->pluck('name')->toArray();
            // dd($this->data);

            $data = $this->data;
            // $this->data['crud'] = collect($this->data['crud'])
            // dd($this->data);
            // dd($this->crud->entity_name);
            // dd($this->data['crud']['entity_name_plural']);
            return redirect($this->crud->route.'/create/bulk/'.
                            $this->data['title'].'/'.
                            $this->data['quantity'].'/'.
                            $this->data['call_number'].'/'.
                            $this->data['accession_number'].'/'.
                            $request->authors .'/'.
                            $this->data['edition'].'/'.
                            $this->data['year_published'].'/'.
                            $this->data['publisher'].'/'.
                            $this->data['book_category_id'].
                            '?isbn='.$this->data['isbn'].
                            '&code='.$this->data['code']);
            // return view('library.books.bulk-create', $data)->with('data', json_encode($data));
            // return view('library.books.bulk-create', $this->data)->with('data', json_encode($data));
            // return redirect($this->crud->route.'/create/bulk')->with('data', $this->data)->with('insert', json_encode($data));
        }
    }

    // public function bulkCreate($title, $quantity, $call_number, $accession_number, $authors, $edition, $year_published, $publisher, $book_category_id){
    //     $this->data['isbn']             =   request()->get('isbn');
    //     $this->data['code']             =   request()->get('code');
    //     $this->data['title']            =   $title;
    //     $this->data['edition']          =   $edition;
    //     $this->data['quantity']         =   $quantity;
    //     $this->data['publisher']        =   $publisher;
    //     $this->data['call_number']      =   $call_number;
    //     $this->data['year_published']   =   $year_published;
    //     $this->data['book_category_id'] =   $book_category_id;
    //     $this->data['accession_number'] =   $accession_number;
    //     $this->data['authors']          =   collect(json_decode($authors))->pluck('name')->toArray();
    //     $this->data['crud']['route']                = $this->crud->route.'/bulk-store';
    //     $this->data['crud']['entity_name']          = $this->crud->entity_name;
    //     $this->data['crud']['entity_name_plural']   = $this->crud->entity_name_plural;
       
    //     return view('library.books.bulk-create', $this->data)->with('data', json_encode($this->data));
    // }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function addBookCopy(BookModalRequest $request){
        
        $id   = request('book_id');
        $this_book = Book::where('id', $id)->first();
        // $tags = DB::table('book_has_subject_tags')->where('book_id', $id)->get();
        // $tags = collect($tags)->pluck('book_subject_tag_id')->toArray();
        $books = new Book();
        $books->title            =  $this_book->title;
        $books->authors          =  $this_book->authors;
        $books->edition          =  $this_book->edition;
        $books->isbn             =  request('modal_isbn');
        $books->code             =  request('modal_code');
        $books->publisher        =  $this_book->publisher;
        $books->year_published   =  $this_book->year_published;
        $books->book_category_id =  $this_book->book_category_id;
        $books->call_number      =  request('modal_call_number');
        $books->accession_number =  request('modal_accession_number');
       
        if($books->save()){
            \Alert::success('Successfully Added.')->flash();
            return \Redirect::to($this->crud->route);
        }
        else{
            \Alert::error('Error Adding, Something Went Wrong, Please Try Again.')->flash();
            return \Redirect::to($this->crud->route);
        }
    }

    // public function bulkStore(BookBulkRequest $request){


    //     $data = json_decode($request->data);
    //     for ($i=1; $i <= $data->quantity; $i++) {
    //         $validatedData[] = $request->validate([
    //             'isbn'.$i => [
    //                 'nullable',
    //                 Rule::unique('books', 'isbn')->where('title', '!=',  $data->title),
    //                 Rule::notIn($isbn),
    //             ],
    //             'code'.$i => [
    //                 'nullable',
    //                 Rule::unique('books', 'isbn')->where('title', '!=',  $data->title),
    //                 Rule::notIn($isbn),
    //             ],
    //         ]);
    //     }
    //     dd(request('isbn2'));
    // }
}
