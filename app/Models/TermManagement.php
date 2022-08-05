<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class TermManagement extends Model
{
    use CrudTrait, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'term_managements';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['department_id', 'name', 'type', 'no_of_term'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['ordinal_terms'];

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

    public function department ()
    {
        return $this->belongsTo("App\Models\Department");
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

    public function getOrdinalTermsAttribute ()
    {
        $locale = 'en_US';
        $formatter = new \NumberFormatter($locale, \NumberFormatter::SPELLOUT);
        $formatter->setTextAttribute(\NumberFormatter::DEFAULT_RULESET, "%spellout-ordinal");

        if($this->attributes['type'] == 'FullTerm') {
            return ['Full'];
        } else {
            $terms = [];
            for ($i = 1; $i <= $this->no_of_term; $i++) {
                $terms[] = ucfirst($formatter->format($i));
            }
            return $terms;
        }
        return [];
    }
   
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setNoOfTermAttribute ($value) {

        if($this->type == 'FullTerm') {
            $this->attributes['no_of_term'] = 0; 
        } else {
            $this->attributes['no_of_term'] = $value;  
        }
    }
}
