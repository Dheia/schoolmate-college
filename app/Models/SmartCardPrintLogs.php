<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class SmartCardPrintLogs extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'smart_card_print_logs';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['studentnumber', 'printed'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['full_name'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getPrintedWithBadge()
    {
        if($this->printed) {
            return '<span class="badge" style="">Yes</span>';
        }
        return '<span class="badge">No</span>';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function student ()
    {
        return $this->belongsTo(Student::class, 'studentnumber', 'studentnumber');
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
    public function getFullNameAttribute ()
    {
        $student = $this->student()->first();
        return $student ? $student->firstname . ' ' . $student->lastname : '-';
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
