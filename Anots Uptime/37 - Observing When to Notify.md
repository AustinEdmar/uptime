1 - php artisan make:observer CheckObserver


2 - em AppServiceProvider.php
<!--  public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Site::observe(SiteObserver::class);
        Endpoint::observe(EndpointObserver::class);
        Check::observe(CheckObserver::class); // esta observando a model Check -->

3 - volto no Observer testar 
<?php

namespace App\Observers;

use App\Models\Check;
use Illuminate\Support\Facades\Log;

class CheckObserver
{
    public function created(Check $check): void
    {
        Log::info("abc");
    }

<!-- 4 - adiciono este no model Check -->
public function previous() 
{
    return $this->endpoint->checks()->orderBy('id', 'desc')->where('id', '<', $this->id)->first();
}

<!-- 5 - volto no Observer e adiciono a logica -->
<?php

namespace App\Observers;

use App\Models\Check;
use Illuminate\Support\Facades\Log;

class CheckObserver
{
    public function created(Check $check): void
    {
        if (!$check->isSuccessful() && ($check->previous()?->isSuccessful() || $check->endpoint->checks()->count() === 1)) {
            Log::info("User Notify: " . $check->id);
        }
    }
    
   
}