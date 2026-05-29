<?php

namespace App\Mail;

use App\Models\Endpoint;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EndpointRecoveredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Endpoint $endpoint)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->endpoint->site->domain . ' - Voltou ao Ar',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'email.endpoint_recovered',
        );
    }
}