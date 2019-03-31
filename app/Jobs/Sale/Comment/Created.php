<?php

namespace App\Jobs\Sale\Comment;

use App\Mail\Sale\Comment\SendCommentMailable;
use App\Models\Sale;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class Created implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $comment;
    /**
     * @var Sale
     */
    public $sale;

    /**
     * Create a new job instance.
     *
     * @param Sale $sale
     * @param $comment
     */
    public function __construct(Sale $sale, $comment)
    {
        $this->comment = $comment;
        $this->sale = $sale;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send(new SendCommentMailable($this->sale, $this->comment));
    }

    /**
     * The job failed to process.
     *
     * @param  Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        // Send user notification of failure, etc...
    }
}
