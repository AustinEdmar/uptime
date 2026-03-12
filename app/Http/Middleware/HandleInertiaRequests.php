<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Http\Resources\EndpointFrequencyResource;
use App\Enums\EndpointFrequency;
use App\Http\Resources\SiteResource;
use App\Models\Site;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],

            'endpointFrequencies' => EndpointFrequencyResource::collection(EndpointFrequency::cases()), // case por temos um enum 
            'sites' => $request->user() ? SiteResource::collection($request->user()->sites()->latest()->get()) : null,
        ];
    }
}
