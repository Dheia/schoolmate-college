<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeTemplate extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'grade_templates';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        // 'period_id',
        // 'teacher_id',
        'name',
        'school_year_id',
        'description'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $dates = ['deleted_at'];
    // protected $appends = ['template_school_year'];

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

    public function schoolYear(){
        return $this->belongsTo('App\Models\SchoolYear');
    }

    public function period(){
        return $this->belongsTo('App\Models\Period','period_id');
    }

    public function teacher(){
        return backpack_auth()->user();
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

    public function getCurrentSchoolYearAttribute($query){
        return $query->where('is_active', '=', 1);
    }

    // public function getTemplateSchoolYearAttribute ($query)
    // {
    //     return $this->name . ' | ' . \App\Models\SchoolYear::where('id', $this->schoolyear_id)->firstOrFail()->schoolYear;
    // }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
