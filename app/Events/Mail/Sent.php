<?php

namespace App\Events\Mail;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Sent implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mail;

    public function __construct($mail)
    {
        $this->mail = $mail;
    }

    public function broadcastOn()
    {
        return new Channel('email-queue');
    }

    public function broadcastAs()
    {
        return 'sent';
    }
}

