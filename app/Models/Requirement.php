<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requirement extends Model
{
    use CrudTrait;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'requirements';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'studentnumber',
        'student_id',
        'file_recommendation_form',
        'file_good_moral',
        'file_report_card',
        'file_birth_certificate',
        'file_medical_certificate',
        'file_id_passport',
        'file_guardian1_id',
        'file_guardian2_id',
        'file_guardian1_agreement',
        'file_guardian2_agreement',
        'file_visa',
        'file_alien_certificate',
        'file_ssp',
        'proof_of_payment',
        'uploaded_by'
    ];
    // protected $hidden = [];
    // protected $dates = [];
    protected $dates = ['deleted_at'];

    // protected $casts = [
    //     'file_recommendation_form'  => 'array',
    //     'file_good_moral'           => 'array',
    //     'file_report_card'          => 'array',
    //     'file_birth_certificate'    => 'array',
    //     'file_medical_certificate'  => 'array',
    //     'file_id_passport'          => 'array',
    //     'file_guardian1_id'         => 'array',
    //     'file_guardian2_id'         => 'array',
    //     'file_guardian1_agreement'  => 'array',
    //     'file_guardian2_agreement'  => 'array',
    //     'file_visa'                 => 'array',
    //     'file_alien_certificate'    => 'array',
    //     'file_ssp'                  => 'array',
    //     'proof_of_payment'          => 'array',
    // ];

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

    public function student() 
    { 
        return $this->belongsTo(Student::class, 'studentnumber', 'studentnumber'); 
    }

    public function studentById ()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
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
    public function getFullnameAttribute()
    {
        if($this->studentnumber)
        {
            return $this->student()->first()->full_name;
        }
        else
        {
            return $this->studentById()->first()->full_name;
        }
    }

    // public function getFileRecommendationFormAttribute($value){
    //     if(count(json_decode($value)) > 0){
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }


    // file_good_moral

    // public function getFileGoodMoralAttribute($value){
    //     if(count(json_decode($value)) > 0){
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    // file_report_card

    // public function getFileReportCardAttribute($value){
    //     if(count(json_decode($value)) > 0){
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    // // file_birth_certificate

    // public function getFileBirthCertificateAttribute($value){
    //     // $this->file
    //     return $value;
    //     // if(count(json_decode($value)) > 0){
    //     //     return true;
    //     // } else {
    //     //     return false;
    //     // }
    // }

    // // file_medical_certificate

    // public function getFileMedicalCertificateAttribute($value){
    //     if(count(json_decode($value)) > 0){
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }


    // // file_id_passport

    // public function getFileIdPassportAttribute($value){
    //     if(count(json_decode($value)) > 0){
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }


    // // file_guardian1_id

    // public function getFileGuardian1IdAttribute($value){
    //     if(count(json_decode($value)) > 0){
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }


    // // file_guardian2_id

    // public function getFileGuardian2IdAttribute($value){
    //     if(count(json_decode($value)) > 0){
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    // // file_guardian1_agreement

    // public function getFileGuardian1AgreementAttribute($value){
    //     if(count(json_decode($value)) > 0){
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    // // file_guardian2_agreement

    // public function getFileGuardian2AgreementAttribute($value){
    //     if(count(json_decode($value)) > 0){
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }


    // // file_visa

    // public function getFileVisaAttribute($value){
    //     if(count(json_decode($value)) > 0){
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }



    // // file_alien_certificate

    // public function getFileAlienCertificateAttribute($value){
    //     if(count(json_decode($value)) > 0){
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }


    // // file_ssp

    // public function getFileSspAttribute($value){
    //     if(count(json_decode($value)) > 0){
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    // // other

    // public function getOtherAttribute($value){
    //     if($value !== null){
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }


    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setProofOfPaymentAttribute($value)
    {

        $attribute_name = "proof_of_payment";
        $disk = "do_spaces";
        $destination_path = "uploads/students/requirements/proof_of_payment";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setFileRecommendationFormAttribute($value)
    {

        $attribute_name = "file_recommendation_form";
        $disk = "do_spaces";
        $destination_path = "uploads/students/requirements/recommendation_letter";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setFileGoodMoralAttribute($value)
    {

        $attribute_name   = "file_good_moral";
        $disk             = "do_spaces";
        $destination_path = "uploads/students/requirements/good_moral";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setFileReportCardAttribute($value)
    {

        $attribute_name = "file_report_card";
        $disk = "do_spaces";
        $destination_path = "uploads/students/requirements/report_card";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setFileBirthCertificateAttribute($value)
    {

        $attribute_name = "file_birth_certificate";
        $disk = "do_spaces";
        $destination_path = "uploads/students/requirements/birth_certificate";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setFileIdPassportAttribute($value)
    {

        $attribute_name = "file_id_passport";
        $disk = "do_spaces";
        $destination_path = "uploads/students/requirements/passport";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setFileMedicalCertificateAttribute($value)
    {

        $attribute_name = "file_medical_certificate";
        $disk = "do_spaces";
        $destination_path = "uploads/students/requirements/medical_certificate";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setFileGuardian1IdAttribute($value)
    {

        $attribute_name = "file_guardian1_id";
        $disk = "do_spaces";
        $destination_path = "uploads/students/requirements/guardian_ids";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setFileGuardian2IdAttribute($value)
    {

        $attribute_name = "file_guardian2_id";
        $disk = "do_spaces";
        $destination_path = "uploads/students/requirements/guardian_ids";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setFileGuardian1AgreementAttribute($value)
    {

        $attribute_name = "file_guardian1_agreement";
        $disk = "do_spaces";
        $destination_path = "uploads/students/requirements/guardian_agreement";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setFileGuardian2AgreementAttribute($value)
    {

        $attribute_name = "file_guardian2_agreement";
        $disk = "do_spaces";
        $destination_path = "uploads/students/requirements/guardian_agreement";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setFileVisaAttribute($value)
    {

        $attribute_name = "file_visa";
        $disk = "do_spaces";
        $destination_path = "uploads/students/requirements/visa";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setFileAlienCertificateAttribute($value)
    {

        $attribute_name = "file_alien_certificate";
        $disk = "do_spaces";
        $destination_path = "uploads/students/requirements/alien_certificate";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setFileAlienSspAttribute($value)
    {

        $attribute_name = "file_ssp";
        $disk = "do_spaces";
        $destination_path = "uploads/students/requirements/ssp";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function setOtherAttribute($value)
    {

        $attribute_name = "file_ssp";
        $disk = "do_spaces";
        $destination_path = "uploads/students/requirements/other";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);
    }
    // public static function boot()
    // {
    //     parent::boot();
    //     static::deleting(function($obj) {
    //         if (count((array)$obj->photos)) {
    //             foreach ($obj->photos as $file_path) {
    //                 \Storage::disk('public_folder')->delete($file_path);
    //             }
    //         }
    //     });
    // }
}
