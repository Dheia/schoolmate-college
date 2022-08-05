<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnlineComment extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'online_comments';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'content',
        'online_post_id',
        'commentable_id',
        'commentable_type'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['commenter_name', 'commenter_photo'];

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
    public function post ()
    {
        return $this->belongsTo(OnlinePost::class, 'online_post_id');
    }

    public function commentable ()
    {
        return $this->morphTo();
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
    public function getCommenterNameAttribute()
    {
        $commentable = $this->commentable()->first();
        return $commentable ? $commentable->firstname . ' ' . $commentable->lastname : null;
    }

    public function getCommenterPhotoAttribute()
    {
        $commentable = $this->commentable()->first();
        return $commentable ? $commentable->photo : null;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
