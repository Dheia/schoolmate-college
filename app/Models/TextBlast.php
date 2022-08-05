<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class TextBlast extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'text_blasts';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'title',
        'message',
        'success',
        'send_date_time',
        'subscribers',
        'response',
        'total',
        'is_now',
        'blast_type'
    ];
    // protected $hidden = ['subscribers', 'response'];
    // protected $dates = ['send_date_time'];

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

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
   
    public function getBlastTypeAttribute ()
    {
        return json_decode($this->attributes['blast_type']);
    }
   
    public function getSubscribersAttribute ()
    {
        return json_decode($this->attributes['subscribers']);
    }
   
    public function getResponseAttribute ()
    {
        return json_decode($this->attributes['response']);
    }

    public function getTimeAgoAttribute ()
    {
        return $this->created_at->diffForHumans();
    }

    public function getTotalTextMessagesAttribute ()
    {

        if((int)strlen($this->message) > 160) {
            return floor(strlen($this->message) / 160) + 1; 
        }

        return 1;
    }
    
    public function getTotalSentMessagesAttribute ()
    {
        return $this->getTotalTextMessagesAttribute() * $this->total;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
