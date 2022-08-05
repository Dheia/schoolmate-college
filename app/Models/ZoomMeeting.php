<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZoomMeeting extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'zoom_meetings';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'meetingable_id',
        'meetingable_type',
        'zoom_user_id',
        'employee_id',
        'zoom_uuid',
        'zoom_id',
        'zoom_host_id',
        'data',
        'status',
        'active',
        'start_time',
        'end_time'
    ];
    protected $appends = ['start_url', 'join_url'];
    // protected $hidden = [];
    // protected $dates = [];

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

    /*** Get the parent meetingable model (meeting or online class).*/
    public function meetingable()
    {
        return $this->morphTo();
    }

    public function zoomUser()
    {
        return $this->belongsTo(ZoomUser::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
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
    public function getStartUrlAttribute()
    {
        $data = json_decode($this->data);
        if(! $data) {
            return null;
        }
        
        return isset($data->start_url) ? $data->start_url : null;
    }

    public function getJoinUrlAttribute()
    {
        $data = json_decode($this->data);
        if(! $data) {
            return null;
        }
        
        return isset($data->join_url) ? $data->join_url : null;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
