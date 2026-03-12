<?php

namespace App\Http\Controllers;

use App\Models\Endpoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EndpointDestroyController extends Controller
{
    
    public function __invoke(Request $request, Endpoint $endpoint)
    {
            if (! Gate::allows('delete-endpoint', $endpoint)) {
                abort(403);
            }
            $endpoint->delete();

            return back();
    }
}
