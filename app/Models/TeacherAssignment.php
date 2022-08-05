<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherAssignment extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'employees';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'employee_id',
        'type',
        // 'schoolyear_id',
        // 'subject_id',
        // 'subjects',
        // 'is_active'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $dates = ['deleted_at'];
    protected $appends = ['has_teacher_role'];

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

    public function employee ()
    {
        return $this->belongsTo(Employee::class, 'id', 'id');
    }

    public function subjects() {
        return $this->belongsToMany(SubjectManagement::class);
    }

    public function user () 
    {
        return $this->hasOne(User::class, 'employee_id', 'id');
    }

    // public function subject() {
    //     return $this->belongsTo('App\Models\SubjectManagement','subject_id');
    // }

    // public function employee(){
    //     return $this->belongsTo('App\Models\Employee','employee_id','id');
    // }

    // public function schoolyear() {
    //     return $this->belongsTo('App\Models\SchoolYear','schoolyear_id');
    // }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeTeachingPersonnel ($query)
    {
        $query->where('type', 'Teaching Personnel')->orWhere('type', 'Non-Teaching/Teaching');
        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    public function getFullNameAttribute ()
    {
        return $this->firstname . ' ' . $this->middlename .  ' ' . $this->lastname;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
