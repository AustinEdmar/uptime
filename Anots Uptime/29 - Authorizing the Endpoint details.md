1 - vamos autorizar o endpoint
EndpointPolicy.php

  public function view(User $user, Endpoint $endpoint): bool
    {
         return $user->id === $endpoint->site->user_id
            ? Response::allow()
            : Response::deny('Você não tem permissao para ver este endpoint.');
    }



<!-- 2  no provider-->
Gate::define('view-endpoint', [EndpointPolicy::class, 'view']);


<!-- 3 - agora vou no controller -->

<?php

namespace App\Http\Controllers;

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
        if (! Gate::allows('view-endpoint', $endpoint)) {
                abort(403);  
            }

       return Inertia::render('Endpoint', [
         
             'endpoint' => EndpointResource::make($endpoint)
       ]);
    }
}


