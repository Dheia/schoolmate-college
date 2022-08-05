<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class SectionManagement extends Model
{
    use CrudTrait;
    use \Awobaz\Compoships\Compoships;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'section_managements';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'name', 
        'curriculum_id', 
        'track_id',
        'level_id', 
        // 'year_id', 
        'subject_details'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'subject_details' => 'object'
    ];
    protected $appends = ['name_level', 'track_code'];
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
    public function curriculum(){
        return $this->belongsTo('App\Models\CurriculumManagement');
    }


    public function subject () {
        return $this->belongsTo('App\Models\SubjectManagement', 'curriculum_id');
    }

    public function section () {
        return $this->belongsTo('App\Models\SectionManagement');
    }

    public function level () {        
        return $this->belongsTo(YearManagement::class, 'level_id');
    }

    // public function levels ()
    // {
    //     return $this->hasManay('App\Models\YearManagement');
    // }

    public function year () {
        return $this->belongsTo('App\Models\SchoolYear');
    }

    public function track () {
        return $this->belongsTo('App\Models\TrackManagement');
    }

    public function levelWithTrashed () {        
        return $this->level()->withTrashed();
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

    public function getDepartmentAttribute ()
    {
        $level = \App\Models\YearManagement::where('id', $this->level_id)->first();
        return $level ? $level->department_name : "-";
    }

    public function getLevelAttrAttribute ()
    {
        $level = \App\Models\YearManagement::where('id', $this->level_id)->first();

        if($level !== null)
        {
            return $level->year;
        }

        return "-";
    }

    public function getNameLevelAttribute ()
    {
        $level =  $this->level()->first();

        if($level !== null)
        {
            return $level->year. ' | '.$this->name;
        }

        return "-";
    }

    public function getTrackCodeAttribute ()
    {
        $track =  $this->track()->first();

        if($track !== null)
        {
            return $track->code;
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
