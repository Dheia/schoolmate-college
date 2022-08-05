<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrackManagement extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'track_managements';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['level_id', 'code', 'description', 'active'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    private function object_to_string($objects, $field='name', $glue=', ') {
      $output = array();
      if(!empty($objects) && count($objects) > 0) {
        foreach($objects as $object) {
          if(is_array($object) && isset($object[$field])) {
            $output[] = $object[$field];
          } else  if(is_object($object) && isset($object->$field)) {
            $output[] = $object->$field;
          } else {
            // TODO: homework assignment =)
          }
        }
      }
      return join($glue, $output);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function level(){
        return $this->belongsTo('App\Models\YearManagement')->orderBy('sequence');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActive ($query)
    {
        $query->where('active', 1);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    // public function getDescriptionFormattedAttribute ()
    // {
    //     return self::object_to_string(json_decode($this->description), $field='subject');
    // }

    public function getDescriptionFormattedAttribute ()
    {
        $level = self::level()->first();
        return $level ? $level->year . ' - ' . $this->code : '-';
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
