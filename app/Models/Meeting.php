<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

use App\Http\Controllers\BBB;

class Meeting extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'meetings';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'code', 
        'name', 
        'description', 
        'start_at', 
        'end_at', 
        'date', 
        'start_time', 
        'end_time', 
        'color', 
        'zoom_id', 
        'status', 
        'active', 
        'archive', 
        'employee_id'
    ];
    // protected $hidden = [];
    // protected $dates = [];
     protected $appends = [
        'conference_status',
        'start_url',
        'join_url'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function getEmployeeNearestMeeting($employee_id)
    {
        $dateTime   = Carbon::now();

        $meeting_tag    =   UsersMeeting::where('employee_id', $employee_id)->get();
        $meetings       =   Meeting::with(['employee', 'users_meeting'])
                                ->whereIn('id', $meeting_tag->pluck('meeting_id'))
                                ->where('start_at', '>=', $dateTime)
                                ->where('end_at', '>', $dateTime)
                                ->orderBy('start_at', 'ASC')
                                ->orderBy('end_at', 'ASC')
                                ->notArchive()
                                ->active()
                                ->first();
        return $meetings;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function users_meeting() {
        return $this->belongsToMany(Employee::class, 'users_meeting', 'meeting_id', 'employee_id');
    }

    public function students_meeting() {
        return $this->belongsToMany(Student::class, 'students_meeting', 'meeting_id', 'student_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /*** Get the meeting's meetingable (Zoom Meeting). ***/
    public function zoomMeeting()
    {
        return $this->morphOne(ZoomMeeting::class, 'meetingable');
    }

    /*** Get the meeting's meetingable (Zoom Recordings). ***/
    public function zoomRecordings()
    {
        return $this->morphMany(ZoomRecording::class, 'meetingable');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActive ($query)
    {
        return $query->where('active', 1);
    }
    public function scopeArchive ($query)
    {
        return $query->where('archive', 1);
    }
    public function scopeNotArchive ($query)
    {
        return $query->where('archive', 0);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    // public function getConferenceStatusAttribute ()
    // {
    //     $meetingId      = $this->code;
    //     $password       = "teacher-" . $this->code;
    //     $video_con_info = BBB::getConferenceStatus($meetingId, $password);

    //     $data           = gettype($video_con_info) == "object" ? $video_con_info : null;

    //     if($data)
    //     {
    //         if($data->original->returncode == "SUCCESS"){
    //             return 1;
    //         }
    //     }
    //     return 0;
    // }

    public function getConferenceStatusAttribute()
    {
        if($this->status == 'started') {
            return 1;
        }
        return 0;
    }

    public function getStartUrlAttribute()
    {
        $zoom_meeting = ZoomMeeting::where('zoom_id', $this->zoom_id)->first();
        return $zoom_meeting ? $zoom_meeting->start_url : null;
    }

    public function getJoinUrlAttribute()
    {
        $zoom_meeting = ZoomMeeting::where('zoom_id', $this->zoom_id)->first();
        return $zoom_meeting ? $zoom_meeting->join_url : null;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
