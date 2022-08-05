<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Employee extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'employees';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'employee_id',
        'qr_code',
        'prefix',
        'firstname',
        'middlename',
        'lastname',
        'extname',
        'position',
        // 'tax_status_id',
        // 'date',
        'date_hired',
        'address1',
        'address2',
        'city',
        'province',
        'country',
        'mobile',
        'telephone',
        'domestic_profile',
        'sss',
        'phil_no',
        'pagibig',
        'tinno',
        'age',
        'gender',
        'civil_status',
        'date_of_birth',
        'religion',
        'spouse_name',
        'spouse_age',
        'spouse_occupation',
        'spouse_company',
        'spouse_company_address',
        'fathers_name',
        'fathers_company_name',
        'fathers_company_address',
        'fathers_occupation',
        'fathers_age',
        'fathers_telephone',
        'fathers_mobile',
        'fathers_email',
        'mothers_name',
        'mothers_company_name',
        'mothers_company_address',
        'mothers_occupation',
        'mothers_age',
        'mothers_telephone',
        'mothers_mobile',
        'mothers_email',
        'sibling',
        'emergency_name',
        'emergency_address',
        'emergency_telephone',
        'emergency_mobile',
        'emergency_relation',
        'primary',
        'secondary',
        'tertiary',
        'post_graduate',
        'educational',
        'employment_history',
        'salary',
        'currently_employed',
        'time_start',
        'referral',
        'name_of_referer',
        'relationship',
        'references',
        'medical_condition',
        'past_illness',
        'present_illness',
        'allergies',
        'minor_illness',
        'family_physician',
        'hospital_reference',
        'organ_donor',
        'blood_type',
        'type',
        'photo',
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $appends = ['full_name', 'rfid', 'employment_status', 'is_resigned', 'has_teacher_role']; 
    protected $dates = ['deleted_at'];
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

    public function tax_status()
    {
        return $this->belongsTo('App\Models\TaxStatus');
    }

    public function turnstilelog()
    {
        return $this->hasManyThrough('App\Models\TurnstileLog','App\Models\Rfid','studentnumber','rfid','employee_id','rfid');
    }

    public function rfid ()
    {
        return $this->belongsTo(Rfid::class, 'employee_id', 'studentnumber')->where('user_type', 'employee');
    }

    public function schedule ()
    {
        return $this->hasOne(ScheduleTagging::class, 'employee_id', 'employee_id');
    }
 
    public function employmentStatusHistories ()
    {
        return $this->hasMany(EmploymentStatusHistory::class, 'employee_id');
    }

    public function latestEmploymentStatusHistory()
    {
        return $this->hasOne(EmploymentStatusHistory::class, 'employee_id')->latest('status_change_date');
    }

    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function departments() {
        return $this->belongsToMany(Department::class, 'employee_departments');
    }
    
    public function nonAcademicDepartment() {
        return $this->belongsTo(NonAcademicDepartment::class);
    }
    
    public function nonAcademicDepartments() {
        return $this->belongsToMany(NonAcademicDepartment::class, 'employee_non_academic_departments');
    }

    public function user () 
    {
        return $this->hasOne(User::class, 'employee_id', 'id');
    }

    public function onlineClasses () 
    {
        return $this->hasMany(OnlineClass::class, 'teacher_id', 'id');
    }

    public function comments()
    {
        return $this->morphMany(OnlineComment::class, 'commentable');
    }

    public function onlineClassAttendances()
    {
        return $this->morphMany(OnlineClassAttendance::class, 'user');
    }

    public function goals()
    {
        return $this->morphMany(Goal::class, 'user');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function getFullNameAttribute ()
    {
        return  $this->firstname . ' ' . $this->middlename .  ' ' . $this->lastname;
    }

    public function getBirthdayAttribute ()
    {
        return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->format('F d, Y') : "";
    }
    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getRfidAttribute ()
    {
       $rfid = $this->rfid()->first();
       
       if($rfid !== null) { return $rfid->rfid; }
       return null;
    }

    public function getPhotoAttribute(){

        // if(isset($this->attributes["photo"])){
        //     return "storage/".$this->attributes["photo"];
        // }
        // return "images/headshot-default.png";
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

    public function getEmploymentStatusAttribute ()
    {
        $status = $this->employmentStatusHistories()->latest()->with('employmentStatus')->first();
        if($status) {
            if($status->employmentStatus) {
                return $status->employmentStatus->name;
            }
        }
        return '-';
    }

    public function getTotalYearsOfServiceAttribute() {

        $now = Carbon::now();

        if($this->date_hired){
           
            return Carbon::parse($this->date_hired)->diff($now)->format('%y yrs %m mons and %d days');
        }
    }

    public function getIsResignedAttribute ()
    {
        $status = $this->employmentStatusHistories()->latest()->with('employmentStatus')->first();
        if($status) {
            if($status->employmentStatus) {
                return $status->employmentStatus->resigned;
            }
        }
        return false;
    }

    public function getRenderedQrCodeAttribute() 
    {
        if($this->qr_code) {
            $qrCode = \QrCode::size(150)->generate($this->qr_code);
            return $qrCode;
        } else {
            return null;
        }
    }

    public function getHasTeacherRoleAttribute ()
    {
        $user   =   User::with('roles')->where('employee_id', $this->id)->first();
        return $user;
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setPhotoAttribute($value)
    {
        
        $attribute_name = "photo";
        $disk = "public";
        $destination_path = "uploads/employees/";

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
