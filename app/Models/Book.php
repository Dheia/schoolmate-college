<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Book extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'books';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'code',
        'title',
        'authors',
        'book_category_id',
        // 'book_subject_tag_id',
        'edition',
        'year_published',
        'publisher',
        'isbn',
        'accession_number',
        'call_number',
    ];
    protected $casts = [
        'authors' => 'array',
    ];
    protected $appends = [
            'is_available',
            'category_name'
        ];
    // protected $hidden = [];
    // protected $dates = [];

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

    public function authors ()
    {
        return $this->belongsToMany(BookAuthor::class, 'book_has_authors', 'book_id', 'book_author_id');
    }

    public function subjectTags ()
    {
        return $this->belongsToMany(BookSubjectTag::class, 'book_has_subject_tags', 'book_id', 'book_subject_tag_id');
    }

    public function category ()
    {
        return $this->belongsTo(BookCategory::class, 'book_category_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeIsAll ($query)
    {
        $books = $query->where('deleted_at', null);
        return $books;
    }
    public function scopeIsUnique ($query)
    {
        // $enrolled = Student::join('enrollments', function ($join) {
        //     $join->on('students.studentnumber', 'enrollments.studentnumber');
        // })->where('enrollments.school_year_id', SchoolYear::active()->first()->id)->select('students.*')->get();

        //  // dd(collect($enrolled)->pluck('studentnumber'));
        // $students = $query->whereNotIn('students.studentnumber', collect($enrolled)->pluck('studentnumber'))->get();
        // // dd($students);
        return $query->distinct()->select('title', 'book_category_id', 'call_number', 'authors', 'edition', 'year_published', 'publisher');   
    }

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
    public function setCodeAttribute(){
        if(request('code') == null || request('code') == '')
        {
            $words = explode(" ", request('title'));
            $acronym = "";
            $matches = '';
            foreach ($words as $w) {
                $re = "~L?(?:X{0,3}(?:IX|IV|V|V?I{1,3})|IX|X{1,3})|XL|L~m";
                preg_match($re, $w, $matches);
                if($matches){
                    $acronym .= $w;
                }else{
                    $acronym .= $w[0];
                }
            }
            $this->attributes['code'] = $acronym;
        }
        else{
            $this->attributes['code'] = request('code');
        }

    }

    public function getCategoryNameAttribute(){
        $category = $this->category()->first();
        if($category !== null) {
            return $category->title;
        }
        return null;
    }

    public function getIsAvailableAttribute(){
        $borrowedBooks      =   BookTransaction::where('date_returned', null)->get();
        $borrowedBooksIds   =   collect($borrowedBooks)->whereIn('book_id', $this->id);
        if(count($borrowedBooksIds) > 0){
            return 'Not Available';
        }else{
            return 'Available';
        }

    }

    public function getAuthorsNameAttribute(){
        $author     =   null;
        foreach($this->authors as $name){
            $author =  $author.'  '.$name['name'];
        }
        return $author;
    }

    public function getQuantityAttribute(){

        if( request()->type == 'Unique' ) {
            $quantity   =   count (
                                Book::where('title', $this->title)
                                    ->where('book_category_id', $this->book_category_id)
                                    ->where('call_number', $this->call_number)
                                    ->where('edition', $this->edition)
                                    ->where('year_published', $this->year_published)
                                    ->where('publisher', $this->publisher)
                                    ->where('book_category_id', $this->book_category_id)
                                    ->get()
                                );
        }
        else {
            $quantity   =   '1';
        }

       
        return   $quantity;
    } 

}
