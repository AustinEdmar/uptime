<?php

namespace App\Observers;

use App\Models\Check;
use App\Events\EndpointWentDown;

class CheckObserver
{
    //// Evita alertas repetidos: só entra quando o site cai pela primeira vez
    /*  public function created(Check $check): void
    {   

        
        if (
            !$check->isSuccessful() &&
            ($check->previous()?->isSuccessful() || $check->endpoint->checks()->count() === 1)
            ) {
            EndpointWentDown::dispatch($check);
        }
    }   */

    public function created(Check $check): void
{   
    \Log::info('Observer executou', [
        'check_id' => $check->id,
        'is_successful' => $check->isSuccessful(),
        'response_code' => $check->response_code,
    ]);

    if (
        !$check->isSuccessful() &&
        ($check->previous()?->isSuccessful() || $check->endpoint->checks()->count() === 1)
    ) {
        \Log::info('Disparando evento EndpointWentDown');
        EndpointWentDown::dispatch($check);
    }
}
   
        
   
}
