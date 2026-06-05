<?php

namespace App\Http\Controllers;

use App\Http\Resources\CheckResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Site;
use App\Models\Endpoint;
use App\Http\Resources\SiteResource;
use App\Http\Resources\EndpointResource;
use Illuminate\Support\Facades\Gate;

class EndpointIndexController extends Controller
{
    public function __invoke(Request $request, Endpoint $endpoint)
    {
        /*  if (! Gate::allows('view-endpoint', $endpoint)) {
                 abort(403);  
             } */

        $checks = $endpoint->checks()
            ->latest()
            ->paginate(10);

        return Inertia::render('Endpoint', [
            'endpoint' => EndpointResource::make($endpoint),
            'checks' => CheckResource::collection($checks),
        ]);
    }
}
