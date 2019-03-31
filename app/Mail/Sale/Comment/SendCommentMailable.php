<?php

namespace App\Mail\Sale\Comment;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Sale\Comment\Created as CommentCreatedEvent;
use Illuminate\Contracts\Queue\Factory as Queue;

class SendCommentMailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var Sale
     */
    public $sale;
    public $comment;

    /**
     * Create a new message instance.
     *
     * @param Sale $sale
     * @param $comment
     */
    public function __construct(Sale $sale, $comment)
    {
        $this->sale = $sale;
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->sale->user->email)
            ->subject("Queuer: Welcome to queuer")
            ->markdown('emails.welcome');
    }

    /**
     * Queue the email
     * @param Queue $queue
     * @return mixed
     */
    public function queue(Queue $queue)
    {
        broadcast(new CommentCreatedEvent($this->sale, $this->comment))->toOthers();

        return parent::queue($queue);
    }
}
