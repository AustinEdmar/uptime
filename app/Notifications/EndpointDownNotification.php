<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Endpoint;

class EndpointDownNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Endpoint $endpoint)
    {
        //
    }

    public function via($notifiable): array
{
    return ['mail'];
}

public function toMail($notifiable): MailMessage
{
    \Log::info('toMail executado', [
        'notifiable' => $notifiable,
        'endpoint' => $this->endpoint->id
    ]);
    
    return (new MailMessage)
        ->subject($this->endpoint->site->name . ' Endpoint Down')
        ->markdown('email.endpoint_down', [
            'endpoint' => $this->endpoint,
        ]);
}
  
}
