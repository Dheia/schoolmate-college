<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectManagement extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'subject_managements';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['subject_title', 'subject_code', 'subject_description', 'percent', 'no_unit', 'price', 'parent_id'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = array('name_and_percent');
    protected $dates = ['deleted_at'];

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

    public function childrens ()
    {
        return $this->hasMany(SubjectManagement::class, 'parent_id', 'id');
    }

    public function curriculum()
    {
        return $this->belongsTo('App\Models\CurriculumManagement');
    }

    public function section() {
        return $this->belongsTo('App\Models\SectionManagement');
    }

    public function parent ()
    {
        return $this->hasOne(SubjectManagement::class, 'parent_id');
    }

    public function subject ()
    {
        return $this->belongsTo(SubjectManagement::class, 'parent_id');
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
    public function getNameAndPercentAttribute()
    {
        return $this->subject_code . ' (' . $this->percent . '%)'; 
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
