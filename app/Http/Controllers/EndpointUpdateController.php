<?php

namespace App\Http\Controllers;

use App\Models\Endpoint;
use App\Http\Requests\EndpointUpdateRequest;
use Illuminate\Support\Facades\Gate;

class EndpointUpdateController extends Controller
{
    public function __invoke(EndpointUpdateRequest $request, Endpoint $endpoint)
    {
        //dd($endpoint);
         if (! Gate::allows('update-endpoint', $endpoint)) {
                abort(403);  
            }
        $endpoint->update($request->only('location', 'frequency'));
        
        return back();
    } 
}
