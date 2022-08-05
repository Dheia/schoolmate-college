<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Setting;

class SendMailableKioskAfterEnrolled extends Mailable
{
    use Queueable, SerializesModels;

    private $enrollment;
    private $tuition;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($enrollment, $tuition, $kiosk, $show_tuition)
    {
        $this->enrollment   = $enrollment;
        $this->tuition      = $tuition;
        $this->kiosk        = $kiosk;
        $this->show_tuition = $show_tuition;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->school_logo       = $schoolLogo       = Setting::where('key', 'schoollogo')->first()->value;
        $this->school_name       = $schoolName       = Setting::where('key', 'schoolname')->first()->value;
        $this->school_address    = $schoolAddress    = Setting::where('key', 'schooladdress')->first()->value;
        $this->schoolAbbr        = $schoolAbbr       = Setting::where('key', 'schoolabbr')->first()->value;

        $data = [
            'kiosk'             => $this->kiosk, 
            'tuition'           => $this->tuition, 
            'enrollment'        => $this->enrollment, 
            'show_tuition'      => $this->show_tuition,
            'school_logo'       => $this->school_logo,
            'school_name'       => $this->school_name,
            'school_address'    => $this->school_address,
            'schoolAbbr'        => $this->schoolAbbr

        ];
        return $this->subject('SchoolMATE ' . $this->$schoolAbbr .' - Kiosk Online Enrollment Registration')
                    ->view('kiosk.old.mail_enroll_v2')
                    ->with($data);
    }
}
