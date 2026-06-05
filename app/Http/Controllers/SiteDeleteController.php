<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class SiteDeleteController extends Controller
{
    public function __invoke(Request $request, Site $site)
    {
        $site->delete();

        return redirect()->route("dashboard");

    }
}
