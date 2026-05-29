<?php

namespace App\Observers;

use App\Events\EndpointRecovered;
use App\Models\Check;
use App\Events\EndpointWentDown;
use Carbon\Carbon;

class CheckObserver
{
    // CheckObserver.php


    // public function created(Check $check): void
    // {
    //     $endpoint = $check->endpoint;

    //     $isDown = !$check->isSuccessful();
    //     $wasDown = $endpoint->is_down;

    //     \Log::info('Observer executou', [
    //         'check_id' => $check->id,
    //         'is_down' => $isDown,
    //         'was_down' => $wasDown,
    //     ]);

    //     // tempo de cooldown
    //     $cooldownMinutes = 30;

    //     $canNotifyAgain =
    //         !$endpoint->last_downtime_notification_sent_at ||
    //         Carbon::parse($endpoint->last_downtime_notification_sent_at)
    //             ->addMinutes($cooldownMinutes)
    //             ->isPast();

    //     \Log::info('DEBUG CONDITIONS', [
    //         'isDown' => $isDown,
    //         'wasDown' => $wasDown,
    //         'canNotifyAgain' => $canNotifyAgain,
    //         'last_notification' => $endpoint->last_downtime_notification_sent_at,
    //     ]);

    //     // endpoint caiu
    //     if ($isDown && (!$wasDown || $canNotifyAgain)) {

    //         \Log::info('Disparando evento EndpointWentDown');

    //         EndpointWentDown::dispatch($check);

    //         $updated = $endpoint->update([
    //             'is_down' => true,
    //             'last_downtime_notification_sent_at' => now(),
    //         ]);

    //         \Log::info('UPDATE RESULT', [
    //             'updated' => $updated,
    //             'endpoint' => $endpoint->fresh(),
    //         ]);
    //     }

    //     // endpoint recuperou
    //     if (!$isDown && $wasDown) {

    //         $endpoint->update([
    //             'is_down' => false,
    //             'last_downtime_notification_sent_at' => null,
    //         ]);
    //     }
    // }

    // public function created(Check $check): void
    // {
    //     $endpoint = $check->endpoint;
    //     $previous = $check->previous();

    //     if (!$check->isSuccessful()) {
    //         // Marca o endpoint como down
    //         $endpoint->update(['is_down' => true]);

    //         // Só dispara o evento na transição: estava OK → ficou down
    //         $isTransition = is_null($previous) || $previous->isSuccessful();

    //         if ($isTransition) {
    //             \Log::info('Disparando evento EndpointWentDown');
    //             EndpointWentDown::dispatch($check);
    //         }
    //     } else {
    //         // Volta ao normal
    //         $endpoint->update(['is_down' => false]);
    //     }


    // }



    public function created(Check $check): void
    {
        $endpoint = $check->endpoint;
        $previous = $check->previous();

        if (!$check->isSuccessful()) {

            // Marca como down
            $endpoint->update(['is_down' => true]);

            // Só dispara quando muda de OK -> DOWN
            $isTransition = is_null($previous) || $previous->isSuccessful();

            if ($isTransition) {
                \Log::info('Disparando evento EndpointWentDown');

                EndpointWentDown::dispatch($check);
            }

        } else {

            // Marca como recuperado
            $endpoint->update(['is_down' => false]);

            // Só dispara quando muda de DOWN -> OK
            $wasDown = !is_null($previous) && !$previous->isSuccessful();

            if ($wasDown) {
                \Log::info('Disparando evento EndpointRecovered');

                EndpointRecovered::dispatch($check);
            }
        }
    }




}
