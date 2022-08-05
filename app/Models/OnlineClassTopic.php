<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnlineClassTopic extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'online_class_topics';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'title',
        'description',
        'files',
        'online_course_id',
        'online_class_module_id',
        'active',
        'archive'
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
                                            'filename' => $filename
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
    public function course ()
    {
        return $this->belongsTo(OnlineCourse::class, 'online_course_id');
    }
    public function module ()
    {
        return $this->belongsTo(OnlineClassModule::class, 'online_class_module_id');
    }
    public function pages ()
    {
        return $this->hasMany(OnlineTopicPage::class, 'online_class_topic_id');
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    // public function setFilesAttribute($value)
    // {
    //     $attribute_name     =   'files';
    //     $request            =   \Request::instance();
    //     $disk               =   "uploads";

    //     $course             =   OnlineCourse::where('id', $this->online_course_id)->first();
    //     $module             =   OnlineClassModule::where('id', $this->online_class_module_id)->first();
    //     $employee           =   Employee::where('id', backpack_auth()->user()->employee_id)->first()->employee_id;
    //     $schoolYear         =   SchoolYear::active()->first();

    //     $destination_path   =   "Online Class(".$schoolYear->schoolYear.")/Courses/".$course->code.'/'.$module->title.'/'.$this->title;
    //     $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path); 
    // }


}
