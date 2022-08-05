<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CafeteriaRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Laravel\Passport\Client;

use App\Models\Book;
use App\Models\BookTransaction;


use Carbon\Carbon;

class LibraryController extends Controller
{
	private $client;

    public function __construct ()
    {
        $this->client = Client::find(1);
    }
    public function index(){

        return view('library.search_book');
    }

    public function getBooks(Request $request){
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
}
