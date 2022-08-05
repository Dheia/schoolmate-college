<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Arr;

class ZoomRecording extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'zoom_recordings';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'meetingable_id',
        'meetingable_type',
        'zoom_id',
        'zoom_uuid',
        'zoom_host_id',
        'duration',
        'share_url',
        'recording_files',
        'password',
        'status'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['audio_only', 'shared_screen_with_speaker_view'];

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

    public function zoomMeeting()
    {
        return $this->belongsTo(ZoomMeeting::class, 'zoom_uuid', 'zoom_uuid');
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
    public function getAudioOnlyAttribute()
    {
        $recording_files =  json_decode($this->recording_files);
        $audio_only      =  Arr::first($recording_files, function ($value, $key) {
                                return $value->recording_type == 'audio_only';
                            });

        return $audio_only;
    }

    public function getSharedScreenWithSpeakerViewAttribute()
    {
        $recording_files =  json_decode($this->recording_files);
        $share_screen    =  Arr::first($recording_files, function ($value, $key) {
                                return $value->recording_type == 'shared_screen_with_speaker_view';
                            });

        return $share_screen;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
