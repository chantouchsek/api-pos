<?php

namespace App\Notifications\Sale\Comment;

use Carbon\Carbon;
use NotificationChannels\OneSignal\OneSignalMessage;

class Created extends CommentNotification
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
            'body' => "Comment was created at {$this->comment->created_at} by {$notifiable->name}",
            'notify_type' => 'comment',
            'notify_id' => $this->comment->id,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
            'title' => 'Comment Created'
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
            ->subject("Comment Created")
            ->body("Comment was created at {$this->comment->created_at} by {$notifiable->name}")
            ->icon($notifiable->comment_avatar)
            ->setData('notify_type', 'comment')
            ->setData('created_at', $timestamp)
            ->setData('updated_at', $timestamp)
            ->setData('notify_id', $this->comment->id);
    }
}
