<?php

namespace App\Listeners;

use App\Events\ReloadEmployeeNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GetEmployeeNotification
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
     * @param  ReloadEmployeeNotification  $event
     * @return void
     */
    public function handle(ReloadEmployeeNotification $event)
    {
        //
    }
}
