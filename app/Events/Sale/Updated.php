<?php

namespace App\Events\Sale;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Updated extends SaleEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @return string
     */
    public function broadcastAs()
    {
        return 'updated';
    }
}
