<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Endpoint;

class EndpointDownMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Endpoint $endpoint)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->endpoint->site->name . ' - Endpoint Down',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'email.endpoint_down',
        );
    }
}