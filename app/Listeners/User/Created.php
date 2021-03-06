<?php

namespace App\Listeners\User;

use App\Events\User\Created as UserEvent;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\User\Created as UserNotificationCreated;

class Created implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param UserEvent $event
     * @return void
     */
    public function handle(UserEvent $event)
    {
        $users = User::role(['Supper Admin'])->get();
        Notification::send($users, new UserNotificationCreated($event->model));
    }

    /**
     * Handle a job failure.
     *
     * @param UserEvent $event
     * @param  \Exception $exception
     * @return void
     */
    public function failed(UserEvent $event, $exception)
    {
        \Log::info($event);
    }
}
