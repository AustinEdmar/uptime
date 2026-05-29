1 - em usr
 public function sites()
    {
        return $this->hasMany(Site::class);
    }




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
            'sites' => SiteResource::collection(Site::get()),

        ]);
    }



<!-- na model site -->

   public static function booted()

    {
        static::updating(function (Site $site) {


         

            // se o site esta sendo update set para true 
           // e pega todos sites onde id é diferente do site atual e set para false

           if(in_array('default', array_keys($site->getDirty()))) {
                $site->user->sites()->where('id', '!=', $site->id)->update(['default' => false]);
           }
        });
    }


<!-- vamos criar um Observer --> o boot era so para experimento vamos fazer tudo no observer

docker-compose run --rm artisan make:observer SiteObserver


<?php

namespace App\Observers;

use App\Models\Site;

class SiteObserver
{
    public function updating(Site $site)
    {
        if (in_array('default', array_keys($site->getDirty()))) {
            $site->user->sites()->where('id', '!=', $site->id)->update(['default' => false]);
        }
    }
}


<!-- registro no provider 

 public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Site::observe(SiteObserver::class);
    } -->