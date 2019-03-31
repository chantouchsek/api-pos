<?php

namespace App\Listeners\Sale\Comment;

use App\Events\Sale\Comment\Created as CommentCreated;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Sale\Comment\Created as CommentNotificationCreated;

class Created implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommentCreated $event
     * @return void
     */
    public function handle(CommentCreated $event)
    {
        $users = User::role(['Supper Admin', 'Admin'])->get();
        Notification::send($users, new CommentNotificationCreated($event->model, $event->comment));
    }
}
