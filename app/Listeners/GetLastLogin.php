<?php

namespace App\Listeners;

use App\Events\DisplayLastLogin;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GetLastLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DisplayLastLogin  $event
     * @return void
     */
    public function handle(DisplayLastLogin $event)
    {
        //
        
    }
}
