<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentSubmittedAssignment extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'student_submitted_assignments';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'assignment_id',
        'student_id',
        'answer',
        'files',
        'status',
        'rubrics',
        'score',
        'archive',
        'active'
    ];
    protected $casts = [
        'files'     => 'array'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function uploadFilesToDisk($value, $attribute_name, $disk, $destination_path)
    {
        $request            =   \Request::instance();
        $attribute_value    = (array) $this->{$attribute_name};

        // If the file is uploaded, delete file from disk
        // if($request->hasFile($attribute_name) && $this->{$attribute_name} && $this->attribute_name != null && $request->file($attribute_name)->isValid())
        // {
        //     \Storage::disk($disk)->delete($this->{$attribute_name}->file_o);
        //     $this->attributes[$attribute_name] = [];
        // }
        if ($request->hasFile($attribute_name) && $request->file($attribute_name)->isValid()) {
            $file = $request->file($attribute_name);
            // 1. Generate a new file name
            $filename      = $file->getClientOriginalName();
            $new_file_name = md5($file.time()).'.'.$file->getClientOriginalExtension();
            // 2. Move the new file to the correct path
            $file_path          = $file->storeAs($destination_path, $new_file_name, $disk);
            // 3. Add the public path to the database
            $attribute_value[]  = [
                                    'filepath' => 'uploads/'.$file_path,
                                    'filename' => $filename
                                ];
        }
        $this->attributes[$attribute_name] = json_encode($attribute_value);
    }

    public function uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path)
    {
        $request            = \Request::instance();
        $attribute_value    = (array) $this->{$attribute_name};
        $files_to_clear     = $request->get('clear_'.$attribute_name);
        // if a file has been marked for removal,
        // delete it from the disk and from the db
        if ($files_to_clear) {
            $attribute_value = (array) $this->{$attribute_name};
            foreach ($files_to_clear as $key => $filename) {
                \Storage::disk($disk)->delete($filename);
                $attribute_value = array_where($attribute_value, function ($value, $key) use ($filename) {
                    return $value != $filename;
                });
            }
        }
        // if a new file is uploaded, store it on disk and its filename in the database
        if ($request->hasFile($attribute_name)) {
            foreach ($request->file($attribute_name) as $file) {
                if ($file->isValid()) {
                    // 1. Generate a new file name
                    $filename      = $file->getClientOriginalName();
                    $new_file_name = md5($file.time()).'.'.$file->getClientOriginalExtension();
                    // 2. Move the new file to the correct path
                    $file_path          = $file->storeAs($destination_path, $new_file_name, $disk);
                    // 3. Add the public path to the database
                    $attribute_value[]  = [
                                            'filepath' => 'uploads/'.$file_path,
                                            'filename' => $filename,
                                            'extension' => $file->getClientOriginalExtension()
                                        ];
                }
            }
        }
        $this->attributes[$attribute_name] = json_encode($attribute_value);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function assignment ()
    {
        return $this->belongsTo(Assignment::class);
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
    public function getStudentScoreAttribute()
    {
        $score   = 0;
        $rubrics    = json_decode($this->rubrics);
        if(!$rubrics)
        {
            return $score;
        }
        if( count($rubrics) > 0 )
        {
            foreach ($rubrics as $key => $rubric) {
                if($rubric->name != "" && $rubric->points != "" && $rubric->score != "")
                {
                    $score += $rubric->score;
                }
            }
            return $score;
        }
        else
        {
            return $score;
        }
    }

    public function getScoreAttribute()
    {
        $assignment = $this->assignment;
        if(!$assignment)
        {
            return '-';
        }
        $assignmentRubrics = json_decode($assignment->rubrics);
        $total   = 0;
        if( count($assignmentRubrics) > 0 )
        {
            foreach ($assignmentRubrics as $key => $assignmentRubric) {
                $total += $assignmentRubric->points;
            }
        }

        $rubrics    = json_decode($this->rubrics);
        if(!$rubrics)
        {
            return 0 . '/' . $total;
        }
        if( count($rubrics) > 0 )
        {
            $score   = 0;
            foreach ($rubrics as $key => $rubric) {
                if($rubric->name != "" && $rubric->points != "" && $rubric->score != "")
                {
                    $score += $rubric->score;
                }
            }
            return $score . '/' . $total;
        }
        else
        {
            return 0 . '/' . $total;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setFilesAttribute($value)
    {
        $student            = auth()->user()->student;
        $schoolYear         = SchoolYear::active()->first();
        $attribute_name     = 'files';
        $request            = \Request::instance();
        $disk               = "uploads";
        $assignment         = Assignment::where('id', $this->assignment_id)->first();
        // $destination_path   = "Online Class(".$schoolYear->schoolYear.")/assignments/students/".$student->studentnumber;

        $destination_path   =  "Online Class(" . $schoolYear->schoolYear . ")/" . $assignment->class_code . '/student/' . $this->studentnumber . 'assignments';

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }
}
