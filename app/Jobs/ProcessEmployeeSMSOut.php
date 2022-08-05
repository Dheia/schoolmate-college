<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\SmartMessaging;



class ProcessEmployeeSMSOut implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SmartMessaging;

    private $rfid;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($rfid)
    {
        //
        $this->rfid = $rfid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(env('ENABLE_SMS_TURNSTILE_OUT') == "1"){

            if(env('SMS_PROVIDER') === "SMART") {                
                $sms_success = $this->sendEmployeeSMSOutSmart($this->rfid, config('settings.schoolabbr')??\Config::get('settings.schoolabbr'));
                echo $sms_success;

            }
        }
    }
}
