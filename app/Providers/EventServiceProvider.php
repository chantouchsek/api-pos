<?php

namespace App\Providers;

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
        // Users
        'App\Events\User\Created' => [
            'App\Listeners\User\Created'
        ],
        'App\Events\User\Updated' => [
            'App\Listeners\User\Updated'
        ],
        'App\Events\User\Deleted' => [
            'App\Listeners\User\Deleted'
        ],
        // Sales
        'App\Events\Sale\Created' => [
            'App\Listeners\Sale\Created'
        ],
        'App\Events\Sale\Updated' => [
            'App\Listeners\Sale\Updated'
        ],
        'App\Events\Sale\Deleted' => [
            'App\Listeners\Sale\Deleted'
        ],
        'App\Events\Mail\Sent' => [
            'App\Listeners\Mail\Sent'
        ],
        'App\Events\Mail\Queued' => [
            'App\Listeners\Mail\Queued'
        ]
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
