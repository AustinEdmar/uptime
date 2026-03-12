<?php

namespace App\Providers;

use App\Models\Endpoint;
use App\Models\Site;
use App\Models\Check;
use App\Observers\EndpointObserver;
use App\Observers\SiteObserver;
use App\Observers\CheckObserver;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Policies\SitePolicy;
use App\Policies\EndpointPolicy;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use App\Events\EndpointWentDown;
use App\Listeners\SendDownEmailNotifications;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Site::observe(SiteObserver::class);
        Endpoint::observe(EndpointObserver::class);
        Check::observe(CheckObserver::class); // esta observando a model Check
        

        Gate::define('delete-endpoint', [EndpointPolicy::class, 'delete']);
        
        Gate::define('update-endpoint', [EndpointPolicy::class, 'update']);

        Gate::define('view-endpoint', [EndpointPolicy::class, 'view']);
       
        Carbon::setLocale('pt');

        
    }
}
