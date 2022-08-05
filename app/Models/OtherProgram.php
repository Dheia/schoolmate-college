<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtherProgram extends Model
{
    use CrudTrait;
    use \Awobaz\Compoships\Compoships;
    use SoftDeletes;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'other_programs';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'qbo_map', 'school_year_id', 'amount'];
    protected $hidden = ['qbo_map'];
    // protected $dates = [];
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

    public function paymentHistories ()
    {
        return $this->morphMany('App\PaymentHistory', 'payment_historable');
    }

    public function schoolYear ()
    {
        return $this->belongsTo('App\Models\SchoolYear');
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

    public function getNameWithSchoolYearAttribute ()
    {
        if($this->schoolYear == null) {
            return $this->name;
        }
        return $this->name . ' A.Y. ' . $this->schoolYear->schoolYear;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
