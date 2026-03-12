<?php

namespace App\Observers;

use App\Models\Site;
use Illuminate\Support\Arr;

class SiteObserver
{

    public function creating(Site $site)
    {
        $parsed = parse_url($site->domain);
        // dd($parsed);
        $site->scheme = Arr::get($parsed, 'scheme');
        $site->domain = Arr::get($parsed, 'host');

        
    }
    
    public function updating(Site $site)
    {
        if (in_array('default', array_keys($site->getDirty()))) {
            $site->user->sites()->whereNot('id', $site->id)->update(['default' => false]);
        }

        /*  if ($site->isDirty('default') && $site->default) {
            $site->user->sites()
                ->whereNot('id', $site->id)
                ->update(['default' => false]);
        } */
    }
}
