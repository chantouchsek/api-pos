<?php

namespace App\Listeners\Sale;

use App\Events\Sale\Deleted as SaleDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Deleted implements ShouldQueue
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
     * @param  SaleDeleted $event
     * @return void
     */
    public function handle(SaleDeleted $event)
    {
        //
    }
}
