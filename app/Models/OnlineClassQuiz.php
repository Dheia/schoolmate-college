<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

use Carbon\Carbon;

class OnlineClassQuiz extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'online_class_quizzes';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'online_class_id',
        'quiz_id',
        'online_post_id',
        'start_at',
        'end_at',
        'allow_late_submission',
        'allow_retake',
        'shuffle',
        'school_year_id'
    ];
    // protected $casts = [
    //     // 'start_at' => 'datetime:M d, Y - h:m A',
    //     // 'end_at' => 'datetime:M d, Y - h:m A'
    // ];
    // // protected $hidden = [];
    // protected $dates = ['start_at', 'end_at'];
    protected $appends = ['show_results'];

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

    public function onlineClass ()
    {
        return $this->belongsTo(OnlineClass::class);
    }

    public function quiz ()
    {
        return $this->hasOne(Quiz::class, 'id', 'quiz_id');
    }

    public function onlinePost ()
    {
        return $this->belongsTo(OnlinePost::class);
    }

    public function schoolYear ()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function studentQuizResults ()
    {
        return $this->hasMany(StudentQuizResult::class);
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
    public function getShowResultsAttribute()
    {
        if(!$this->allow_late_submission && !$this->allow_retake && Carbon::now() > $this->end_at) {
            return 1;
        }
        return 0;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

}
