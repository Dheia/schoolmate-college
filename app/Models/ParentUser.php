<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\ParentCredential;

class ParentUser extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'parent_users';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'photo',
        'gender',
        'birthdate',
        'citizenship',
        'street_number',
        'barangay',
        'city_municipality',
        'province',
        'country',
        'mobile',
        'telephone',
        'email'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['fullname'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getVerifiedWithBadge() 
    {
        if($this->verified)
        {
            return "<span class='badge badge-success'>Verified</span>";
        }
        return "<span class='badge badge-default'>Unverified</span>";
    }

    public function getStatusWithBadge() 
    {
        $credential = $this->parentCredential;

        if(! $this->verified) {
            return "<span class='badge badge-default'>Unverified</span>";
        }

        if(! $credential) {
            return "<span class='badge badge-default'>Inactive</span>";
        }

        if($credential->active){
            return "<span class='badge badge-success'>Active</span>";
        }
        
        return "<span class='badge badge-default'>Inactive</span>";
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function parentCredential ()
    {
        return $this->hasOne(ParentCredential::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    public function getHasParentCredentialAttribute ()
    {
        $isExist = ParentCredential::where('parent_user_id', $this->id)->exists();
        return $isExist ?? false;
    }
    public function getPhotoAttribute(){
        if(isset($this->attributes['photo'])) {
            if($this->attributes['photo'] !== null){
                $photo = $this->attributes['photo'];
                if(\Storage::disk('public')->exists($photo)) {
                    $photo = 'storage/'.$this->attributes['photo'];
                    return $photo;
                } else {
                    return 'images/headshot-default.png';
                }
            } else {
                return 'images/headshot-default.png';
            }
        } else {
            return 'images/headshot-default.png';
        }
    }

    public function getFullnameAttribute ()
    {
        return $this->firstname . ' ' . $this->middlename . ' ' . $this->lastname;
    }

    public function getResidentialAddressAttribute ()
    {
        return $this->street_number . ' ' . $this->barangay . ' ' . $this->city_municipality . ' ' . $this->province . ' ' . $this->country;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setPhotoAttribute($value)
    {
        $attribute_name = 'photo';
        $disk = 'public';
        $destination_path = 'uploads/parents/';

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
}
