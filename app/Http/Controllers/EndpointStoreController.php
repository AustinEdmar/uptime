<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use App\Http\Requests\EndpointStoreRequest;

class EndpointStoreController extends Controller
{

 

    public function __invoke(EndpointStoreRequest $request, Site $site)
    {
        $site->endpoints()->create($request->only(['location', 'frequency']));

        return back();
        
    }


    
    
}
