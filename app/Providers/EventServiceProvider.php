<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\DisplayLastLogin' => [
            'App\Listeners\GetLastLogin',
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\LogSuccessfulLogout',
        ],
        'App\Events\PostCrud' => [
            'App\Listeners\GetPost',
        ],
        'App\Events\DisplayComment' => [
            'App\Listeners\GetComment',
        ],
        'App\Events\ReloadEmployeeNotification' => [
            'App\Listeners\GetEmployeeNotification',
        ],
        'App\Events\ReloadStudentNotification' => [
            'App\Listeners\GetStudentNotification',
        ],
        'App\Events\ReloadParentNotification' => [
            'App\Listeners\GetParentNotification',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
