<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Site;

class SitePolicy
{
    public function storeEndpoint(User $user, Site $site)
    {
        
        return $user->id === $site->user_id;
    }
}
