<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookReservation;
use App\Models\Student;
use App\Models\Rfid;
use Auth;
use Carbon\Carbon;

use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\SectionManagement;
use App\Models\StudentSectionAssignment;

class LibraryTransactionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $books = Book::all();
        $books->paginate(5);
        

    	return response()->json(["books"=>$books]);
    }

    public function mybooks()
    {
        $id = Auth::id();
        $mybooks = Book::leftJoin('book_reservations','books.id','=','book_reservations.book_id')
            ->where('book_reservations.student_id',$id)
            ->where('books.status',"reserve")
            ->where('book_reservations.status',"reserved")
            ->get();

        return response()->json(["books"=>$mybooks]);
    }
    public function unreturn()
    {
        $id = Auth::id();
        $mybooks = Book::leftJoin('book_transactions','books.id','=','book_transactions.book_id')
            ->where('book_transactions.studentnumber',$id)
            ->where('books.status',"reserve")
            ->where('book_transactions.is_returned',false)
            ->get();

        return response()->json(["books"=>$mybooks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $day = Carbon::now();        
        if($request->action == "reserve"){
            $sid = Auth::id();
            $student = Student::find($sid);            
            $rfid = Rfid::where('studentnumber',$student->studentnumber)->first();          
            $bookreservation = BookReservation::create([
                'rfid' => $rfid->rfid,
                'student_id' => $sid,
                'book_id' => $request->name,
                'date_reserved' => Carbon::now(),                
                'status' => "reserved"
            ]);
            $id = $request->name;
            $bookupdate = Book::find($id);
            $bookupdate->update(['status' => "reserve"]);
        }
        else{
            $id = $request->name;
            $bookreservation = BookReservation::find($id);
            $bookreservation->update([                                
                'date_updated' => Carbon::today(),            
                'status' => "canceled"
            ]);
            $id = $bookreservation->book_id;        
            $bookupdate = Book::find($id);
            $bookupdate->update(['status' => "avail"]);
        }   	
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function library(){
        $title = "Library";
    	$id = Auth::id();
    	$student = Student::where('id',$id)->first();
    	return view('studentLibraryTransaction',compact(['student', 'title']));
    }
}
