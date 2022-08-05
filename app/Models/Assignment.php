<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class Assignment extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'assignments';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'online_class_id',
        'type',
        'title',
        'instructions',
        'rubrics',
        'due_date',
        'employee_id',
        'archive',
        'active'
    ];
    // protected $hidden = [];
    protected $dates = ['due_date'];
    protected $appends = [
        'class_name', 
        'class_code',
        'teacher_fullname',
        'status'
    ];

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
    public function class ()
    {
        return $this->belongsTo(OnlineClass::class, 'online_class_id');
    }

    public function employee ()
    {
        return $this->belongsTo(Employee::class);
    }

    public function submittedAssignments()
    {
        return $this->hasMany(StudentSubmittedAssignment::class);
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
    public function getClassNameAttribute()
    {
        $class = $this->class()->first();
        return $class ? $class->name : 'Class not found.';
    }

    public function getClassCodeAttribute()
    {
        $class = $this->class()->first();
        return $class ? $class->code : 'Class not found.';
    }

    public function getTeacherFullnameAttribute()
    {
        $teacher = $this->employee()->first();
        if(! $teacher) {
            return 'Unknow Teacher';
        }
        $prefix = $teacher->prefix ? $teacher->prefix . '. ' : '';
        return $prefix . $teacher->firstname . ' ' . $teacher->lastname;
    }

    public function getTotalPointsAttribute()
    {
        $rubrics = json_decode($this->rubrics);
        $total   = 0;
        if( count($rubrics) > 0 )
        {
            foreach ($rubrics as $key => $rubric) {
                $total += $rubric->points;
            }
        }
        return $total;
    }

    public function getTotalRubricAttribute()
    {
        $rubrics = json_decode($this->rubrics);
        return $rubrics ? count($rubrics) : 0;
    }

    public function getStatusAttribute()
    {
        // return $this->due_date->diffForHumans();
        $status = $this->due_date->diffInDays(Carbon::now());
        if($status <= 0)
        {
            return 'Overdue';
        }
        else if($status == 1)
        {
            return '1 day left';
        }
        return $status . ' days left';
    }

    public function getSubmittedAttribute()
    {
        $class = $this->class()->first();
        if(!$class){ return '0/0'; }
        // Get Students
        $students               = json_decode($class->studentSectionAssignment->students);
        // Get Submitted Assignments
        $submittedAssignments   = $this->submittedAssignments()->get();
        return count($submittedAssignments) . '/' . count($students);
    }

    public function getIsEditableAttribute()
    {
        $submittedAssignments = $this->submittedAssignments()->get();
        if($submittedAssignments)
        {
            if(count($submittedAssignments) > 0)
            {
                return 0;
            }
        }
        return 1;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setRubricsAttribute($value)
    {        
        $rubrics    = json_decode($value);
        $json       = array();
        if( count($rubrics) > 0 )
        {
            foreach ($rubrics as $key => $rubric) {
                if($rubric->name != "" && $rubric->points != "")
                {
                    if(is_int(json_decode($rubric->points)))
                    {
                        array_push($json, $rubric);
                    }
                }
            }
        }
        $this->attributes['rubrics'] = json_encode($json);
    }

}
