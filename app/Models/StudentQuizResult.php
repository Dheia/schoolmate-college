<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class StudentQuizResult extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'student_quiz_results';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'studentnumber', 
        'online_class_quiz_id', 
        'attempts',
        'questionnaire',
        'results',
        'score',
        'final_score',
        'is_check',
        'time_start_at',
        'time_end_at'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['student_score', 'total_score'];
    protected $casts = [
        'results'     => 'array',
        'final_score' => 'array'
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
    public function student()
    {
        return $this->belongsTo(Student::class, 'studentnumber', 'studentnumber');
    }

    public function classQuiz()
    {
        return $this->belongsTo(OnlineClassQuiz::class, 'online_class_quiz_id');
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
    public function getTotalScoreAttribute ()
    {
        $questions          = json_decode($this->questionnaire);
        $totalPoints    = null;
        foreach ($questions as $question) {
            $totalPoints += $question->points;
            
        }
        return $totalPoints;
    }

    public function getStudentScoreAttribute ()
    {
        $questions   = collect($this->final_score);
        $totalPoints = collect($questions)->pluck('score')->sum();
        return $totalPoints;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
