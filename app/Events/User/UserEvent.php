<?php

namespace App\Events\User;

use App\Events\BaseEvent as Event;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;

abstract class UserEvent extends Event
{

    /**
     * @var Model The model that has been updated.
     */
    public $model;

    /**
     * Create a new event instance.
     *
     * @param User $user The user that has been updated.
     */
    public function __construct(User $user)
    {
        $this->model = $user;
        $this->transformer = new UserTransformer();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['user'];
    }
}
