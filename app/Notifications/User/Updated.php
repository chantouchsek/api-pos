<?php

namespace App\Notifications\User;

use Carbon\Carbon;
use NotificationChannels\OneSignal\OneSignalMessage;

class Updated extends UserNotification
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
            'body' => "User number: #{$this->user->name} was updated at {$this->user->updated_at}",
            'notify_type' => 'user',
            'notify_id' => $this->user->uuid,
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
            ->body("User ID: #{$this->user->staff_id} was updated at {$this->user->created_at} by {$notifiable->name}")
            ->setData('notify_type', 'user')
            ->setData('created_at', $timestamp)
            ->setData('updated_at', $timestamp)
            ->setData('notify_id', $this->user->uuid);
    }
}
