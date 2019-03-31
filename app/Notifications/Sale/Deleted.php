<?php

namespace App\Notifications\Sale;

use Carbon\Carbon;
use NotificationChannels\OneSignal\OneSignalMessage;

class Deleted extends SaleNotification
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
            'body' => "User ID: #{$this->sale->staff_id} was deleted at {$this->sale->created_at} by {$notifiable->name}",
            'notify_type' => 'sale',
            'notify_id' => $this->sale->uuid,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
            'title' => 'User Deleted'
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
            ->subject("User Deleted")
            ->body("User ID: #{$this->sale->staff_id} was deleted at {$this->sale->created_at} by {$notifiable->name}")
            ->setData('notify_type', 'sale')
            ->icon($notifiable->sale_avatar)
            ->setData('created_at', $timestamp)
            ->setData('updated_at', $timestamp)
            ->setData('notify_id', $this->sale->uuid);
    }
}
