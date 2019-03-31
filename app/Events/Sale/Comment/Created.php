<?php

namespace App\Events\Sale\Comment;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Created extends CommentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @return string
     */
    public function broadcastAs()
    {
        return 'created';
    }
}
