<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Config;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookTransaction extends Model
{
    use CrudTrait;
    use SoftDeletes;
    use \Awobaz\Compoships\Compoships;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'book_transactions';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [];
    protected $appends  =   [   'title', 
                                'accession_number', 
                                'status', 
                                'fine', 
                                'borrower_fullname',
                                'fine_status'
                            ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
        'date_borrowed' => 'datetime:M d, Y - h:m A',
        'date_returned' => 'datetime:M d, Y - h:m A',
        'due_date'      => 'datetime:M d, Y - h:m A',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function student() {
        return $this->belongsTo("App\Models\Student", "studentnumber", 'studentnumber');
    }
     public function employee() {
        return $this->belongsTo("App\Models\Employee", "employee_id", 'employee_id');
    }
    public function book() {
        return $this->belongsTo(Book::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function getIdNumberAttribute(){
        if($this->studentnumber ?? '')
        {
            return $this->studentnumber;
        }
        else{
            return $this->employee_id;
        }
    }
    public function getBorrowerFullnameAttribute(){
        if($this->studentnumber ?? '')
        {
            $fullname = $this->student()->first();
            return $fullname->full_name;
        }
        else{
            $fullname = $this->employee()->first();
            return $fullname->full_name;
        }
    }
    public function getTitleAttribute() {
        $book = $this->book()->first();
        return  $book ? $book->title: "";
    }
    public function getAccessionNumberAttribute() {
        $book = $this->book()->first();        
        return  $book ? $book->accession_number: "";
    }
    public function getCurrentLevelAttribute(){
        if($this->studentnumber ?? ''){
            $current_level = Student::where('studentnumber', $this->studentnumber)->first();
            return $current_level->current_level;
        }
        return '-';
    }
    public function getStatusAttribute() {
        // IF 0 DAYS LEFT
        if(($this->due_date < Carbon::now()) == true && $this->date_returned == null) {
            return "Over Due";
        }
        else if($this->date_returned != null) {
            // if($this->fine > 0 && $this->paid_date == null){
            //     return "Returned/Unpaid";
            // }
            // else if($this->fine > 0 && $this->paid_date != null){
            //     return "Returned/Paid";
            // }
            return "Returned";
        }
        else{
            return "Borrowed";
        }
    }

    public function getFineStatusAttribute(){
        if($this->fine == 0){
            return "No Fine";
        }
        else if($this->paid_date == null){
            return "Not Paid";
        }
        else{
            return "Paid";
        }
    }

    public function getFineAttribute(){
        if($this->due_date < Carbon::now() && $this->date_returned == null && $this->paid_date == null){
            $fine = (int)(Carbon::now()->floatDiffInDays($this->due_date)) * ((int)Config::get('settings.bookfine'));
            if($fine == 0){
                return Config::get('settings.bookfine');
            }
            return  $fine;    
        }else{
            if($this->date_returned > $this->due_date){
                $date_returned = $this->date_returned;
                $fine = (int)(Carbon::parse($date_returned)->diffInDays($this->due_date)) * ((int)Config::get('settings.bookfine'));
                if($fine == 0){
                    return number_format((float)Config::get('settings.bookfine'), 2, '.', '');
                }
                return $fine;
            }else{
                return '0.00';
            }
        }
    }

    // public function getStudentTotalFineAttribute(){
    //     $books = collect(BookTransaction::where('studentnumber', $this->studentnumber)
    //                                     ->where('fine', '>', '0')
    //                                     ->where('paid', null)
    //                                     ->where('paid_date', null)
    //                                     ->where('deleted_at', null)
    //                                    ->get());
    //     return $books->sum('fine'); 
    // }
}
