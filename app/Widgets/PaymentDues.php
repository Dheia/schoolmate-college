<?php

namespace App\Widgets;

use App\Models\Enrollment;
use App\Models\SchoolYear;
use Arrilot\Widgets\AbstractWidget;

class PaymentDues extends AbstractWidget
{

    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'count' => 5
    ];

    public function placeholder()
    {
        return '<center>Loading...</center>';
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //
        $schoolyear = SchoolYear::where('isActive',1)->first();
        $schoolyear = $schoolyear !== null ? $schoolyear : null;

        if($schoolyear !== null) {
            $school_year = $schoolyear->schoolYear; 
            $enrolment = Enrollment::where('school_year_id', $schoolyear->id)
                                        ->where('deleted_at', null)
                                        ->where('is_applicant', 0)
                                        ->with('student')
                                        ->get();
        }

        return view('widgets.payment_dues', [
            'config'        =>   $this->config,
            'enrolment'     =>   $enrolment
            ]
        );
    }
}
