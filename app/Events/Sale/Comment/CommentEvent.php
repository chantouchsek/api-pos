<?php

namespace App\Events\Sale\Comment;

use App\Events\BaseEvent as Event;
use App\Models\Sale;
use App\Transformers\CommentTransformer;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;

abstract class CommentEvent extends Event implements ShouldQueue, ShouldBroadcast
{

    /**
     * @var Model The model that has been updated.
     */
    public $model;

    public $comment;

    /**
     * Create a new event instance.
     *
     * @param Sale $sale The sale that has been updated.
     * @param $comment
     */
    public function __construct(Sale $sale, $comment)
    {
        $this->model = $sale;
        $this->transformer = new CommentTransformer();
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PrivateChannel
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.Sale.' . $this->model->id);
    }
}
