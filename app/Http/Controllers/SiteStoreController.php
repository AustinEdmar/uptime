<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SiteStoreRequest;
use Illuminate\Database\QueryException;

class SiteStoreController extends Controller
{
   
    
    /* public function __invoke(SiteStoreRequest $request)
    {
        $site = $request->user()->sites()->create($request->only(['domain']));
       
        return redirect()->route('dashboard', $site);
    } */

        public function __invoke(SiteStoreRequest $request)
{
    try {
        $site = $request->user()
            ->sites()
            ->create($request->only('domain'));

        return redirect()->route('dashboard', $site);

    } catch (QueryException $e) {
        if ($e->getCode() === '23505') {
            return back()
                ->withErrors([
                    'domain' => 'Este domínio já está cadastrado para este usuário.'
                ])
                ->withInput();
        }

        throw $e;
    }
  }
}
