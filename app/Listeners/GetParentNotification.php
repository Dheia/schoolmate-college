<?php

namespace App\Listeners;

use App\Events\ReloadParentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GetParentNotification
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
     * @param  ReloadParentNotification  $event
     * @return void
     */
    public function handle(ReloadParentNotification $event)
    {
        //
    }
}
