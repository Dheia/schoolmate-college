<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\TurnstileLog;
use App\Models\Student;
use App\Models\Rfid;
use Carbon\Carbon;
// use Backpack\Settings\App\Models\Setting;

use Illuminate\Support\Facades\Redis;

class SingleDisplay implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rfid;
    public $turnstile;
    public $in;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($rfid,$turnstile,$in)
    {
        //
        $this->rfid = $rfid;
        $this->turnstile = $turnstile;
        $this->in = $in;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('display-single-channel');
    }

    public function broadcastWith()
    {
        $isStudent = null;
        $data = [];
        $rfid = Rfid::where('rfid', $this->rfid)->orderBy('id','desc')->first();
        // dd($rfid);
        if($rfid){

            $lastStudentLog = TurnstileLog::where('rfid',$this->rfid)->orderby('id', 'desc')->first();
    
            $rfidLast = Rfid::where('rfid',$lastStudentLog->rfid)->first();
        
            $lastStudent = Student::where('studentnumber', $rfidLast->studentnumber)->first();

                if($lastStudent){
                    $data["laststudent"] = [
                        "rfid" => $this->rfid,
                        "firstname" => ucfirst($lastStudent->firstname),
                        "middlename" => ucfirst($lastStudent->middlename),
                        "lastname" => ucfirst($lastStudent->lastname),
                        "timein" => date("g:i a", strtotime($lastStudentLog->timein)),
                        "timeout" => $lastStudentLog->timeout ? date("g:i a", strtotime($lastStudentLog->timeout)) : null,
                        "image" => $lastStudent->photo,
                        "is_student" => True
                    ];
                }
        return $data;
        }
        else {
            return [];
        }




        
    }
        
}