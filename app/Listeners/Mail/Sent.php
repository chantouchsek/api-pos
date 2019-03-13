<?php

namespace App\Listeners\Mail;

use App\Events\Mail\Sent as MailSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Sent implements ShouldQueue
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
     * @param  MailSent $event
     * @return void
     */
    public function handle(MailSent $event)
    {
        \Log::info($event->mail);
    }
}
