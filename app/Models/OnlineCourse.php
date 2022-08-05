<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnlineCourse extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'online_courses';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'code',
        'name',
        'description',
        'requirements',
        'content_standard',
        'performance_standard',
        'duration',
        'teacher_id',
        'subject_id',
        'level_id',
        'color',
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
    public function teacher ()
    {
        return $this->belongsTo(Employee::class);
    }
    public function subject ()
    {
        return $this->belongsTo(SubjectManagement::class);
    }
    public function level ()
    {        
        return $this->belongsTo(YearManagement::class, 'level_id');
    }
    public function modules ()
    {
        return $this->hasMany(OnlineClassModule::class);
    }
    public function class ()
    {
        return $this->hasMany(OnlineClass::class, 'online_course_id');
    }
    public function share() {
        return $this->belongsToMany(Employee::class, 'online_course_teacher', 'online_course_id', 'teacher_id');
    }
    public function topics ()
    {
        return $this->hasMany(OnlineClassTopic::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeActive ($query)
    {
        return $query->where('active', 1);
    }
    public function scopeNotActive ($query)
    {
        return $query->where('active', 0);
    }
    public function scopeArchive ($query)
    {
        return $query->where('archive', 1);
    }
    public function scopeNotArchive ($query)
    {
        return $query->where('archive', 0);
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
}
