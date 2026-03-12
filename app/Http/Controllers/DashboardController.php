<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Site;
use App\Http\Resources\SiteResource;
use App\Enums\EndpointFrequency;
use App\Http\Resources\EndpointResource;

class DashboardController extends Controller
{
    public function __invoke(Request $request, Site $site){

        $site->update([
            'default' => true,
        ]);

        if(!$site->exists){
              $site = $request->user()->sites()->whereDefault(true)->first() ?? $request->user()->sites()->first();
        }
        
        return Inertia::render('Dashboard', [
            'site' => SiteResource::make($site),
            //'endpoints' => EndpointResource::collection($site->endpoints),
            'endpoints' => EndpointResource::collection(
        $site->endpoints()->with([
            'checks' => fn ($q) => $q->latest()->limit(1)
        ])->get()
    ),
        ]);
    }
}
