<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;

use App\Events\EndpointRecovered;
use App\Mail\EndpointRecoveredMail;

class SendRecoveredEmailNotifications
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
    public function handle(EndpointRecovered $event): void
    {
        // garante relações carregadas
        $event->check->load('endpoint.site');

        $endpoint = $event->check->endpoint;
        $site = $endpoint->site;

        $emails = $site->notification_emails ?? [];

        \Log::info('Listener de recuperação executou', [
            'endpoint_id' => $endpoint->id,
            'emails' => $emails,
        ]);

        collect($emails)->each(function ($email) use ($endpoint) {

            try {

                \Log::info('Enviando email de recuperação para: ' . $email);

                Mail::to($email)->send(
                    new EndpointRecoveredMail($endpoint)
                );

            } catch (\Exception $e) {

                \Log::error('Erro ao enviar email de recuperação', [
                    'email' => $email,
                    'error' => $e->getMessage(),
                ]);
            }
        });
    }
}