<?php

namespace App\Listeners\Sale;

use App\Events\Sale\Created as SaleCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Created implements ShouldQueue
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
     * @param  SaleCreated $event
     * @return void
     */
    public function handle(SaleCreated $event)
    {
        //
    }
}
