docker-compose run --rm artisan make:resource SiteResource 


no controller
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Site;
use App\Http\Resources\SiteResource;


class DashboardController extends Controller
{
    public function __invoke(Request $request, Site $site){

        return Inertia::render('Dashboard', [
            'site' => SiteResource::make($site),
            'sites' => SiteResource::collection(Site::get()),

        ]);
    }
}

<!-- resource

 public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'domain' => $this->domain,
        ];
    }
     -->


<!-- dashboard -->

defineProps({
    site: Object,
    sites: Object
});