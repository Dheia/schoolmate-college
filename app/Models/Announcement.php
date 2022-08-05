<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

use Spatie\Activitylog\Traits\LogsActivity;

use Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Announcement extends Model
{
    use CrudTrait;

    protected static function boot() {
        parent::boot();

        Announcement::deleted(function($announcement) {
            $announcement_id = (int)$announcement->id;
            $notifications = DB::table('notifications')->whereJsonContains('data', ['announcement_id' => $announcement_id])->delete();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'announcements';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['message','image','files','start','end', 'user_id', 'audience', 'global'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = ['files' => 'array'];
    protected $appends = ['files_with_link'];
    protected static $logAttributes = ['message','image','start','end','global'];

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

    public function announcementReads() {
        return $this->belongsTo(AnnouncementRead::class);
    }

    public function user ()
    {
        return $this->belongsTo(User::class);
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
    public function getUserFullnameAttribute()
    {
        $user = $this->user()->first();
        return $user->full_name;
    }

    public function getImageAttribute(){
        if(isset($this->attributes['image'])) {
            if($this->attributes['image'] !== null){
                $image = $this->attributes['image'];
                if(\Storage::disk('public')->exists($image)) {
                    $image = 'storage/'.$this->attributes['image'];
                    return $image;
                }
            }
        }

        return null;
    }

    public function getFilesWithLinkAttribute() {
        $files_with_link = '';
        if($this->files) {
            $files = $this->files;
            if(count($files)>0){
                foreach ($files as $key => $file) {
                    // $url = 'https://docs.google.com/viewer?url=' . url($file) . '&embedded=true';
                    // $files_with_link = ($files_with_link ? $files_with_link . '<br>' : '') . '<a href="'.$url.'" target="_blank">'.$file.'</a>';
                    $files_with_link = ($files_with_link ? $files_with_link . '<br>' : '') . '<a href="'.url($file).'" target="_blank" download="'.url($file) .'">'.$file.'</a>';
                }
                // dd($files_with_link);
                return $files_with_link;
            }
        }
        return '-';
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setImageAttribute($value)
    {
        $attribute_name = 'image';
        $disk = 'public';
        $destination_path = 'uploads/announcements/images';

        // if the image was erased
        if ($value==null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (starts_with($value, 'data:image'))
        {
            // 0. Make the image
            $image = \Image::make($value);
            // 1. Generate a filename.
            $filename = md5($value.time()).'.jpg';
            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());
            // 3. Save the path to the database
            $this->attributes[$attribute_name] = $destination_path.'/'.$filename;
        }
    }

    // public function setFilesAttribute($value)
    // {
    //     $attribute_name = "files";
    //     $disk = "public";
    //     $destination_path = "uploads/announcements/files";

    //     $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);

    // // return $this->{$attribute_name}; // uncomment if this is a translatable field
    // }
}
