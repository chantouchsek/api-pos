<?php

namespace App\Notifications\Sale\Comment;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\OneSignal\OneSignalChannel;

abstract class CommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $comment;
    /**
     * @var Sale
     */
    public $sale;

    /**
     * Create a new notification instance.
     * @param Sale $sale
     * @param $comment
     */
    public function __construct(Sale $sale, $comment)
    {
        $this->comment = $comment;
        $this->sale = $sale;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast', OneSignalChannel::class];
    }
}
