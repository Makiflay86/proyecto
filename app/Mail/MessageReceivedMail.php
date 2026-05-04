<?php

namespace App\Mail;

use App\Models\Message;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MessageReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Message $msg,
        public readonly User $recipient,
        public readonly User $sender,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Nuevo mensaje sobre el producto: {$this->msg->product->nombre}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.message-received',
        );
    }
}
