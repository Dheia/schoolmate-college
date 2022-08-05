<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'quizzes';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'title',
        'description',
        'quiz_type',
        'questions',
        'isCorrect',
        'temp_answers',
        'duration',
        'points',
        'subject_id',
        'teacher_id',
        'user_id',
        'school_year_id',
        'active',
        'archive',
        'json'
    ];
    // protected $hidden = [];
    protected $dates = [
        'start_at',
        'end_at'
    ];
    protected $casts = [
        'questions'     => 'array',
        'isCorrect'     => 'array',
        'temp_answers'     => 'array'
    ];

    protected $appends = ['total_questions', 'total_score'];
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

    public function subject ()
    {
        return $this->belongsTo(SubjectManagement::class);
    }

    public function teacher ()
    {
        return $this->belongsTo(Employee::class);
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

    // public function getJsonAttribute ()
    // {
    //     // return json_decode($this->attributes['json']);
    // }

    public function getTotalQuestionsAttribute ()
    {
        $questions = $this->questions;
        $totalQuestions = 0;
        if(! $questions) {
            return 0;
        }
        return is_array($questions) ? count(($questions)) : 0;
    }

    public function getTotalScoreAttribute ()
    {
        $questions   = $this->questions;
        $total_score = 0;
        if(is_array($questions)) {
            $total_score = collect($questions)->pluck('points')->sum();
        }
        return $total_score;
    }

    // public function getTotalPagesAttribute ()
    // {
    //     return $this->json ? count($this->json->pages) : 0;
    // }

    // public function getTotalScoreAttribute ()
    // {
    //     $pages = $pages = $this->json ? $this->json->pages : [];
    //     $totalPoints = 0;
    //     foreach ($pages as $key => $page) {
    //         $totalPoints += collect($page->elements)->sum('points');
    //     }
    //     return $totalPoints;
    // }

    // public function getCorrectAnswersAttribute()
    // {
    //     $pages = $pages = $this->json ? $this->json->pages : [];
    //     $correctAnswers = [];
    //     if($pages) {
    //         foreach ($pages as $key => $page) {
    //             if($page->elements) {
    //                 foreach($page->elements as $elKey => $question) {
    //                     $correctAnswers[$question->name] = $question->correctAnswer ?? null;
    //                 }
    //             }
    //         }
    //     } 

    //     return $correctAnswers;
    // }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
