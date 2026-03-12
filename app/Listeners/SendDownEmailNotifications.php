<?php

namespace App\Listeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Notification;
use App\Notifications\EndpointDownNotification;

use App\Events\EndpointWentDown;

use App\Mail\EndpointDownMail;


class SendDownEmailNotifications
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
     /* public function handle(EndpointWentDown $event): void
    {
        
        collect($event->check->endpoint->site->notification_emails)->each(function ($email) use ($event) {
            Notification::route('email', $email)->notify(new EndpointDownNotification($event->check->endpoint));
        });
    } */

   /*  public function handle(EndpointWentDown $event): void
{
    \Log::info('Listener executou', [
        'check_id' => $event->check->id,
        'endpoint_id' => $event->check->endpoint->id,
    ]);

    $emails = $event->check->endpoint->site->notification_emails;
    
    \Log::info('Emails para notificar', ['emails' => $emails]);

    collect($emails)->each(function ($email) use ($event) {
        \Log::info('Enviando notificação para: ' . $email);
        Notification::route('email', $email)->notify(new EndpointDownNotification($event->check->endpoint));
    });
} */




   /*  public function handle(EndpointWentDown $event): void
    {
        \Log::info('Listener executou', [
            'check_id' => $event->check->id,
            'endpoint_id' => $event->check->endpoint->id,
        ]);
        
        $emails = $event->check->endpoint->site->notification_emails;
        
        \Log::info('Emails para notificar', ['emails' => $emails]);
        
        collect($emails)->each(function ($email) use ($event) {
            \Log::info('Enviando email para: ' . $email);
            Mail::to($email)->send(new EndpointDownMail($event->check->endpoint));
        });
    } */

    
        public function handle(EndpointWentDown $event): void
    {
        Mail::to('teste@exemplo.com')->send(new \App\Mail\Example());
         Mail::to($event->endpoint-site->notification_emails);
       /*  \Log::info('Listener executou', [
            'check_id' => $event->check->id,
            'endpoint_id' => $event->check->endpoint->id,
        ]);
        
        // Carregue o relacionamento site
        $event->check->load('endpoint.site');
        
        $emails = $event->check->endpoint->site->notification_emails;
        
        \Log::info('Emails para notificar', ['emails' => $emails]);
        
        collect($emails)->each(function ($email) use ($event) {
            try {
                \Log::info('Enviando email para: ' . $email);
                Mail::to($email)->send(new EndpointDownMail($event->check->endpoint));
                \Log::info('Email enviado com sucesso para: ' . $email);
            } catch (\Exception $e) {
                \Log::error('Erro ao enviar email', [
                    'email' => $email,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }); */
    }
}
