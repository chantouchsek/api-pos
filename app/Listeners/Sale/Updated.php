<?php

namespace App\Listeners\Sale;

use App\Events\Sale\Updated as SaleUpdate;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Updated implements ShouldQueue
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
     * @param  SaleUpdate $event
     * @return void
     */
    public function handle(SaleUpdate $event)
    {
        //
    }
}
