<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer as MailerContract;
use App\Events\Mail\{Queued as EmailQueued, Sent as EmailSent};
use Illuminate\Contracts\Queue\Factory as Queue;

class SendMailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $mail;

    /**
     * Create a new message instance.
     *
     * @param $mail
     */
    public function __construct($mail)
    {

        $this->mail = $mail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Queuer: Welcome to queuer: $this->mail")->markdown('emails.welcome');
    }

    /**
     * Send the mail
     * @param MailerContract $mailer
     */
    public function send(MailerContract $mailer)
    {
        broadcast(new EmailSent($this->mail))->toOthers();

        parent::send($mailer);
    }

    /**
     * Queue the email
     * @param Queue $queue
     * @return mixed
     */
    public function queue(Queue $queue)
    {
        broadcast(new EmailQueued($this->mail))->toOthers();

        return parent::queue($queue);
    }

}
