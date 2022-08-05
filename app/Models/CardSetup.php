<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class CardSetup extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'card_setups';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['template_name', 'front_card', 'rear_card', 'active'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['front_card_columns', 'back_card_columns'];

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

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActive ($query)
    {
        return $query->where('active', 1);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getFrontCardAttribute ()
    {
        return json_decode($this->attributes['front_card']);
    }

    public function getRearCardAttribute ()
    {
        return json_decode($this->attributes['rear_card']);
    }

    public function getFrontCardColumnsAttribute ()
    {
        $front = collect($this->getFrontCardAttribute()->objects)->pluck('id');
        return $front;
    }

    public function getBackCardColumnsAttribute ()
    {
        $back = collect($this->getRearCardAttribute()->objects)->pluck('id');
        return $back;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
