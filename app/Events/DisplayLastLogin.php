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
use App\Models\StudentSmsTagging as SMS;
use App\Models\SmsLog;
use App\Models\Employee;
use Carbon\Carbon;

use App\Http\Controllers\GlobeMessaging;
use App\Http\Controllers\SmartMessaging;

use Illuminate\Support\Facades\Redis;

use Illuminate\Support\Facades\Log;

class DisplayLastLogin implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, GlobeMessaging, SmartMessaging;

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
        return new Channel('display-channel');
    }

    public function broadcastWith()
    {
        $data = [];
        $isStudent = null;

        $rfid = Rfid::where('rfid', $this->rfid)->orderBy('id','desc')->first();

        if ($rfid) {

            $student        = Student::where('studentnumber',$rfid->studentnumber)->get();
            $topsix         = TurnstileLog::where('is_logged_in',1)->orderBy('id', 'desc')->take(12)->get();
            $studentArray   = [];
            $employeeArray  = [];
            $cntStudent     = 0;
            $cntEmployee    = 0;

            foreach ($topsix as $value) {
                $rfid = Rfid::where('rfid',$value->rfid)->first();
                if($rfid){
                    $student  = Student::where('studentnumber',$rfid->studentnumber)->first();
                    $employee = Employee::where('employee_id', $rfid->studentnumber)->first();
                }
                
                if ($student) {

                    $isStudent = True;
                    if ($cntStudent < 6) {
                        if ($student->firstname) {
                            $arraymember = [
                                "rfid" => $rfid,
                                "firstname"     => ucfirst($student->firstname),
                                "middlename"    => ucfirst($student->middlename),
                                "lastname"      => ucfirst($student->lastname),
                                "timein"        => date("g:i a", strtotime($value->timein)),
                                "image"         => $student->photo ? '/storage/'. $student->photo : 'images/headshot-default.png',
                                "level"         => $student->current_level,
                                "is_student"    => True
                            ];
                        }
                        array_push($studentArray, $arraymember);
                        $cntStudent += 1;
                    }
                }

                if ($employee) {
                    $isStudent = false;
                    if ($cntEmployee <= 4) {
                        if ($employee->firstname) {
                            $arraymember = [
                                "rfid"          => $rfid,
                                "firstname"     => ucfirst($employee->firstname),
                                "middlename"    => ucfirst($employee->middlename),
                                "lastname"      => ucfirst($employee->lastname),
                                "timein"        => date("g:i a", strtotime($value->timein)),
                                "image"         => $employee->photo ? '/storage/'.$employee->photo : 'images/headshot-default.png',
                                "level"         => $employee->year,
                                "is_student"    => False
                            ];
                        }
                        array_push($employeeArray, $arraymember);
                        $cntStudent += 1;
                    }
                }
            }

            $data["student"]        = $studentArray;
            $data["employee"]       = $employeeArray;
            $data["marketingvideo"] = \Config::get('settings.rfidmarketingvideo');
            $data["schoollogo"]     = \Config::get('settings.schoollogo');
            $rfidLast               = Rfid::where('rfid', $this->rfid)->orderBy('id','desc')->first();

            if ($rfidLast->user_type == "student") {
                $lastStudentLogTimein = TurnstileLog::with('rfid')
                            ->where('rfid', $this->rfid)
                            ->whereDate('created_at', \Carbon\Carbon::parse(now()))
                            ->orderby('id', 'asc')->first();
                
                if ($this->in === "out") {
                    $lastUserLog = TurnstileLog::with('rfid')
                            ->where('rfid', $this->rfid)
                            ->whereDate('created_at', \Carbon\Carbon::parse(now()))
                            ->orderby('id', 'desc')->first();

                    $timeout = $lastUserLog != null ? date("g:i a", strtotime($lastUserLog->timeout)) : "";
                } 
                else { $timeout = ""; }

                $rfidStudentnumber  = Rfid::where('rfid', $this->rfid)->first();
                $lastStudent        = Student::where('studentnumber', $rfidStudentnumber->studentnumber)->first();
                    
                if ($lastStudent) {
                    $data["lastlogin"] = [
                        "rfid" => $rfidLast->rfid,
                        "firstname" => ucfirst($lastStudent->firstname),
                        "middlename" => ucfirst($lastStudent->middlename),
                        "lastname" => ucfirst($lastStudent->lastname),
                        "timein" => $lastStudentLogTimein ? date("g:i a", strtotime($lastStudentLogTimein->timein)) : "",
                        // "timeout" => $lastUserLog ? date("g:i a", strtotime($lastUserLog->timeout)) : "",
                        "timeout" => $timeout,
                        "image" => '/storage/'.$lastStudent->photo,
                        "level" => $lastStudent->current_level,
                        "is_student" => True
                    ];
                }
            }

            if($rfidLast->user_type == "employee") {
                $lastUserLogTimein = TurnstileLog::with('rfid')
                            ->where('rfid', $this->rfid)
                            ->whereDate('created_at', \Carbon\Carbon::parse(now()))
                            ->orderby('id', 'asc')->first();

                if($this->in === "out"){
                    $lastUserLog = TurnstileLog::with('rfid')
                            ->where('rfid', $this->rfid)
                            ->whereDate('created_at', \Carbon\Carbon::parse(now()))
                            ->orderby('id', 'desc')->first();

                    $timeout = $lastUserLog != null ? date("g:i a", strtotime($lastUserLog->timeout)) : "";
                }else{
                    $timeout = "";
                }
                
                $rfidEmployeenumber = Rfid::where('rfid', $this->rfid)->first();
                $lastEmployee       = Employee::where('employee_id', $rfidEmployeenumber->studentnumber)->first();
                
                if($lastEmployee) {
                    $data["lastlogin"] = [
                        "rfid" => $rfidLast->rfid,
                        "firstname" => ucfirst($lastEmployee->firstname) ?? "",
                        "middlename" => ucfirst($lastEmployee->middlename)?? "",
                        "lastname" => ucfirst($lastEmployee->lastname)?? "",
                        "timein" => $lastUserLogTimein ? date("g:i a", strtotime($lastUserLogTimein->timein)) : null,
                        "timeout" => $timeout ?? "",
                        "image" => '/storage/'.$lastEmployee->photo ?? "",
                        "is_student" => True
                    ];
                }
            }
        }

        return $data;
    }
}