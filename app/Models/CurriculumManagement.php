<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurriculumManagement extends Model
{
    use CrudTrait;
    use \Awobaz\Compoships\Compoships;
    use SoftDeletes;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'curriculum_managements';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'curriculum_name', 
        'description', 
        // 'subject_details'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $dates = ['deleted_at'];
    protected $appends = ['total_subject_mappings'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getTotalSubjectMapSlugWithLink()
    {
        return '<a href="curriculum_management/' . $this->id . '/subjects">' . $this->subjectMappings()->count() . '</a>';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    // public function subject(){
    //     return $this->belongsTo('App\Models\SubjectManagement', 'curriculum_id');
    // }

    // public function subjects ()
    // {
    //     return $this->hasMany("App\Models\SubjectManagement", "curriculum_id");
    // }

    public function subjectMappings ()
    {
        return $this->hasMany("App\Models\SubjectMapping", "curriculum_id");
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

    public function getTotalSubjectMappingsAttribute ()
    {
        return $this->subjectMappings()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
