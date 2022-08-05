<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Questionnaire extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'questionnaires';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'subject_id',
        'quiz_id',
        'teacher_id',
        'user_id',
        'school_year_id',
        'type',
        'description',
        'question',
        'attachments',
        'choices',
        'json',
        'answer',
        'points',
        'active',
        'archive'
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    // public function setChoicesAttribute ()
    // {
    //     $variable = 'A';
    //     $choices = [];
    //     // dd((json_decode($request->choices)));
    //     foreach (json_decode(request('choices')) as $key => $value) {
    //         $choices[] = [
    //             $variable => $value->choices
    //         ];
    //         $variable++;
    //     }
    //     $this->attributes['choices'] = json_encode($choices);
    // }
}
