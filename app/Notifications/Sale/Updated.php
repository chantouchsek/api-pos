<?php

namespace App\Notifications\Sale;

use Carbon\Carbon;
use NotificationChannels\OneSignal\OneSignalMessage;

class Updated extends SaleNotification
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
            'body' => "Sale number: #{$this->sale->name} was updated at {$this->sale->updated_at} by $notifiable->name",
            'notify_type' => 'sale',
            'notify_id' => $this->sale->uuid,
            'created_at' => $timestamp,
            'updated_at' => $timestamp
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
            ->subject("User Updated")
            ->body("Sale ID: #$this->sale->sale_number was updated at $this->sale->created_at by $notifiable->name")
            ->setData('notify_type', 'sale')
            ->setData('created_at', $timestamp)
            ->setData('updated_at', $timestamp)
            ->setData('notify_id', $this->sale->uuid);
    }
}
