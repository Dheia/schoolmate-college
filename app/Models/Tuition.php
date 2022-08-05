<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Models\PaymentHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Tuition extends Model
{
    use \Venturecraft\Revisionable\RevisionableTrait;
    use CrudTrait;
    use \Awobaz\Compoships\Compoships;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'tuitions';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'form_name',
        'department_id',
        'grade_level_id',
        'track_id',
        'commitment_payment_id',
        'schoolyear_id',
        'tuition_fees',
        'miscellaneous',
        'activities_fee',
        'other_fees',
        'payment_scheme',
        'active',
        // 'payment_semi_annual',
        // 'payment_quarterly',
        // 'payment_monthly',
    ];
    // protected $hidden = [];
    // protected $dates = [];

    protected $appends = [
                            'total_miscellaneous', 
                            'total_activities',
                            'total_other_fees',
                            'total_payable_upon_enrollment',
                            'total_mandatory_fees_upon_enrollment',
                            'total_payment_scheme',
                            'total_installment',
                            'grand_total',
                        ];
    protected $dates = ['deleted_at'];
    protected static $logAttributes = ['*'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getSystemUserId()
    {
        try {
            if (class_exists($class = '\SleepingOwl\AdminAuth\Facades\AdminAuth')
                || class_exists($class = '\Cartalyst\Sentry\Facades\Laravel\Sentry')
                || class_exists($class = '\Cartalyst\Sentinel\Laravel\Facades\Sentinel')
            ) {
                return ($class::check()) ? $class::getUser()->id : null;
            } elseif (\Auth::check()) {
                return \Auth::user()->getAuthIdentifier();
            } elseif (backpack_auth()->check()) {
                return backpack_auth()->user()->getAuthIdentifier();
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function year_management ()      { return $this->belongsTo(YearManagement::class, 'grade_level_id'); }
    public function level ()                { return $this->hasMany(YearManagement::class, 'level_id'); }
    public function commitment_payment ()   { return $this->belongsTo(CommitmentPayment::class); }
    public function commitment_payment_active ()   { return $this->belongsTo(CommitmentPayment::class)->where('active', 1); }
    public function school_year ()          { return $this->belongsTo(SchoolYear::class, "schoolyear_id"); }
    public function student ()              { return $this->belongsTo(Student::class); }
    public function selectedPayment ()      { return $this->belongsTo("App\SelectedPaymentType", 'grade_level_id'); }
    public function otherPrograms ()        { return $this->belongsToMany("App\SelectedOtherPrograms", 'grade_level_id'); }
    public function department ()           { return $this->belongsTo(Department::class); }
    public function track ()                { return $this->belongsTo(TrackManagement::class); }
    public function enrollments ()          { return $this->hasMany(Enrollment::class); }


    public function getTotalPayableUponEnrollmentAttribute ()
    {
        $tot1 = 0;
        $tot2 = 0;
        $tot3 = 0;
        $tot4 = 0;

        if($this->tuition_fees !== "")
        {
            // $this->setAttribute('tuition_fees', json_decode($this->tuition_fees));
            foreach ($this->tuition_fees as $tuition_fee) {
                if(isset($tuition_fee->payment_type) && $tuition_fee->payment_type == 1) {
                    $tot1 += (float)$tuition_fee->tuition_fees - (float)$tuition_fee->discount;
                } 
                
                if(isset($tuition_fee->payment_type) && $tuition_fee->payment_type == 2) {
                    $tot2 += (float)$tuition_fee->tuition_fees - (float)$tuition_fee->discount;
                }

                if(isset($tuition_fee->payment_type) && $tuition_fee->payment_type == 3) {
                    $tot3 += (float)$tuition_fee->tuition_fees - (float)$tuition_fee->discount;
                } 

                if(isset($tuition_fee->payment_type) && $tuition_fee->payment_type == 4) {
                    $tot4 += (float)$tuition_fee->tuition_fees - (float)$tuition_fee->discount;
                }
            }
        }

        $array = [
                    0 => ['amount' => $tot1, 'payment_type' => 1], 
                    1 => ['amount' => $tot2, 'payment_type' => 2], 
                    2 => ['amount' => $tot3, 'payment_type' => 3], 
                    3 => ['amount' => $tot4, 'payment_type' => 4]
                ];
        return $array;
    }

    public function getTotalMiscellaneousAttribute()
    {
        $miscs = $this->miscellaneous;
        $totalAmount = 0;
        foreach ($miscs as $misc) {
            $totalAmount += (float)$misc->amount;
        }
        return $totalAmount;
    }

    public function getTotalActivitiesAttribute()
    {
        $activities = $this->activities_fee;

        $totalAmount = 0;
        if($activities !== null) {
            if( count($activities) > 0 ) {
                foreach ($activities as $activity) {
                    $totalAmount += (float)$activity->amount;
                }
            }
        }

        return $totalAmount;
    }

    public function getTotalOtherFeesAttribute()
    {
        $other_fees = $this->other_fees;

        $totalAmount = 0;
        if($other_fees !== null) {
            if( count($other_fees) > 0 ) {
                foreach ($other_fees as $activity) {
                    $totalAmount += (float)$activity->amount;
                }
            }
        }

        return $totalAmount;
    }

    public function getTotalMandatoryFeesUponEnrollmentAttribute ()
    {
        $tot1 = 0;
        $tot2 = 0;
        $tot3 = 0;
        $tot4 = 0;
        // dd($this->getTotalPayableUponEnrollmentAttribute());
        foreach ($this->getTotalPayableUponEnrollmentAttribute() as $value) {
            if($value['payment_type'] == 1) {
                $tot1 = $value['amount'] + $this->getTotalMiscellaneousAttribute() + $this->getTotalActivitiesAttribute() + $this->getTotalOtherFeesAttribute();
            } 
            
            if($value['payment_type'] == 2) {
                $tot2 = $value['amount'] + $this->getTotalMiscellaneousAttribute() + $this->getTotalActivitiesAttribute() + $this->getTotalOtherFeesAttribute();
            }

            if($value['payment_type'] == 3) {
                $tot3 = $value['amount'] + $this->getTotalMiscellaneousAttribute() + $this->getTotalActivitiesAttribute() + $this->getTotalOtherFeesAttribute();
            } 

            if($value['payment_type'] == 4) {
                $tot4 = $value['amount'] + $this->getTotalMiscellaneousAttribute() + $this->getTotalActivitiesAttribute() + $this->getTotalOtherFeesAttribute();
            }
        }

        return $array = [
                            0 => ['amount' => $tot1, 'payment_type' => 1], 
                            1 => ['amount' => $tot2, 'payment_type' => 2], 
                            2 => ['amount' => $tot3, 'payment_type' => 3], 
                            3 => ['amount' => $tot4, 'payment_type' => 4]
                        ];
    }


    public function getTotalPaymentSchemeAttribute ()
    {
        // Loop Commitment Payment
        $commitmentPayments = CommitmentPayment::get();

        $ps = [];

        foreach ($commitmentPayments as $key => $commitmentPayment) {
            $ps[] = [
                'amount' => collect($this->payment_scheme)->sum($commitmentPayment->snake . '_amount'),
                'payment_type' => $commitmentPayment->id
            ];
        }

        return $ps;
        // $tot1 = 0;
        $tot2 = 0;
        $tot3 = 0;
        $tot4 = 0;

        if($this->payment_scheme) {
            foreach ($this->payment_scheme as $key => $value) {
                dd($value);
                // $tot1 = 0;
                $tot2 += (float)$value->semi_amount;
                $tot3 += (float)$value->quarterly_amount;
                $tot4 += (float)$value->monthly_amount;
            }
        }

        return $array = [
                            0 => ['amount' => '-', 'payment_type' => 1], 
                            1 => ['amount' => $tot2, 'payment_type' => 2], 
                            2 => ['amount' => $tot3, 'payment_type' => 3], 
                            3 => ['amount' => $tot4, 'payment_type' => 4]
                        ];
    }

    public function getTotalInstallmentAttribute ()
    {
        $tot1 = 0;
        $tot2 = 0;
        $tot3 = 0;
        $tot4 = 0;

        // dd($this->getTotalPaymentSchemeAttribute());

        foreach ($this->getTotalPaymentSchemeAttribute() as $value) {
            $tot2 += $value['payment_type'] === 2 ? (float)$value['amount'] : 0;
            $tot3 += $value['payment_type'] === 3 ? (float)$value['amount'] : 0;
            $tot4 += $value['payment_type'] === 4 ? (float)$value['amount'] : 0;
        }

        return $array = [
                            0 => ['amount' => $tot1, 'payment_type' => 1], 
                            1 => ['amount' => $tot2, 'payment_type' => 2], 
                            2 => ['amount' => $tot3, 'payment_type' => 3], 
                            3 => ['amount' => $tot4, 'payment_type' => 4]
                        ];
    }

    public function getGrandTotalAttribute ()
    {
        // dd($this->getTotalInstallmentAttribute());
        $tot1 = 0;
        $tot2 = 0;
        $tot3 = 0;
        $tot4 = 0;

        foreach ($this->getTotalMandatoryFeesUponEnrollmentAttribute() as $value) {
            $tot1 += $value['payment_type'] === 1 ? (float)$value['amount'] : 0;
            $tot2 += $value['payment_type'] === 2 ? (float)$value['amount'] : 0;
            $tot3 += $value['payment_type'] === 3 ? (float)$value['amount'] : 0;
            $tot4 += $value['payment_type'] === 4 ? (float)$value['amount'] : 0;
        }


        foreach ($this->getTotalInstallmentAttribute() as $value) {
            $tot1 += $value['payment_type'] == 1 ? (float)$value['amount'] : 0;
            $tot2 += $value['payment_type'] == 2 ? (float)$value['amount'] : 0;
            $tot3 += $value['payment_type'] == 3 ? (float)$value['amount'] : 0;
            $tot4 += $value['payment_type'] == 4 ? (float)$value['amount'] : 0;
        }

        return $array = [
                            0 => ['amount' => $tot1, 'payment_type' => 1], 
                            1 => ['amount' => $tot2, 'payment_type' => 2], 
                            2 => ['amount' => $tot3, 'payment_type' => 3], 
                            3 => ['amount' => $tot4, 'payment_type' => 4]
                        ];
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeExclude($query,$value = array()) 
    {
        return $query->select( array_diff( $this->fillable,(array) $value) );
    }

    public function scopeActive($query) 
    {
        return $query->where('active', 1)->get();
    }

    public function scopeTrackCode ($query, $track_code)
    {
        $tuitions = $query->join('track_managements', function ($join) {
            $join->on('tuitions.track_id', 'track_managements.id');
        })->where('track_managements.code', $track_code)->select('tuitions.*');
        // dd($students->get());
        return $tuitions;
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getTuitionFeesAttribute ($value)
    {
        return json_decode($value);
    }

    public function getMiscellaneousAttribute ($value)
    {
        return json_decode($value);
    }
    
    public function getActivitiesFeeAttribute ($value)
    {
        return json_decode($value);
    }
    
    public function getPaymentSchemeAttribute ($value)
    {
        return json_decode($value);
    }

    public function getOtherFeesAttribute ($value)
    {
        return json_decode($value);
    }


    // ENCODED
    public function getTuitionFeesEncodedAttribute ()
    {
        return $this->attributes['tuition_fees'];
    }

    public function getMiscellaneousEncodedAttribute ()
    {
        return $this->attributes['miscellaneous'];
    }
    
    public function getActivitiesFeeEncodedAttribute ()
    {
        return $this->attributes['activities_fee'];
    }
    
    public function getPaymentSchemeEncodedAttribute ()
    {
        return $this->attributes['payment_scheme'];
    }

    public function getOtherFeesEncodedAttribute ()
    {
        return $this->attributes['other_fees'];
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
