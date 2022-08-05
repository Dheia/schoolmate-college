<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\BBB;

class OnlineClass extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'online_classes';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
                            'code',
                            'name',  
                            'description',
                            'days',
                            'start_time',
                            'end_time',
                            'link_to_quipper',
                            'substitute_teachers',
                            'online_course_id',
                            'teacher_id',
                            'subject_id', 
                            'section_id',
                            'school_year_id',
                            'term_type',
                            'summer',
                            'active',
                            'archive',
                            'color',
                            'zoom_id',
                            'status',
                            'ongoing'   
                        ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
        'days'     => 'array',
        'substitute_teachers' => 'array'
    ];

    protected $appends = [
        'subject_code',
        'subject_name',
        'section_name',
        'section_level_name',
        'level_name',
        'track_name',
        'conference_status',
        'total_student',
        'teacher_fullname',
        'join_url',
        'started_by'
    ];
        
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function getConferenceStatus ($class_code)
    {
        $meetingId = $class_code;
        $password = "teacher-" . $class_code;
        $video_conference_info = BBB::getConferenceStatus($meetingId, $password);
        $data = gettype($video_conference_info) == "object" ? $video_conference_info : null;
        if($data)
        {
            if($data->original->returncode == "SUCCESS"){
                return 1;
            }
        }
        return 0;
    }

    /* GET ONLINE CLASSES */
    public static function getOnlineClasses($student, $school_year)
    {
        $student_sections   =   StudentSectionAssignment::where('school_year_id', $school_year->id)
                                    ->whereJsonContains('students', $student->studentnumber)
                                    ->get();

        $online_classes     =   OnlineClass::where('school_year_id', $school_year->id)
                                    ->whereIn('section_id', $student_sections->pluck('section_id'))
                                    ->active()
                                    ->notArchive()
                                    ->orderBy('id', 'DESC')
                                    ->get();
        return $online_classes;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function section ()
    {
        return $this->belongsTo(SectionManagement::class);
    }

    public function sections ()
    {
        return $this->hasMany(SectionManagement::class);
    }

    public function subject ()
    {
        return $this->belongsTo(SubjectManagement::class);
    }

    public function teacher ()
    {
        return $this->belongsTo(Employee::class);
    }

    public function schoolYear ()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function course ()
    {
        return $this->belongsTo(OnlineCourse::class, 'online_course_id');
    }

    public function modules ()
    {
        return $this->hasMany(OnlineClassModule::class);
    }

    public function posts ()
    {
        return $this->hasMany(OnlinePost::class);
    }

    public function studentSectionAssignment ()
    {
        return $this->belongsTo(StudentSectionAssignment::class, 'section_id', 'section_id');
    }

    public function activeStudentSectionAssignment ()
    {
        $studentSectionAssignment = $this->studentSectionAssignment()
                                        ->where('school_year_id', SchoolYear::active()->first()->id)
                                        ->where('term_type', $this->term_type)
                                        ->where('summer', $this->summer);
        return $studentSectionAssignment;
    }

    public function studentSectionAssignments ()
    {
        return $this->hasMany(StudentSectionAssignment::class, 'section_id', 'section_id');
    }

    public function onlineClassQuizzes ()
    {
        return $this->hasMany(OnlineClassQuiz::class, 'online_class_id');
    }

    /*** Get the meeting's meetingable. ***/
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
    public function scopeStudentList($query)
    {
        $this->activeSY = SchoolYear::active()->first()->id;
        return $query->join('student_section_assignments', function ($join) {
                $join->on('student_section_assignments.section_id', '=', 'online_classes.section_id')
                    ->where('student_section_assignments.school_year_id', '=',   $this->activeSY)
                    ->where('online_classes.school_year_id', $this->activeSY);
            });
    }
    public function scopeActiveSchoolYear($query)
    {
        return $query->where('online_classes.school_year_id', SchoolYear::active()->first()->id);
    }
    public function scopeActiveTeacher($query)
    {
        return $query->whereIn('online_classes.teacher_id', Employee::get()->pluck('id'));
    }
    
    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    public function getSectionNameAttribute()
    {
        $section = $this->section()->first();
        return $section ? $section->name : '-';
    }

    public function getSectionLevelNameAttribute ()
    {
        $section = $this->section()->with('level')->first();

        if($section == null) {
            return '-';
        }

        $level = $section->level;

        if($level == null) {
            return $section->name;
        } else {
            return $level->year . ' | ' . $section->name;
        }
    }

    public function getCodeSubjectNameAttribute ()
    {
        $subject = $this->subject()->first();

        if($subject !== null) {
            return $subject->subject_code . ' - ' . $subject->subject_title;
        }
        return '-';
    }

    public function getSubjectNameAttribute ()
    {
        $subject = $this->subject()->first();

        if($subject !== null) {
            return $subject->subject_title;
        }
        return 'Unknown Subject';
    }

    public function getSubjectCodeAttribute ()
    {
        $subject = $this->subject()->first();

        if($subject !== null) {
            return $subject->subject_code;
        }
        return 'Unknown Subject';
    }

    public function getLevelNameAttribute ()
    {
        $section = $this->section()->with('level')->first();

        if($section == null) {
            return '-';
        }

        $level = $section->level;

        if($level == null) {
            return '-';
        } else {
            return $level->year;
        }
    }

    public function getTrackNameAttribute ()
    {
        $section = $this->section()->with('track')->first();

        if($section == null) {
            return '-';
        }

        $track = $section->track;

        if($track == null) {
            return '-';
        } else {
            return $track->code;
        }
    }

    public function getScheduleDayAttribute ()
    {
        $days = $this->days;
        $sched_days = '';
        if(!$days) { return '-'; }
        if(count($days) < 1) { return '-'; }
        foreach ($days as $key => $day) {
            if($day == 'Thursday'){
                $sched_days = $sched_days . substr($day, 0, 2);
            }
            else{
                $sched_days = $sched_days . substr($day, 0, 1);
            }
        }
        return $sched_days;
    }

    public function getScheduleTimeAttribute ()
    {
        $start_time =   $this->start_time;
        $end_time   =   $this->end_time;

        if(!$start_time) { return '-'; }
        if(!$end_time) { return '-'; }

        return date("h:i:a", strtotime($start_time)) . ' - ' . date("h:i:a", strtotime($end_time));
    }

    // public function getConferenceStatusAttribute ()
    // {
    //     $meetingId = $this->code;
    //     $password = "teacher-" . $this->code;
    //     $video_conference_info = BBB::getConferenceStatus($meetingId, $password);
    //     $data = gettype($video_conference_info) == "object" ? $video_conference_info : null;
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

    public function getTotalStudentAttribute()
    {
        $studentSectionAssignment = $this->studentSectionAssignments()->where('school_year_id', $this->school_year_id)
                                        ->where('term_type', $this->term_type)
                                        ->where('summer', $this->summer)
                                        ->first();
        return $studentSectionAssignment ? count(json_decode($studentSectionAssignment->students)) : 0;
    }

    public function getTeacherFullnameAttribute()
    {
        $teacher = $this->teacher()->first();
        if(! $teacher) {
            return 'Unknow Teacher';
        }
        $prefix = $teacher->prefix ? $teacher->prefix . '. ' : '';
        return $prefix . $teacher->firstname . ' ' . $teacher->lastname;
    }

    public function getJoinUrlAttribute()
    {
        $zoom_meeting = ZoomMeeting::where('zoom_id', $this->zoom_id)->first();
        return $zoom_meeting ? $zoom_meeting->join_url : null;
    }

    public function getStartedByAttribute()
    {
        $zoom_meeting = ZoomMeeting::where('zoom_id', $this->zoom_id)->first();
        return $zoom_meeting ? $zoom_meeting->employee_id : null;
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
