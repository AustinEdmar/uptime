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





    public function handle(EndpointWentDown $event): void
    {
        // garante relações carregadas
        $event->check->load('endpoint.site');

        $endpoint = $event->check->endpoint;
        $site = $endpoint->site;

        $emails = $site->notification_emails ?? [];

        \Log::info('Listener executou', [
            'endpoint_id' => $endpoint->id,
            'emails' => $emails,
        ]);

        collect($emails)->each(function ($email) use ($endpoint) {
            try {
                \Log::info('Enviando email para: ' . $email);

                // No listener, para debugar:
                \Log::info('Emails do site', [
                    'site_id' => $endpoint->site->id,
                    'emails' => $endpoint->site->notification_emails,
                ]);

                Mail::to($email)->send(
                    new EndpointDownMail($endpoint)
                );

            } catch (\Exception $e) {
                \Log::error('Erro ao enviar email', [
                    'email' => $email,
                    'error' => $e->getMessage(),
                ]);
            }
        });
    }
}
