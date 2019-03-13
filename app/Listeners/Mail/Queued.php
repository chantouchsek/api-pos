<?php

namespace App\Listeners\Mail;

use App\Events\Mail\Queued as EmailQueued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Queued implements ShouldQueue
{
    use InteractsWithQueue;
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
     * @param  EmailQueued $event
     * @return void
     */
    public function handle(EmailQueued $event)
    {
        \Log::info($event->mail);
    }
}
