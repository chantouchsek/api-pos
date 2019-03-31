<?php

namespace App\Notifications\Sale;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\OneSignal\OneSignalChannel;

abstract class SaleNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $sale;

    /**
     * Create a new notification instance.
     *
     * @param Sale $sale
     */
    public function __construct(Sale $sale)
    {
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
