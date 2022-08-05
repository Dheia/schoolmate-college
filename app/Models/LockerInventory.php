<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class LockerInventory extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'locker_inventories';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name','studentnumber','building_id','is_active','description'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $dates = ['deleted_at'];
    protected $appends = ['fullname'];

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

    public function building(){
        return $this->belongsTo("App\Models\Building");
    }

    public function student(){
        return $this->belongsTo("App\Models\Student",'studentnumber','studentnumber');
    }

    public function getStudent ()
    {
        return $this->belongsTo("App\Models\Student", 'studentnumber', 'studentnumber');
    }

    public function logs ()
    {
        return $this->hasMany("App\LockerInventoryLog", "locker_id", "id");
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

    public function getFullnameAttribute()
    {
        if($this->studentnumber){
            $student = Student::where('studentnumber', $this->studentnumber)->first();
            return $student ? $student->fullname : "Unknown";
        } else {
            return "Unknown";
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
