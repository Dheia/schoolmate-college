<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Mail;
use Log;

use App\Models\KioskEnrollment;
use App\Mail\SendMailableKioskNewStudent;

class MailNewStudentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The podcast instance.
     *
     * @var \App\Models\KioskEnrollment
     */
    protected $kioskEnrollment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(KioskEnrollment $kioskEnrollment)
    {
        $this->kioskEnrollment = $kioskEnrollment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->kioskEnrollment->email)->send(new SendMailableKioskNewStudent($this->kioskEnrollment));
        } catch (Exception $e) {
            Log::error('Kiosk New Student Sending Mail Error - Kiosk Enrollment ID: ' . $this->kioskEnrollment->id, $e);
        }
    }
}
