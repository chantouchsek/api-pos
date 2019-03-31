<?php

namespace App\Notifications\Sale;

use Carbon\Carbon;
use NotificationChannels\OneSignal\OneSignalMessage;

class Created extends SaleNotification
{
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $timestamp = Carbon::now()->addSecond()->toDateTimeString();
        return [
            'body' => "Sale ID: #{$this->sale->sale_number} was created at {$this->sale->created_at} by {$notifiable->name}",
            'notify_type' => 'sale',
            'notify_id' => $this->sale->uuid,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
            'title' => 'Sale Created'
        ];
    }

    /**
     * @param $notifiable
     * @return OneSignalMessage
     */
    public function toOneSignal($notifiable)
    {
        $timestamp = Carbon::now()->addSecond()->toDateTimeString();
        return OneSignalMessage::create()
            ->subject("Sale Created")
            ->body("Sale ID: #{$this->sale->sale_number} was created at {$this->sale->created_at} by {$notifiable->name}")
            ->icon($notifiable->sale_avatar)
            ->setData('notify_type', 'sale')
            ->setData('created_at', $timestamp)
            ->setData('updated_at', $timestamp)
            ->setData('notify_id', $this->sale->uuid);
    }
}
