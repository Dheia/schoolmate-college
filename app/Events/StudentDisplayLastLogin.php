<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

// Models
use App\Models\Rfid;
use App\Student;

class StudentDisplayLastLogin implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $rfid;
    private $turnstile;
    private $in;
    private $time;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($rfid, $turnstile, $in)
    {
        $this->rfid      = $rfid;
        $this->turnstile = $turnstile;
        $this->in        = $in;
    }


    public function broadcastOn()
    {
        return new Channel('student-channel');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastWith ()
    {
        $rfidEntity = Rfid::where('rfid', $this->rfid)->first();

        if($rfidEntity) {
            $student = Student::where('studentnumber', $rfidEntity->studentnumber)
                                ->select('id', 'firstname', 'middlename', 'lastname', 'studentnumber', 'photo')
                                ->first();

            $now = now()->format('h:i:s a');

            $this->in === 'in' ? $this->time =  $now : '';
            $this->in === 'out' ? $this->time = $now : '';

            return  [
                        'data' => $student ?? null,
                        'tap' => [
                                    'type' => $this->in,
                                    'time' => $this->time,
                        ]
                    ];
        }

        return ['data' => null];
    }
}
