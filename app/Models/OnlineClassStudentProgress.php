<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnlineClassStudentProgress extends Model
{
    use CrudTrait;
    use SoftDeletes;

    protected $table = 'online_class_student_progresses';

    protected $fillable = [
        'student_id',
        'online_class_id',
        'online_topic_page_id'
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
    public function student ()
    {
        return $this->belongsTo(Student::class);
    }
    public function page ()
    {        
        return $this->belongsTo(OnlineTopicPage::class);
    }
    public function class ()
    {
        return $this->belongsTo(OnlineClass::class);
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
}
