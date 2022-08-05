<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnlinePost extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'online_posts';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
                            'content',
                            'files',
                            'file_name',
                            'online_class_id',
                            'class_topic_id',
                            'class_module_id',
                            'teacher_id',
                            'studentnumber',
                            'likes',
                            'subject_id', 
                            'section_id',
                            'school_year_id',
                            'term_type',
                            'active',
                            'archive',
                            'start_at',
                            'end_at' 
                        ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
        'files'     => 'array',
        'file_name' => 'array',
        'likes'     =>  'array'
    ];
    protected $appends = [
                            'class_code',
                            'subject_code',
                            'subject_name',
                            'total_likes',
                            'poster_name',
                            'poster_photo',
                            'student_likes',
                            'employee_likes',
                            'files_with_extension'
                        ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
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
                \Storage::disk('do_spaces')->put($filename);
                
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
                    // $file_path          = $file->storeAs($destination_path, $new_file_name, 'do_spaces');
                    $file_path          = $file->storePubliclyAs('/uploads/'.$destination_path, $new_file_name, 'do_spaces');
                    // 3. Add the public path to the database
                    $attribute_value[]  = [
                                            'filepath' => $file_path,
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
    public function comments()
    {
        return $this->hasMany(OnlineComment::class);
    }

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

    public function student() {
        return $this->belongsTo("App\Models\Student", "studentnumber", 'studentnumber');
    }

    public function schoolYear ()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function class ()
    {
        return $this->belongsTo(OnlineClass::class, 'online_class_id');
    }

    public function topic ()
    {
        return $this->belongsTo(OnlineClassTopic::class, 'class_topic_id');
    }

    public function module ()
    {
        return $this->belongsTo(OnlineClassModule::class, 'class_module_id');
    }

    public function classQuiz ()
    {
        return $this->hasOne(OnlineClassQuiz::class, 'online_post_id');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeIsTeacher ($query)
    {
        return $query->where('teacher_id', '!=', null);
    }
    public function scopeIsStudent ($query)
    {
        return $query->where('studentnumber', '!=', null);
    }
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
    public function getClassCodeAttribute()
    {
        $class = $this->class;

        return $class ? $class->code : '-';
    }
    
    public function getPosterNameAttribute() 
    {
        $teacher = $this->teacher()->first();
        $student = $this->student()->first();

        if(!$teacher){
            if($student){
                return $student->firstname.' '.$student->lastname;
            }
        }
        else{
            return $teacher->firstname.' '.$teacher->lastname;
        }
        return '-';
    }
    public function getPosterPhotoAttribute() 
    {
        $teacher = $this->teacher()->first();
        $student = $this->student()->first();

        if(!$teacher){
            if($student){
                if($student->photo){
                    return $student->photo;
                }
            }
        }
        else{
            if($teacher->photo){
                return $teacher->photo;
            }
        }
        return 'images/headshot-default.png';
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

    public function getFilesWithExtensionAttribute()
    {
        $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];
        $files = [];
        $post_files = json_decode($this->attributes['files']);
        // dd($post_files);
        if(count($post_files) > 0){

            foreach ($post_files as $key => $file) {
                $file_extension = pathinfo($file->filepath, PATHINFO_EXTENSION);
                if ( in_array($file_extension, $imageExtensions) )
                {
                    $files[] = [
                        'filepath' => $file->filepath,
                        'filename' => $file->filename,
                        'extension' => 'img'
                    ];  
                }
                else {
                    $files[] = [
                        'filepath' => $file->filepath,
                        'filename' => $file->filename,
                        'extension' => $file_extension
                    ];  
                }
            }
        }
        return $files;
    }

    public function getStudentLikesAttribute()
    {
        $studentLikes      =   collect($this->likes)->where('type', 'student')->pluck('student_id')->toArray();
        return $studentLikes;
    }

    public function getEmployeeLikesAttribute()
    {
        $employeeLikes      =   collect($this->likes)->where('type', 'employee')->pluck('employee_id')->toArray();
        return $employeeLikes;
    }

    public function getTotalLikesAttribute()
    {
        $likes = collect($this->likes);
        return count($likes);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    // */
    public function setFilesAttribute($value)
    {
        $attribute_name     = 'files';
        $request            = \Request::instance();
        $disk               = "uploads";
        $schoolYear         =   SchoolYear::active()->first();

        $class              = OnlineClass::where('id', $this->online_class_id)->first();
        if($this->studentnumber){
            $destination_path   =  "Online Class(".$schoolYear->schoolYear.")/".$class->code.'/student/'.$this->studentnumber;
            $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
        }
        else if(backpack_auth()->user()->employee_id){
            $employee = Employee::where('id', backpack_auth()->user()->employee_id)->first()->employee_id;
            $destination_path   =  "Online Class(".$schoolYear->schoolYear.")/".$class->code.'/teacher/'.$employee;
            $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
        }
        
    }
}
