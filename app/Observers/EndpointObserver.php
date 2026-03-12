<?php

namespace App\Observers;

use Illuminate\Support\Arr;
use App\Models\Endpoint;
use Illuminate\Support\Str;

class EndpointObserver
{
    
    public function creating(Endpoint $endpoint)
    {

       
       /*  $parsed = parse_url($endpoint->site->url() . '/' . $endpoint->location);

        $endpoint->location = '/' . trim(trim(Arr::get($parsed, 'path'), '/') . '?' . Arr::get($parsed, 'query'), '?');
         */

        $parsed = parse_url($endpoint->site->url() . '/' . $endpoint->location);

        $path = Arr::get($parsed, 'path');
        $query = Arr::get($parsed, 'query');

        $location = trim($path, '/');

        if ($query) {
            $location .= '?' . $query;
        }

        // 🔥 Remove vírgulas, múltiplos espaços e normaliza
        $location = str_replace(',', '', $location);           // remove vírgulas
        $location = preg_replace('/[\s,]+/', '', $location);     // espaços → hífen
        $location = Str::ascii($location);                      // remove acentos

        $endpoint->location = '/' . $location;
        $endpoint->next_check = now()->addSeconds($endpoint->frequency);
    }
}
