<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

// MODELS
use App\Models\Employee;
use App\Models\Rfid;

class EmployeeDisplayLastLogin implements ShouldBroadcast
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
        return new Channel('employee-channel');
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
            $employee = Employee::where('employee_id', $rfidEntity->studentnumber)
                                ->select('id', 'firstname', 'middlename', 'lastname', 'employee_id', 'photo', 'position')
                                ->first();

            $now = now()->format('h:i:s a');

            $this->in === 'in' ? $this->time =  $now : '';
            $this->in === 'out' ? $this->time = $now : '';

            return  [
                        'data' => $employee ?? null,
                        'tap' => [
                                    'type' => $this->in,
                                    'time' => $this->time,
                        ]
                    ];
        }

        return ['data' => null];
    }
}
